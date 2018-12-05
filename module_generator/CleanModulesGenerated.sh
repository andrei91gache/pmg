#!/usr/bin/env bash


if [ "$(id -u)" != "0" ]; then
    echo "This script must be run as root"
    exit 1
else
    path=`pwd`
    rm -fr $path/modules_generated/*
fi
