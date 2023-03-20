#!/usr/bin/env bash
# Usage: ./bump_version.sh <major|minor|patch> - Sets the current version
#
# Usage 2: ./bump_version.sh <version-from> <version-to>
# 	e.g: ./bump_version.sh 1.1.1 2.0

set -e

# Define which files to update and the pattern to look for
# $1 New version
function bump_files() {
    bump .env "^APP_VERSION=.\+$" "APP_VERSION=$1"
}

function bump() {
	echo -n "Updating $1..."
	tmp_file=$(mktemp)
	rm -f "$tmp_file"
	sed -i "s/$2/$3/1w $tmp_file" $1
	if [ -s "$tmp_file" ]; then
		echo "Done"
	else
		echo "Nothing to change"
	fi
	rm -f "$tmp_file"
}

function confirm() {
	read -r -p "$@ [Y/n]: " confirm

	case "$confirm" in
		[Nn][Oo]|[Nn])
			echo "Aborting."
			exit
			;;
	esac
}

new_version=$(cat package.json \
  | grep version \
  | head -1 \
  | awk -F: '{ print $2 }' \
  | sed 's/[",]//g' \
  | tr -d '[[:space:]]')

echo $new_version

if ! [[ "$new_version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
	echo >&2 "'to' version doesn't look like a valid semver version tag (e.g: 1.2.3). Aborting."
	exit 1
fi

bump_files "$new_version"