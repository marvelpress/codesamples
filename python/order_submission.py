#
# Example of sending a print ready JPEG image to the Marvelpress Order API
#
# This example makes use of the httplib2 python library to perform the POST request, similar results
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
url     = "https://orders.marvelpress.com/orders/order"

# Set image path
imagepath   = "/path/to/print/ready/image"

# Prepare the order as an array
order = { 
  'account_code' : "MP-00213",    #Your account code
  "item_count" : 1,               #The number of item lines (used to verify all items are received correctly)
  "image_location" : 'file',      #The method used to send the images - file / url, only file is supported
  
  "items" : [             #Array of order items
         {
            "product_code"  : "AA01001P",        #The product code of the item (from the price list)
            "description"   : "description",     #A customer item description string
            "reference"   : "reference",         #Customer item reference (maybe the internal order number)
            'qty'     : 3,                       #The quantity of this item
            "data"      :  base64.b64encode(open(imagepath, 'rt').read()) #The base64 encoded image
         }
    ],

  "shipping_address" : {        #The shipping details
      'first_name'    : 'Oliver' ,  
      'last_name'     : 'Smith',
      'company'       : 'Marvelpress Ltd',
      'line_1'        : '13a Provincial Park',
      'line_2'        : 'Nether Lane',
      'town'          : 'Salt Lake City',
      'county'        : 'Yorkshire',
      'country'       : 'GB',       
      'post_code'     : '84120',
      'tel_number'    : '01142454494',
      'email'       : 'oliver@marvelpress.co.uk'
  }, 

  'shipping_method' : 'RM1'       #The shipping method code (From table in documentation)
}

# Setup httplib2
http = httplib2.Http()
http.add_credentials(username, password)

# Setup and perform POST

# response consists of a tuple where [0] is the response object and [1]
# the document body
response = http.request(
    url, 
    "POST", 
    headers = {'Content-type': 'application/x-www-form-urlencoded'},
    body = urllib.urlencode({
      "details": json.dumps(order),
      "debug" : "false"
    })
)

#Check response status and show returned string
if response[0].status == 200:
    print "Update OK!"
    print response[1]

else:
    print "Order Submission Failed"
    print response[1]