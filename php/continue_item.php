<?php
/**
 * Example of continuing an order item with the Marvelpress Order API
 *
 * @author Lewis Pollard
 * @copyright Marvelpress Ltd. 2012
 */

$url            = "https://orders.marvelpress.com/orders/continue/";
$APIUsername    = "MP-00213/testing";                   
$APIPassword    = "b21efa4cdacfd551e93e4b76ee7c3667";
$orderId		= "MP-00001-105";
$itemId			= "1";

//Initialise cURL
$ch = curl_init();

//Set the url and AUTH. Specify this is a POST request.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_URL,$url.$orderId."/".$itemId);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, "");
curl_setopt($ch, CURLOPT_USERPWD, $APIUsername.':'.$APIPassword);

//Execute post and decode response
$result = json_decode(curl_exec($ch));

//Get http status
$http_status = curl_getinfo($ch , CURLINFO_HTTP_CODE);

curl_close($ch);

//Check if the request was successful 
if($http_status == 200)
{
    // Successfully cancelled item
    echo "Item was continued.\n";
}
else
{
    echo "Retrieving Product Failed \n";
    echo $result->Message."\n";
}