#!/usr/bin/env bash

alias composer='docker exec -t jobsearch_fpm_1 composer'
alias console='docker exec -t jobsearch_fpm_1 bin/console'
alias mysql='docker exec -ti jobsearch_mysql_1 mysql -u jobsearch -pjobsearch jobsearch'
alias dev='console -e=dev'
alias prod='console -e=prod'
