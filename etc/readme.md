This is just some extra stuff that you may need as well as the install proccess!
#
For support you may go to https://nexussociety.net/support or contact Slick#7454 on discord!
#
**INSTALLING**<br>
The first step is to download all the files.

Then take the /web folder and upload the contents to your webserver.

To check it, go to https://yoursite.com/cf

If it says something about "Nothing To Update" then you did it correctly.

Next, go to your webserver, go to /cf/index.php

You will need to configure a lot of values to the proper things.

After that, go to your database and run the following SQL commands:

```CREATE TABLE subdomaincache (ip LONGTEXT);```<br>
```CREATE TABLE subdomains (ip LONGTEXT, name LONGTEXT, port BIGINT, discorduserid BIGINT);```<br>
```CREATE TABLE subdomainapi (ip LONGTEXT, name LONGTEXT, port BIGINT, discorduserid BIGINT);```<br>
