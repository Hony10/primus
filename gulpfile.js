/* jshint esversion: 6 */ 
/* jshint strict: false */ 
/* jshint unused: false */ 
/* jshint loopfunc: true */ 

// Include all required dependencies
const del = require('del');
const path = require('path');
const fs = require('fs');
const merge = require('merge-stream');
const { watch, series, src, dest, task } = require('gulp');
const rename = require('gulp-rename');
const order = require('gulp-order');
const gulpif = require('gulp-if');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const uglifycss = require('gulp-uglifycss');
const sass = require('gulp-sass');
const dotenv = require('dotenv');
const connect = require('gulp-connect-php');
const mapStream = require('map-stream');

// Path to the theme file source and destination is here
// This must be changed if the theme path changes after an update
const fileSep = path.sep;

// Build variables, change these to minify and optimise build for production or debug
let minifyJs = false;
let minifyCss = false;
let sourceMaps = true;


// removeParentPath will remove the parent path from the string and trailing slash if it is present
function removeParentPath(path, searchPath) {
    if (path.indexOf(searchPath + fileSep) === 0) {
        path = path.substr((searchPath + fileSep).length);
    } else if (path.indexOf(searchPath) === 0) {
        path = path.substr(searchPath.length);
    }
    return path;
}

// getFolders will list all folders in the supplied directory
function getFolders(dir) {
    return fs.readdirSync(dir)
    .filter(function(file) {
        return fs.statSync(path.join(dir, file)).isDirectory();
    });
}

// getFiles will get all files in the current directory
function getFiles(dir) {
    return fs.readdirSync(dir)
    .filter(function(file) {
        return fs.statSync(path.join(dir, file)).isFile();
    });   
}

// This will clean the destination directory of all theme assets
function clean(cb) {
    del([path.join('public', 'assets', 'js'), path.join('public', 'assets', 'css')])
    .catch(function() {})
    .then(function() {
        cb();
    });
}

// Build the JavaScript bundles and add to public path
function buildJS(cb) {
    const jsPath = 'js';
    const libPath = path.join('js', 'lib');
    const output = path.join('public', 'assets', 'js');

    var folders = getFolders(jsPath);
    var streams = [];

    if (folders.length === 0) {
        cb();
        return;
    }

    folders.forEach(function(folder) {
        // The lib folder contains all third party scripts, we don't need to worry about
        // processing these, just copy them to the destination so they can be
        // included separately
        if (folder === 'lib') {
            streams.push(
                src([path.join(jsPath, folder, '**/*.js'), path.join(jsPath, folder, '**/*.js.map')], {base: process.cwd()})
                .pipe(rename(function(path) {
                    path.dirname = removeParentPath(path.dirname, libPath);
                }))            
                .pipe(dest(path.join(output, 'lib')))
            );
            return;
        }

        // All other folder get merged into a single script at the root of the JS folder
        streams.push(
            src(path.join(jsPath, folder, '**/*.js'), {base: process.cwd()})
            .pipe(order([
                path.join(jsPath, folder, '*.js'),
                '*.js',
                path.join(jsPath, folder, '*/*.js'),
                '*/*.js',
                path.join(jsPath, folder, '*/*/*.js'),
                '*/*/*.js',
                path.join(jsPath, folder, '*/*/*/*.js'),
                '*/*/*/*.js',
                path.join(jsPath, folder, '*/*/*/*/*.js'),
                '*/*/*/*/*.js',
                path.join(jsPath, folder, '*/*/*/*/*/*.js'),
                '*/*/*/*/*/*.js',
            ]))
            .pipe(gulpif(sourceMaps, sourcemaps.init()))
            .pipe(concat(folder + '.js'))
            .pipe(gulpif(minifyJs, uglify()))
            .pipe(rename(folder + '.min.js'))
            .pipe(gulpif(sourceMaps, sourcemaps.write('./maps')))
            .pipe(dest(output))
        );
    });

    return merge(streams);
}

// Build all stylesheets
function buildCSS(cb) {
    const cssPath = 'sass';
    const output = path.join('public', 'assets', 'css');

    var files = getFiles(cssPath);
    var streams = [];

    if (files.length === 0) {
        cb();
        return;
    }

    files.forEach(function(file) {
        streams.push(
            src(path.join(cssPath, file), {base: process.cwd()})
            .pipe(sass().on('error', sass.logError))
            .pipe(rename({extname: '.min.css'}))
            .pipe(rename(function(path) {
                path.dirname = removeParentPath(path.dirname, cssPath);
            }))
            .pipe(gulpif(minifyCss, uglifycss()))
            .pipe(dest(output))
        );
    });

    return  merge(streams);
}

// livebuild will prompt a build whenever a change in the src directory is detected
function livebuild(cb) {
    watch('js', exports.build);
    watch('sass', exports.build);
    watch('.env', buildEnv);
    cb();
}

// Sets the build for production
function setProduction(cb) {
    minifyJs = true;
    minifyCss = true;
    sourceMaps = true;
    cb();
}

function buildEnv(cb) {
    setTimeout(function() {
        dotenv.config();

        fs.mkdirSync('public/assets/js', {recursive: true});

        var regPublic = /^_PUBLIC_/g;
        fs.writeFileSync(
            path.join('public', 'assets', 'js', 'env.min.js'),
            'var Env={};(function() {\'use strict\';' +
            'var _a={' +
            Object.keys(process.env).map(function(key) {
                if (key.match(regPublic) || (key === 'APPLICATION_VERSION')) {
                    return key + ':"' + process.env[key] + '",';
                }
                return '';
            }).join('') +
            '};' +
            'Env.get=function(b){return _a["_PUBLIC_"+b.toUpperCase()];};' +
            'Env.version=function(){return _a["APPLICATION_VERSION"];};' +
            '})();'
        );
    }, 200);
    cb();
}

// "gulp build" will run these three processes
exports.build = series(
    clean,
    buildJS,
    buildCSS,
    buildEnv
);

// "gulp watch" will rebuild all assets whenever changes are detected
exports.watch = series(exports.build, livebuild);

// "gulp production" will build all assets optimised for production use
exports.production = series(setProduction, exports.build);
