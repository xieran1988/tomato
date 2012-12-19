#!/bin/bash

url=localhost/tomato/data.php 
T() {
	echo "Test:" $*
	curl $* 
}

mysql < table.sql
T "$url?reg=1&email=aa"
T "$url?reg=1&email=aa&pass=cc"
T "$url?login=1&email=aa&pass=cc"
T "$url?login=1&email=aa&pass=cdd"
T "$url?getdata=1&email=aa"
T -d '{"val":{"title":"cc"}}' "$url?postdata=1&email=aa&act=add" 
T -d '{"val":{"title":"cc"}}' "$url?postdata=1&email=aa&act=del" 
T "$url?getdata=1&email=aa"
