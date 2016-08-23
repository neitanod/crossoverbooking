Crossover - Chief Software Architect PHP - Final assignment
===========================================================


Contents:

- Assignment description
- Work done
- Online version
- Installation instructions
- Running PHP Tests
- Running Angular Tests



Assignment description
----------------------

The Chief Software Architect PHP second assignment requests the design and
development of a modern and responsive PHP+Javascript application.

It consists of a Site's home page that offers the upcoming conventions and expositions on a map.



Work done
---------

The solution developed integrates several packages and web technologies:

 - Laravel framework 5.2
 - AngularJS 1.5
 - Bootstrap CSS Framework
 - jQuery
 - Google Maps service
 - SVG rendering and interaction
 - HTML5 file upload
 - PHPUnit
 - Testem JavaScript test runner



Online version
--------------

You can see the site in action in:

   http://crossover.ip1.cc



Installation instructions
-------------------------

1) Uncompress the ZIP file and move the contents of /Code into a folder called
   "crossover" in your web server's path,

   or perform this command:
    git clone https://github.com/neitanod/crossoverbooking.git crossover

2) Copy _env as .env and edit the database connection values.

3) Install composer as preferred, one way is to download composer.phar
   from https://getcomposer.org/download/ into the project root folder.

4) Set composer.phar execute permissions and run:

   php composer.phar install

   or (if you have composer installed globally):

   composer install

5) Set full permissions to these folders (and it's subfolders):

    (*) Alternatively you can add the web service user to a group and manage
    permissions in a more secure way, but for demo purposes in a
    private server this should be fine

    storage/*
    bootstrap/cache
    public/documents
    public/pics/*

6) Set execution permissions to /artisan

   chmod 775 artisan

7) Create and populate the Database tables by running the following:

   php artisan migrate:install
   php artisan migrate
   php artisan db:seed

8) The Google Maps service now requires a mandatory API key that is
   protected to quota steal by referer check.  This means that you must
   edit your etc/hosts file to include an alias of localhost called
   localhost.ip1.cc wich complies with the *.ip1.cc rule I had to add
   to the API key I created for this site.
   In simple terms, your local copy of the site will have a broken Google
   Maps implementation unless you are seeing it at the http://localhost.ip1.cc
   simulated domain.

9) Visit the site's URL and see the site in action.

10) You can see a demo video on the /Video folder and or online at:
   http://crossover_video.ip1.cc

11) If you also want the scheduled tasks to automatically run on time on yor server (like the required task to notify users about visitors to the stand after the event is over) you must add this to your server's crontab:

    * * * * * php /path/to/crossover/artisan schedule:run >> /dev/null 2>&1


Running PHP Tests
-----------------

You can run the PHP tests on Linux with the following command:

    ./vendor/bin/phpunit

(*) Please run the tests with a seeded database. In the a real project
    the tests should use a different database and seed it themselves but
    time constraint did not gave room to evolve in that direction.



Running Angular Tests
---------------------

You can run the Angular tests on Linux by following this instructions:

    sudo apt-get install npm
    sudo npm install -g testem

From the project root ('crossover' folder) run:

    testem

You will be prompted to access an URL with your browser.  Open that URL and run the tests.







