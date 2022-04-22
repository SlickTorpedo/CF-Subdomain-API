<?php
/*
-----------------------------------------

Please enter your information to the variables below.
Replace all the things inside the quotes and DON'T touch anything else!

-----------------------------------------
*/

$cloudflare_email = "yourEmail@yourSite.com";
//This is the email you use to sign in to cloudflare.

$cloudflare_api_token = "yourCloudflareAPIToken";
//This is the API token for your cloudflare account. Make sure it has the proper zone permissions!
//For more information, Slick#7454 on discord or https://nexussociety.net/support

$auth_password = "Password";
//You need to decide this password. By Default it's "Password" but if you'd like you may change it.
//This will be required in the Discord Bot Part so write it down!
//You are not required to change it, it works with "Password" and you won't need to configure the discord bot much.

$cloudflare_zone = "yourZone";
/* 
This is the domain zone! You can find it at the following:
https://dash.cloudflare.com > Your Domain > Scroll Down until you see "API" on the right.
It's the option that says "Zone ID".
*/

$cloudflare_domain = "yourDomain";
//This is the actual domain.
//Please only put your domain no subdomain.
//For example put nexussociety.net not https://nexussociety.net or panel.nexussociety.net

/*
-----------------------------------------
DATABASE
-----------------------------------------
*/

$servername = "localhost";  //Database IP
$username = "root";         //Database Username
$password = "password";     //Database Password
$dbname = "nexussociety";   //Database Name

/*
-----------------------------------------
DATABASE
-----------------------------------------
*/


$api_enabled = "true";
//Set this to false to disable this all just incase!


/*
-----------------------------------------

/!\ IMPORTANT /!\

EVERYTHING BELOW HERE IS THE CODE
DO NOT MODIFY THIS UNLESS YOU KNOW WHAT YOU'RE DOING!

/!\ IMPORTANT /!\

-----------------------------------------
*/

$true = "true";
if($api_enabled == $true) {
    $verify = "enabled";
} else {
    unset($verify);
}
//Enables the API if $api_enabled is set to true.


if (isset($verify)) { //checks to make sure API is set to true
  
	$conn = mysqli_connect($servername, $username, $password, $dbname); //make database connection
  
	if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
	}
    // SQL Errors

	$sql = "SELECT * FROM subdomainapi"; //Get the subdomain cache from Discord
	$result = mysqli_query($conn, $sql); //Execute SQL Statement
    if (mysqli_num_rows($result) > 0) { //Make sure there's results
	    while($row = mysqli_fetch_assoc($result)) { //Display them and format
            //create the data needed for the first DNS record from the database
      	    $name = $row["name"];
      	    $ip = $row["ip"];
            $port = $row["port"];
            $discid = $row["discorduserid"];
            //create curl request for Cloudflare API
    	    $ch = curl_init();
            $headers = array(
                 'X-Auth-Email: '.$cloudflare_email.'',
                 'Authorization: Bearer '.$cloudflare_api_token.'',
                 'Content-Type: application/json',
            );

            $password = $auth_password; //Set the password for the URL so random people can't update it if you don't want them to
            
            //Create the cloudflare array
            if($_GET['password'] == $password) {
                $data = array(
                    'type' => 'A',
                    'name' => ''.$name.'',
                    'content' => ''.$ip.'',
                    'ttl' => '0',
  			        'proxied' => false,
                );
            } else {
                die("Something's missing...");
            }

            //Send the "A" record to cloudflare API
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$cloudflare_zone."/dns_records");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_exec($ch); //Send it to cloudflare
            curl_close($ch); //Close the curl request

            echo "<br>"; //Not sure why this is here. It was here when I made it the first time so I'm leaving it.

            //Prepare the second API call for the SRV record
            $ch = curl_init();
            $headers = array(
                'X-Auth-Email: '.$cloudflare_email.'',
                'Authorization: Bearer '.$cloudflare_api_token.'',
                'Content-Type: application/json',
            );


            $password = $auth_password; //Check the password just incase :)

            //Create the array with Cloudflare Data
            if($_GET['password'] == $password) {
                $data = array(
	                'type' => 'SRV',
	                'data' => array(
	                "name"=>"".$name."",
	                "ttl"=>0,
	                "service"=>"_minecraft",
	                "proto"=>"_tcp",
	                "weight"=>0,
	                "port"=>"".$port."",
	                "priority"=>0,
	                "target"=>"".$name.".".$cloudflare_domain."",
	            ));
            } else {
                die("Something's missing..."); //Wrong password
            }

            //Ship it off to cloudflare (This is the SRV record)
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$cloudflare_zone."/dns_records");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_exec($ch); //Send it to cloudflare
            curl_close($ch); //Close the curl
    
    }

    //Move it form Cache -> Official Subdomain Log
    //So the bot knows the subdomain is already in use!
    $sql = "INSERT INTO subdomains (ip,name,port,discorduserid) VALUES ('$ip','$name','$port','$discid');";
	$result = mysqli_query($conn, $sql);

    //Delete the old subdomain from cache so it dosen't keep refreshing itself
    $sql = "DELETE FROM subdomainapi WHERE name = '$name'";
	$result = mysqli_query($conn, $sql);

    //let bot know it worked succesfuly
    echo "<br><br>Transferred to more stable table";
  } else {
    //Nothing in the cache database
    echo "Nothing to update!";
  }

    //boop
	mysqli_close($conn);
}
?>
