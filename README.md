# mcbot
Minecraft Bot with Web Interface and Install
Minecraft MC Bot. Built for vanilla minecraft servers, works with out using plugins, and relies strickly on rcon. Has a permission system, web based install and admin, database using sqlite3, written in php and bash. Also can run as service or daemon.

Install
Features
Binary Detection
The script requires mcrcon,screen,inotifywait to be install thus it detects automatically for the user if they are on the server, and directs them to how to install if not

Mod Install Detection
This requires sqlite3 for php, so it checks for that as well as mbstring

Path/File/Mode Detection
It detects if all the files and folders needed exist and are the proper mode and ownership

Testing
The install also test to make sure rcon to the minecraft server works, that the database is connected, and that the bot is up and running

Daemon mode
Also includes all the scripts to set it up as a service and daemon


Admin
Features
Migration Friendly
At any time paths and files can change, every feature is editable via the web admin.

Join
Updating what is first seen by users is important and you have full control over how this is display with a preview of how it will look

Command Editor
There are several generic type of commands to tell users information as well as give them items, there is a built in editor to do so

Strings Editor
Words are important, thus all strings and interactions are editable via the admin, multi-language friendly

Store
Keeping a fresh store is again keeping your community lively. All items in your store, price, points, descriptions are editable via the admin

Points system
The current setup is to have votes worth points, and each site assigned a certain amount. These points than can be used in the store, and even to use on by command use basis, for instance costs 3 points to teleport.

Abuse/Spam Control
Cursing, flood control, spam blocking, all editable

Stats
A pretty exhaustive stat system of anything it can log via the interface it has. How many joins, what ips, time frames, amount of commands used, which commands, how much chat...

Demo: minecraft://play.grx3.com

Website: https://grx3.com
