/*
 * Copyright 2012 Marvelpress Ltd
 */
package uk.co.marvelpress;

import com.sun.org.apache.xml.internal.security.utils.Base64;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import sun.misc.BASE64Encoder;
import org.apache.commons.io.IOUtils;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
/**
 *
 * @author lewis
 */
public class MarvelAPICodeSample {
    private static String imagePath = "/path/to/image";
    private static String url = "https://orders.marvelpress.com/orders/order";
    private static String apiUsername = "MP-00213/testing";
    private static String apiPassword = "b21efa4cdacfd551e93e4b76ee7c3667";
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        String imageData = new String();
        try {
            // Encode image data to base64
            File file = new File(imagePath);
            BASE64Encoder encoder = new BASE64Encoder();
            ByteArrayOutputStream output = new ByteArrayOutputStream();
            encoder.encode(new FileInputStream(file), output);
            imageData = output.toString().replace("\n", "");
        } catch (IOException ex) {
            System.out.println("Could not encode image data: " + ex.getLocalizedMessage());
            System.exit(1);
        }
        
        /* This isnt' pretty but Java has no built in JSON handling classes. 
         * Google will turn up some open source libraries if you want to turn 
         * Maps or Arrays into JSON strings/objects */

        String requestJson = "{"
                    +      "\"account_code\":\"MP-00001\","
                    +      "\"item_count\":1,"
                    +      "\"image_location\":\"file\","
                    +      "\"items\":["
                    +          "{"
                    +              "\"product_code\":\"MPP-3D-IPHONE-4-P\","
                    +              "\"description\":\"description\","
                    +              "\"reference\":\"reference\","
                    +              "\"qty\":3,"
                    +              "\"data\":" + "\"" + imageData + "\"" 
                    +          "}"
                    +      "],"
                    +      "\"shipping_address\":{"
                    +          "\"first_name\":\"Oliver\","
                    +          "\"last_name\":\"Smith\","
                    +          "\"company\":\"Marvelpress Ltd\","
                    +          "\"line_1\":\"13a Provincial Park\","
                    +          "\"line_2\":\"Nether Lane\","
                    +          "\"town\":\"Sheffield\","
                    +          "\"county\":\"Yorkshire\","
                    +          "\"country\":\"GB\","
                    +          "\"post_code\":\"84120\","
                    +          "\"tel_number\":\"01142454494\","
                    +          "\"email\":\"oliver@marvelpress.co.uk\""
                    +     "},"
                    +     "\"shipping_method\":\"RM1\""
                    + "}";
        System.out.println(requestJson);

        try {
            // Create client and post request
            HttpClient client = new DefaultHttpClient();
            HttpPost post = new HttpPost(url);
            
            // Set up post data
            List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
            nameValuePairs.add(new BasicNameValuePair("details", requestJson));
            nameValuePairs.add(new BasicNameValuePair("debug", "true"));
            post.setEntity(new UrlEncodedFormEntity(nameValuePairs));
            
            // Set up authorization
            String userpass = apiUsername + ":" + apiPassword;
            post.setHeader("Authorization", "Basic " + Base64.encode(userpass.getBytes()));

            // Execute and get response
            HttpResponse response = client.execute(post);
            
            // Check success
            if (response.getStatusLine().getStatusCode() == 200) {
                System.out.println("200 Order successful");
            } else {
                System.out.println(response.getStatusLine().getStatusCode() + " Order failed");
            }
            
            System.out.println(IOUtils.toString(response.getEntity().getContent()));
            
        } catch (IOException e) {
            System.out.println("Could not open connection: " + e.getLocalizedMessage());
        }         
    }
}
