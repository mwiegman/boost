Visit https://github.com/mwiegman/boost-phase4">https://github.com/mwiegman/boost-phase4 to download the github repository and install to the directory of your choice. This repository contains the bootstrap and sass configuration files, all necessary templates, pages, and scripts, and the sql "create table" code.

Included Files
The following is a list of the major files included with the application:

--Public Directory
*index.php
This file is the foundation of Boost's templating system. Major modifications to the site's layout and included files should be made here.     
*config.rb
This file contains the configuration options for Compass.

--Sass Directory
This directory contains core Bootstrap css and custom Sass stylesheet. Make all css changes to these files. Any changes made to the stylesheets in the CSS directory will be overriden when compiled.
*bootstrap/_bootstrap-variables.scss
Override Bootstrap variables here (defaults from bootstrap-sass v3.3.6).     
*styles.sccs
Customize your app's design with this file. Styles here will overide Bootstrap defaults.

--Upload Directory
The app's main content is located in this directory.
*mysqli_connect.php
Change the constants in the file to access your database.

--Includes Directory
*header.inc.php
Make changes to your site's header and navigation options using this file.
*footer.inc.php
Customize your site's footer content here.
*language.inc.php
Define page titles and descriptions with this file. To add constants for a new page, name the constant with the pagelet's title

--Pagelets Directory
The individual page contents are located in the pagelets directory. To add a new page to your app, save it here with a file name format of "paglet_title.inc.php". See individual pagelets for comments with descriptions and code logic for each script.ake sure to change the $to variable in the contact pagelet to customize the email address where the contact form will be sent.     

--CSS Directory
This directory contains the animate.css CSS library and the styles.css file that is compiled by Compass. Changes to the site's style should be made to the sass file, as changes made here will be overridden.

--JS Directory
Bootstrap javascript files are located here and Boost's javascript file are located here.
*script.js  
Add custom javascript for the site to this file. Customize form validation for use with the jQuery validation plugin here.

--Images Directory
This directory contains the app's background images. The site was deliberately designed with minimal images to minimize loading times.