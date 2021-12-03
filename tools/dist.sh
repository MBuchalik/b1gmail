#!/bin/bash

set +errexit

function show_help() {
	echo "Usage: $0 [arguments]"
	echo ""
	echo "    -u, --update             Create an update instead of full package"
	echo "    -f <ref>, --from <ref>   Version to update from (default: last tag)"
	echo "    -t <ref>, --tag <ref>    Create archive from given git commit ref"
	echo "    -h, --help               Show help"
	echo ""
	echo "If no arguments are specified, a full package for master will be created."
	echo ""
	exit 0
}

# Default settings
TAG="master"
UPDATE=false
FROM=$(git describe --tags $(git rev-list --tags --max-count=1))

# Extract command line args
while [[ $# > 0 ]]; do
	arg="$1"
	case $arg in
		-u|--update)
			UPDATE=true
			;;
		-f|--from)
			FROM="$2"
			shift
			;;
		-t|--tag)
			TAG="$2"
			shift
			;;
		-h|--help)
			show_help
			;;
		*)
			echo "Unknown argument: $arg"
			exit 1
			;;
	esac
	shift
done

COMMIT_HASH=$(git rev-parse --short $TAG)

if $UPDATE; then
	echo "Creating b1gMail update archive for tag $FROM -> $TAG (commit hash $COMMIT_HASH)"
else
	echo "Creating b1gMail full archive for tag $TAG (commit hash $COMMIT_HASH)"
fi

if [ ! -x "$(which php)" ]; then
	echo "PHP interpreter not found"
	exit 1
fi
TOOLS_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TEMP_DIR="$TOOLS_DIR/dist-$(date +%s)-$$.tmp"
if $UPDATE; then
	ARCHIVE="$(pwd)/dist-${FROM}-to-${TAG}-update.zip"
else
	ARCHIVE="$(pwd)/dist-${TAG}-release.zip"
fi

echo "Cleaning up..."
rm -f $ARCHIVE
mkdir $TEMP_DIR

echo "Exporting $TAG..."
cd $TOOLS_DIR/../
git archive $TAG | tar -x -C $TEMP_DIR

if $UPDATE; then
	echo "Determining changed files..."
	CHANGED_FILES=$(git diff --name-only "${FROM}" "${COMMIT_HASH}" | grep ^src/ | sed 's/^src\///')
fi

echo "Processing dist files..."
cd $TEMP_DIR
cp dist-files/changelog.html .
cp dist-files/liesmich.html .
cp dist-files/readme.html .

echo "Creating version-info..."
echo "Exported on $(date)" > version-info
echo "Based on commit hash $COMMIT_HASH" >> version-info
if $UPDATE; then
	echo "Update package from $FROM" >> version-info
fi

if $UPDATE; then
	echo "Copying changed files..."

	mkdir upload

	cp -R src/setup upload/setup

	for file in $CHANGED_FILES; do
		[ -d "upload/$(dirname $file)" ] || mkdir -p "upload/$(dirname $file)"
		[ -e "src/$file" ] && cp "src/$file" "upload/$file"
	done

	rm -rf src
else
	mv src upload
fi

echo "Removing unnecessary files..."
cd upload
rm -rf serverlib/3rdparty/fpdf/
rm -f plugins/profilecheck*
rm -f plugins/templates/images/koobi*
rm -f plugins/templates/images/news_*
rm -f plugins/templates/images/phpbb*
rm -f plugins/templates/images/plzeditor_*
rm -f plugins/templates/images/wbb*
rm -f plugins/templates/images/modfax*
rm -f plugins/templates/images/sponts*
rm -f plugins/templates/images/modsig*
rm -f plugins/templates/images/widget_calculator*
rm -f plugins/templates/accountmirror.*
rm -f plugins/templates/widget.calculator.*
rm -f plugins/premiumaccount.plugin.php
rm -f plugins/templates/bms.*
rm -f plugins/templates/pacc.*
rm -f plugins/templates/images/pacc_*
rm -f plugins/templates/images/bms_*
rm -f plugins/b1gmailserver.plugin.php
rm -f plugins/vbulletin.auth.php
rm -f plugins/*.auth.*
rm -f plugins/accountmirror.*
rm -f plugins/pluginupdates.*
rm -f plugins/widget.calc.*
rm -f plugins/news.plugin.php
rm -f plugins/plzeditor.plugin.php
rm -f plugins/whitelist.*
rm -f plugins/spontsconnector.*
rm -f plugins/signature.*
rm -f plugins/fax.*
rm -f plugins/templates/vbauth.plugin.prefs.tpl
rm -f plugins/templates/images/vbulletin32.png
rm -f plugins/templates/*auth*.tpl
rm -f plugins/templates/news.*
rm -f plugins/templates/plzeditor.*
rm -f plugins/templates/sponts.*
rm -f plugins/templates/modfax.*
rm -f plugins/templates/modsig.*

if $UPDATE; then
	while [ $( find . -depth -type d -empty | wc -l ) -gt 0 ]; do
		find . -depth -type d -empty -print | xargs rmdir
	done
fi

echo "Converting languages..."
[ -e languages/deutsch.lang.php ] && php $TOOLS_DIR/lang_iso.php languages/deutsch.lang.php
[ -e languages/english.lang.php ] && php $TOOLS_DIR/lang_iso.php languages/english.lang.php

echo "Compressing JS files..."
[ -d clientlib/ ] && php $TOOLS_DIR/compress_js.php clientlib/
[ -d admin/templates/js/ ] && php $TOOLS_DIR/compress_js.php admin/templates/js/
[ -d templates/modern/js/ ] && php $TOOLS_DIR/compress_js.php templates/modern/js/

if $UPDATE; then
	echo "Preparing update package..."
	rm -f setup/index.php
	rm -f serverlib/version.inc.php
	rm -f serverlib/config.default.inc.php

	if [ -e serverlib/config.inc.php ]; then
		echo "ERROR: config.inc.php in update package!"
		exit 1
	fi
else
	echo "Preparing release package..."
	rm setup/update.php
	mv serverlib/config.default.inc.php serverlib/config.inc.php
fi

echo "Packaging..."
cd $TEMP_DIR
zip -q -r -9 $ARCHIVE version-info *.html upload/

echo "Cleaning up..."
rm -rf $TEMP_DIR

echo "Done, $ARCHIVE created."

exit 0
