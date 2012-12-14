
test-postdata:
	curl -d '{"1":"3"}' -b 'email=1' http://localhost/tomato/data.php?postdata=1

test-getdata:
	curl -b 'email=1' http://localhost/tomato/data.php?getdata=1

