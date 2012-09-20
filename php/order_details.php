<?php
/**
 * Example of retrieving a single order's details from the Marvelpress Order API
 *
 * @author Lewis Pollard 
 * @copyright Marvelpress Ltd. 2012
 */

$url            = "https://orders.marvelpress.com/orders/order/";
$APIUsername    = "MP-00213/testing";                   
$APIPassword    = "b21efa4cdacfd551e93e4b76ee7c3667";
$order_id		= "MP-00001-86";
$thumbs			= TRUE;

//Initialise cURL
$ch = curl_init();

//Set the url and AUTH. cURL uses GET by default
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_URL,$url.$order_id."/".$thumbs);
curl_setopt($ch, CURLOPT_USERPWD, $APIUsername.':'.$APIPassword);

//Execute post and decode response
$result = json_decode(curl_exec($ch));

//Get http status
$http_status = curl_getinfo($ch , CURLINFO_HTTP_CODE);

curl_close($ch);

//Check if the request was successful 
if($http_status == 200)
{
    // Result now contains associative array of order details with a base64 encoded thumbnail image
    print_r($result);
}
else
{
    echo "Sending Order Failed \n";
    echo $result->Message."\n";
}