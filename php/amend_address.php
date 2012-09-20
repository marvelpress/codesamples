<?php
/**
 * Example of amending a shipping address with the Marvelpress Order API
 *
 * @author Lewis Pollard 
 * @copyright Marvelpress Ltd. 2012
 */

$url            = "https://orders.marvelpress.com/orders/order/address/";
$APIUsername    = "MP-00213/testing";                   
$APIPassword    = "b21efa4cdacfd551e93e4b76ee7c3667";

//Prepare the order as an array
$order = array( 
  'order_id' => "MP-00001-76",
  'shipping_address' => array(
    'first_name' => 'Lewis',
    'tel_number' => '0121123456'
  )
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
    echo "Amended address.\n";
}
else
{
    echo "Address amendment Failed \n";
    echo $result->Message."\n";
}