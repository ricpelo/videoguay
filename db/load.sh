#!/bin/sh

BASE_DIR=$(dirname $(readlink -f "$0"))
if [ "$1" != "test" ]
then
    psql -h localhost -U videoguay -d videoguay < $BASE_DIR/videoguay.sql
fi
psql -h localhost -U videoguay -d videoguay_test < $BASE_DIR/videoguay.sql
