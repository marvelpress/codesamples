#
# Example of retrieving a single order's details from the Marvelpress Order API
#
# This example makes use of the httplib2 python library to perform the GET request, similar results
# could also be achieved using python requests http://docs.python-requests.org/en/latest/index.html
# or other http libraries
# 
# Author: Oliver Smith 
# Copyright: Marvelpress Ltd. 2012
#

import urllib
import httplib2
import sys
import base64
import json

# Setup access credentials 
username  = "MP-00213/testing"
password  = "b21efa4cdacfd551e93e4b76ee7c3667"
url     = "https://orders.marvelpress.com/orders/order/"
orderId = "MP-00001-105"
thumbs = "1"

# Setup httplib2
http = httplib2.Http()
http.add_credentials(username, password)

# Setup and perform GET

# response consists of a tuple where [0] is the response object and [1]
# the document body
response = http.request(
    url+orderId+"/"+thumbs, 
    "GET", 
    headers = {'Content-type': 'application/x-www-form-urlencoded'}
)

#Check response status and show returned string
if response[0].status == 200:
    print "Continue OK!"
    print response[1]

else:
    print "Item continue failed"
    print response[1]