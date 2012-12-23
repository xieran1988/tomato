#!/bin/bash

echo "Total user"
echo "select count(*) from user" | mysql tomato -uroot | sed '1d'

echo "Valid user"
echo "select count(distinct(email)) from entry" | mysql tomato -uroot | sed '1d'

echo "List"
echo "select distinct(email), count(*) from entry" | mysql tomato -uroot | sed '1d'

