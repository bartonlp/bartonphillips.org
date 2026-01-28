# Examples

**INPORTANT NOTE:** you can disable the log function by either setting $_site->noTrack=true or adding "noTrack":true, to the mysitemap.json.
Doing either of those things will keep the SimpleSiteClass from using the 'logagent' table to log visitors. This will also mean that some of the
example programs will not work.

First you will need to install mysql if you don't already have it. You will need to install the PHP extension for mysql. 
The SimpleSiteClass code runs with PHP 8, and it may not work with older versions of PHP.

Once you have mysql and the PHP extension you can set up mysql. These examples use a database called 'barton' with user 'barton'. There is
a file in this directory called schema.sql. You can run it in mysql and create the table.

I have set up a .htaccess file which has the *Header set* for the site loader.

Now that the database is set up you can run the examples.

I have included a couple of examples to show how to use the SimpleSiteClass. They are in two seperate directories: IfComposer and IfDownloadedZip.
If you set up your directory structure as:  
/var/www/  
/var/html  
and used **composer** to create a /var/www/vendor/bartonlp/simple-site-class directory, you should use IfComposer examples.  
If you downloaded the zip file from github.com/bartonlp/simple-site-class you should use the IfDownloadedZip directory.

In either case, from the *examples* directory in the downloaded location, do:
php -S localhost:3000  
Then open your browser and enter:  
localhost:3000/example1.php

Each example has links to the rest of the examples.

Any questions can be directed to bartonphillips@gmail.com

Have fun

Last Modified October 31, 2023
