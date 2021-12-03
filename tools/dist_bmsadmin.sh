#!/bin/bash

VERSION=$( cat ../src/plugins/b1gmailserver.plugin.php | pcregrep -o1 "version[[:space:]]*=[[:space:]]'([0-9]*\.[0-9]*)'" )

bmpluginbuilder \
	--output=BMSAdmin-${VERSION}.bmplugin \
	--name="b1gMailServer Administration Plugin" \
	--version="${VERSION}" \
	--for-b1gmail="7.2.0" \
	--vendor="B1G Software" \
	--vendor-url="http://www.b1g.de/" \
	--vendor-mail="info@b1g.de" \
	--class="B1GMailServerAdmin" \
	../src/plugins/templates/bms.* \
	../src/plugins/templates/images/bms_* \
	../src/plugins/b1gmailserver.plugin.php

