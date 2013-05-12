using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Net;
using System.Web;

namespace MPExample
{
    class MPExample
    {
        static void Main(string[] args)
        {
            //Setup the parameters
            string baseAddress = "https://orders.marvelpress.co.uk/orders/order/";
            string filename = "<Path to image file>";
            
            FileStream fs = new FileStream(filename, FileMode.Open, FileAccess.Read);
            byte[] filebytes = new byte[fs.Length];
            fs.Read(filebytes, 0, Convert.ToInt32(fs.Length));

            /*
             * Encode to base64, DO NOT add linebreaks, replace all + signs with encoded version 
             * so that it's not interpreted as a space
             */
            string encodedData = Convert.ToBase64String(filebytes).Replace("+", "%2B");

            /*
             * Build the string for the POST
             * 
             * There are two main parameters:
             * 
             * details - JSON string containing order details
             * debug - boolean to indicate if the order is a test (during integration MP force all requests to be tests)
             */
            string postData = "details={\"account_code\":\"MP-00001\",\"item_count\":1,\"image_location\":\"file\",\"items\":[{\"product_code\":\"AJ01011P2\",\"description\":\"description\",\"reference\":\"reference\",\"qty\":2,\"data\":\"" + encodedData + "\"}],\"shipping_address\":{\"first_name\":\"Oliver\",\"last_name\":\"Smith\",\"company\":\"Marvelpress Ltd\",\"line_1\":\"13a Provincial Park\",\"line_2\":\"Nether Lane\",\"county\":\"Sheffield\",\"country\":\"GB\",\"post_code\":\"S35 9ZX\",\"tel_number\":\"01142454494\",\"email\":\"test@example.com\"},\"shipping_method\":\"RM1\"}&debug=true";

            /*
             * Setup the webclient and add headers to allow for setting the correct encoding and security settings
             */
            WebClient myWebClient = new WebClient();
            myWebClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
            myWebClient.Credentials = new NetworkCredential("<MP API USER NAME>", "<MP API PASSWORD>"); 

            ASCIIEncoding encoding = new ASCIIEncoding();
            Byte[] bytes = encoding.GetBytes(postData);

            /*
             * Run the request and capture any error message
             */
            try
            {
                byte[] responseArray = myWebClient.UploadData(baseAddress, bytes);
                
                //Echo response to console
                Console.WriteLine("\nResponse received was :{0}", Encoding.ASCII.GetString(responseArray));
            }
            catch (WebException e)
            {
                //Echo error message to console
                var responseStream = e.Response.GetResponseStream();
                string responseText = "";

                if (responseStream != null)
                {
                    using (var reader = new StreamReader(responseStream))
                    {
                        responseText = reader.ReadToEnd();
                    }
                }

                Console.WriteLine(responseText);
            }

            //Stop the program closing when it finishes
            Console.ReadLine();
            
        }
    }
}
