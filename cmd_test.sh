#!/bin/bash

T() {
	echo "Test:" $*
	php cmd.php $* | sed '1d'
}

mysql < table.sql
T reg=1 email=aa 
T reg=1 email=aa pass=cc
T login=1 email=aa pass=cc
T login=1 email=aa pass=cdd
T postdata=1 email=aa '{"val":{"title":"cc"}}'
T getdata=1 email=aa
