## UC Insight

UC Insight is a collection of utilities that can be used with Cisco Unified Communications Manager.  Read below for a current list of features and components.

## Features Overview
- User Management System
- Multi Cluster Support
- AXL Schema Versions 7.0 through 10.5
 
## SQL Query Tool

The SQL Query Tool allows you to make queries to the CUCM database and export the results in an easy to use format such as csv or pfd.  The query history will retain any succesful queries so you can simply click on the stored query and it will be re-run.

## Service Status

The service status tool will provide a report of all servers in a CUCM cluster and the status of each service, like Cisco Call Manager or TFTP.  This report is useful to export as a CSV and compare when doing upgrades or new implementations to ensure all services that should be started are started and services that should not be started, are not.

## Device Registration

The device registration tool will query the CUCM 'device' table for all devices and then obtain the registration status of each device.  The results can be saved as csv, pdf, etc.  The 'device' table contains more than just phones so you'll get reports on things like MOH servers and Transcoders.  This tool is useful to compare device registration status before and after an upgrade or cluster reboot.  Currently, you'll need to use diff or another tool of your choice to compare the before and after.  Eventually I'd like to add the feature to do this comparison via the program.

### Installation

- Clone this repository to your computer.
- Install Composer Dependencies
~~~
composer install
~~~
- Install npm Dependencies
~~~
npm install
~~~
- Install Bower Dependencies
~~~
bower install
~~~
- Configure your environment variables and database settings -  .env.example is provided as a template.
- Migrate the database
~~~
php artisan migrate
~~~
- Seed the database
~~~
php artisan db:seed
~~~

There will be a user created with an email of 'admin@admin.com' and password 'admin' which you can use to login for the first time.  After that, you can create your own local accounts and configure your CUCM cluster settings under Admin Settings->Clusters.
