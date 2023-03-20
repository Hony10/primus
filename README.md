# Primus Dashboard

This repository contains all code required to build an instance of the Primus Financial Solutions Ltd. dashboard.

[![buddy pipeline](https://app.buddy.works/jamesplant/primus-dashboard/pipelines/pipeline/326194/badge.svg?token=8740cf5b9c462753fbfb90a60d1199e66a88ebd84f63bbc32b132fa35961b274 "buddy pipeline")](https://app.buddy.works/jamesplant/primus-dashboard/pipelines/pipeline/326194)

## Building

You need the following installed to be able to build the dashboard.

- PHP 8.0.2
- Composer 2.0.9
- MySQL 8.x
- Node 14.15.4
- npm 6.14.10
- docker and docker-compose

You also need Gulp installed globally, you can install this using the following command.

```sh
npm install --global gulp gulp-cli
```

Now you can install all required dependencies.

```sh
composer install
npm install
```

Perform the initial build of JS and CSS files by running the below.

```sh
gulp build
```

## Configuration

Copy the `.env.example` file and name it `.env` fill in all required variables.

## Testing

Host the application locally by executing the following.

```sh
php artisan serve --port 8080
```

This will now start listing on `http://127.0.0.1:8080/`.

To watch for changes to JS and CSS source files you can also run the following command alongside the listen command.

```sh
gulp watch
```

And changes to JS and CSS files will be automatically pushed to the assets folder.

## Deployment

You can create a deployment by using the following command (Docker is required).

First create the network bridge that allows the application to communicate with the host MySQL server.

```sh
docker network create --driver=bridge --subnet=192.168.150.0/24 --ip-range=192.168.150.0/24 --gateway=192.168.150.1 my-net
```

Then build the application.

```sh
docker-compose up --build --detach
```

An instance of the application will be built and hosted locally on port `8080`.

## License

This is a closed source project. All code and content are the property of Primus Financial Solutions Ltd.
