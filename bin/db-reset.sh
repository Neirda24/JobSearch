#!/usr/bin/env bash

CURRENT_DIR=$(echo $0 | sed 's#\(^.*/\).*#\1#')
DIR="${PWD}/${CURRENT_DIR}aliases.sh"
source $DIR
shopt -s expand_aliases
dev do:da:dr --force; dev do:da:cr; dev do:mi:mi -n;
