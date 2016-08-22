Crossover - Chief Software Architect PHP - Final assignment
===========================================================



Assignment description
Work done
Online version
Installation instructions
Running Tests



Assignment description
----------------------

(complete this)



Work done
---------

(complete this)



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

8) Visit the site's URL and see the site in action.

9) You can see a demo video on the /Video folder and or online at:
   http://crossover_video.ip1.cc

Running Tests
-------------

You can run the tests on Linux with the following command:

    ./vendor/bin/phpunit

(*) Please run the tests with a seeded database. In the a real project
    the tests should use a different database and seed it themselves but
    time constraint did not gave room to evolve in that direction.


