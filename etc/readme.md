This is just some extra stuff that you may need as well as the install proccess!
#
For support you can go to https://discord.gg/tN7AX9m8mS
#
**INSTALLING**<br>
The first step is to download all the files.

Then take the ``/web`` folder and upload the contents to your webserver.

To check it, go to https://yoursite.com/cf

If it says something about "Nothing To Update" then you did it correctly.

Next, go to your webserver, go to ``/cf/index.php``

You will need to configure a lot of values to the proper things.

After that, go to your database and run the following SQL commands:

```CREATE TABLE subdomaincache (ip LONGTEXT);```<br>
```CREATE TABLE subdomains (ip LONGTEXT, name LONGTEXT, port BIGINT, discorduserid BIGINT);```<br>
```CREATE TABLE subdomainapi (ip LONGTEXT, name LONGTEXT, port BIGINT, discorduserid BIGINT);```<br>

From there you can now start a Minecraft and visit the ``/mc`` folder for a list of plugins you need.

Download and install those plugins, restart your server, and go to the ``/plugins/Skript/scripts`` folder and empty that folder.

Now upload the content from ``/mc`` folder into ``/plugins/Skript/scripts`` and go to console and type ```/sk reload bot```

All set! The bot should be online and it should all be working!

To test it out, type ``-r`` in any channel. If the bot is online, that means it works.

The commands can be found here:
https://github.com/SlickTorpedo/CF-Subdomain-API/tree/main/mc

Contact me for support!
