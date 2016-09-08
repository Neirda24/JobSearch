#!/usr/bin/env bash

alias composer='docker exec -it jobsearch_fpm_1 composer'
alias console='docker exec -it jobsearch_fpm_1 bin/console'
alias dev='console -e=dev'
alias prod='console -e=prod'
