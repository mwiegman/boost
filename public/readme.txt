Getting Started

--Welcome

Are you a small business provider of extracurricular activities or other similar services? Are you looking for a CMS that can help you manage and grow your business? Boost CMS may be the answer you're looking for!

--Features

*User-friendly backend for adding, editing, and deleting classes.
*Easy-to-access class rosters and related information.
*Beautifully designed front end that will draw in new customers and retain the ones you have.
*Authenticate users and register new students.
*Built with Bootstrap <http://getbootstrap.com/> and Sass <http://sass-lang.com/> for easy customization.
*Javascript form validation with jQuery validation plugin <https://jqueryvalidation.org/>.
*Easy to install; just download the github repository to get started.        

--Installation

--System Requirements
Before you install Boost, take note of the following system requirements:

*PHP 5.5+
*Apache web server 2.4.9+
*MySQL 5.5+
*Compass <http://compass-style.org/> css authoring framework     

Visit <https://github.com/mwiegman/boost-phase4> to download the github repository and install to the directory of your choice. This repository contains the bootstrap and sass configuration files, all necessary templates, pages, and scripts, and the sql "create table" code.

--Included Files
The following is a list of the major files included with the application:

*Public Directory
-index.php
This file is the foundation of Boost's templating system. Major modifications to the site's layout and included files should be made here.     
*config.rb
This file contains the configuration options for Compass.

*Sass Directory
This directory contains core Bootstrap css and custom Sass stylesheet. Make all css changes to these files. Any changes made to the stylesheets in the CSS directory will be overriden when compiled.
-bootstrap/_bootstrap-variables.scss
Override Bootstrap variables here (defaults from bootstrap-sass v3.3.6).     
-styles.sccs
Customize your app's design with this file. Styles here will overide Bootstrap defaults.

*Upload Directory
The app's main content is located in the upload directory.
-mysqli_connect.php
Change the constants in the file to access your database.

*Includes Directory
-header.inc.php
Make changes to your site's header and navigation options using this file.
-footer.inc.php
Customize your site's footer content here.
-language.inc.php
Define page titles and descriptions with this file. To add constants for a new page, name the constant with the pagelet's title

*Pagelets Directory
The individual page contents are located in the pagelets directory. To add a new page to your app, save it here with a file name format of "paglet_title.inc.php". See individual pagelets for comments with descriptions and code logic for each script.ake sure to change the $to variable in the contact pagelet to customize the email address where the contact form will be sent.     

*CSS Directory
This directory contains the animate.css CSS library and the styles.css file that is compiled by Compass. Changes to the site's style should be made to the sass file(sass/styles.scss), as changes made here will be overridden.

*JS Directory
Bootstrap javascript files are located here and Boost's javascript file are located here.
-script.js  
Add custom javascript for the site to this file. Customize form validation for use with the jQuery validation plugin here.

*Images Directory
This directory contains the app's background images. The site was designed with minimal images to minimize loading times.

--MySQL
The SQL "create table" code can be found in boost.sql. Use this code to create a database with the proper structure to run the app.
Create Admin
To add administrative users to the app, insert a row in the 'users' table with an 'admin' column value of '1'.

--Feature Requests

Application features to be developed include:

*Online payment processing (paypal integration)
*Third-party login integration (Google, Facebook, or Amazon account login)
*Keyword class search.
*Allow users to register multiple students at one time.
*Allow admin access to registered users list.

--Known Issues

*On initial update of profile information, a confirmation modal is shown and a modal requesting the profile to be completed, even though it is now complete.
*On signup, usernames containing numbers will throw an error.
*Sort feature for class list was not working correctly and was removed. This feature needs to be corrected and reimplimented.

--General Use for Admins
The backend of the Boost app consists of 4 pages.

*addclass.inc.php

Use the form on this page to add classes to the classes table. The form contains inputs for class title, location, category, price, start and end dates, and description. All fields are required. Price should be entered without the dollar sign or decimal point/trailing digits. Dates should be entered in YYYY/MM/DD format.

*classlist.inc.php

This page shows all active classes in the database. Clicking delete link will set the class to inactive in the database and remove that class from the class list. The edit link will bring you to the edit class page.

*modifyclass.inc.php

Use the form on this page to edit classes already added to the classes table.

*registerlist.inc.php

This page displays all classes from the classes table and all students registered for each class.