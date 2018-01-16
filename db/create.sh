#!/bin/sh

if [ "$1" = "travis" ]
then
    psql -U postgres -c "CREATE DATABASE videoguay_test;"
    psql -U postgres -c "CREATE USER videoguay PASSWORD 'videoguay' SUPERUSER;"
else
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists videoguay
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists videoguay_test
    [ "$1" != "test" ] && sudo -u postgres dropuser --if-exists videoguay
    sudo -u postgres psql -c "CREATE USER videoguay PASSWORD 'videoguay' SUPERUSER;"
    [ "$1" != "test" ] && sudo -u postgres createdb -O videoguay videoguay
    sudo -u postgres createdb -O videoguay videoguay_test
    LINE="localhost:5432:*:videoguay:videoguay"
    FILE=~/.pgpass
    if [ ! -f $FILE ]
    then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE
    then
        echo "$LINE" >> $FILE
    fi
fi
