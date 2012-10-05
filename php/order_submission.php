<?php
/**
 * Example of sending a print ready JPEG image to the Marvelpress Order API
 *
 * @author Oliver Smith 
 * @copyright Marvelpress Ltd. 2012
 */

$imagepath      = "/path/to/print/ready/image";
$url            = "https://orders.marvelpress.com/orders/order";
$APIUsername    = "MP-00200/testing";                   
$APIPassword    = "bfgjksdbnfkjlsa78345sdbfglks7";

//Prepare the order as an array
$order = array( 
  "account_code" => "MP-00213",             //Your account code
  "reference" => "A customer order number /reference",       //A reference or order number to aid order tracking
  "image_location" => 'file',               //The method used to send the images - file / url, only file is supported
   "items" => array(                        //Array of order items
         array(
          "product_code"    => "AA01001P",          //The product code of the item (from the price list)
            "description"   => "description",       //A customer item description string
            "reference"     => "reference",         //Customer item reference (maybe the internal order number)
            'qty'           => 3,                   //The quantity of this item
            "data"          => base64_encode(file_get_contents($imagepath)) //The base64 encoded image
         )
    ),
  "shipping_address" => array(              //The shipping details
      'first_name'      => 'Oliver' ,   
      'last_name'       => 'Smith',
      'company'         => 'Marvelpress Ltd',
      'line_1'          => '13a Provincial Park',
      'line_2'          => 'Nether Lane',
      'town'            => 'Salt Lake City',
      'county'          => 'UT',
      'country'         => 'US',                
      'post_code'       => '84120',
      'tel_number'      => '01142454494',
      'email'           => 'oliver@marvelpress.co.uk'
  ), 
  'shipping_method' => 'UPSG'               //The shipping method code (From table in documentation)
);


//Create an escaped string to POST so there are no issues with character encoding 
$orderString = http_build_query(
    array(
        'details' => json_encode($order),
        'debug' => 'false')                 //Indicates if the order is a test order
    );

//Initialise cURL
$ch = curl_init();

//Set the url, number of POST vars, POST data and AUTH
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $orderString);
curl_setopt($ch, CURLOPT_USERPWD, $APIUsername.':'.$APIPassword);

//Execute post and decode response
$result = json_decode(curl_exec($ch));

//Get http status
$http_status = curl_getinfo($ch , CURLINFO_HTTP_CODE);

curl_close($ch);

//Check if the request was successful 
if($http_status == 200)
{
    echo "Order Sent \n";
    echo 'MP Order Number: '.$result->mp_order_number."\n";
}
else
{
    echo "Sending Order Failed \n";
    echo $result->Message."\n";
}