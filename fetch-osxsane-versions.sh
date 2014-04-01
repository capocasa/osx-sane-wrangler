#!/bin/bash

DIR=`dirname $0`

F=$DIR/cache/osxsane/versions

curl -silent http://www.ellert.se/twain-sane/olderversions.html | awk '
BEGIN{
RS="</a>"
  IGNORECASE=1
}
{
  for(o=1;o<=NF;o++){
    if ( $o ~ /href/){
      gsub(/.*href=\042/,"",$o)
      gsub(/\042.*/,"",$o)
      print $(o)
    }
  }
}
' | grep tar.gz > $F

