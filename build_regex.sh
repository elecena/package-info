#!/bin/bash

# TO packages
TO_PACKAGES=`curl -s 'http://www.icpackage.org/TO-65/' curl -s 'http://www.interfacebus.com/semiconductor-transistor-packages.html' curl -s 'https://www.jedec.org/standards-documents/focus/registered-outlines-jep95/transistor-outlines-archive'`

echo $TO_PACKAGES | grep -oE 'TO-[0-9A-Z]+' | grep -vE '92|220|236|247|252|261|263|277' | sort | uniq | cut -d'-' -f 2 | sort -n | tr "\n" '|' | sed 's/|$//g' | awk '{print "TO-?("$1")"}'

# SILP packages
SLIP_PACKAGES=`curl -s 'https://elecena.pl/search.json?q=SILP'`

echo $SLIP_PACKAGES | grep -oE 'SILP-[0-9]+' | sort | uniq | cut -d'-' -f 2 | sort -n | tr "\n" '|' | sed 's/|$//g' | awk '{print "SILP-?("$1")"}'

# SOT packages
SOT_PACKAGES=`curl -s 'https://www.nxp.com/packages/search?q=SOT&type=0'`

echo $SOT_PACKAGES | grep -oE 'SOT[0-9]+' | sort | uniq | sed 's/SOT//g' | sort -n | tr "\n" '|' | sed 's/|$//g' |  awk '{print "SOT-?("$1")"}'
