CodeIgniter Installer
======================

General Information
--------------------

CI Installer is a simple code example of how to create an installer for your downloadable CodeIgniter application.

If you're making a CI app that is designed to be downloaded and self-hosted, CI Installer provides an easy way for the user to get the database structure and  configuration in place.

NOTE: This is to be used as a starting point. You will have to customize it to make it work for your application.

General Instructions
---------------------

1. Download CI Installer (https://github.com/misaelzapata/CodeIgniter-Installer/zipball/master)
2. Make an SQL dump of your desired database structure and initial data and paste it into applications/models/install.sql
(you can also create the database using dbforge)
3. Go to http://example.com/path/to/yourapp/ and see how it goes.

If you have problems or have recommendations, please file an issue at
https://github.com/misaelzapata/CodeIgniter-Installer/issues