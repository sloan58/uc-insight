## UC Insight

UC Insight is a collection of utilities that can be used with Cisco Unified Communications Manager.  It was built using Laravel 5.1.  If you'd like more information on integrating with Cisco CUCM API's using AXL and Laravel, please check out the Karma-Tek Blog @ http://karma-tek.com/blog.

While this application is a work in progress, it does have some useful features out of the box at this time.  Please read below for a current list of features and components.

## Features Overview
- User Management System
- Multi Cluster Support
- Autodialer System (requires a Twilio account)
- IP Phone Security Cert Eraser
- AXL Schema Versions 7.0 through 10.5
 
## SQL Query Tool

The SQL Query Tool allows you to make queries to the CUCM database and export the results in an easy to use format such as csv or pfd.  The query history will retain any succesful queries so you can simply click on the stored query and it will be re-run.

## Service Status

The service status tool will provide a report of all servers in a CUCM cluster and the status of each service, like Cisco Call Manager or TFTP.  This report is useful to export as a CSV and compare when doing upgrades or new implementations to ensure all services that should be started are started and services that should not be started, are not.

## Device Registration

The device registration tool will query the CUCM 'device' table for all devices and then obtain the registration status of each device.  The results can be saved as csv, pdf, etc.  The 'device' table contains more than just phones so you'll get reports on things like MOH servers and Transcoders.  This tool is useful to compare device registration status before and after an upgrade or cluster reboot.

## Autodialer System
The autodialer can place a call to any NANP number and speak a phrase of your choice.  It will take single requests and bulk requests (good for testing number porting!).  A Twilio account is required to use this feature.  After signing up for a Twilio account and phone number, update the application env's for Twilio SID, Token and From phone number.

## IP Phone Certificate Eraser
The Certificate Eraser feature will use the IP Phones built in Web API to press the necessary keys for erasing the ITL or CTL security certificate of a phone.  Currently there are a few models (mostly 79XX) supported, with more to be added as the key press sequences are recorded.  The sequences are stored in the app/helpers.php file starting on line 147.  Future updates will move the configs to the database with a web management interface.

If you have issues controlling the IP phones via the API you can check these settings:

* Make sure the 'Web Access' setting of the IP phone is enabled.
* Make sure the user account you're attempting to control the phone with is associated to that device.
* After checking the steps above, if you're having authentication issues you can send a request via web browser to confirm that CUCM knows you should be able to control the phone.  The web address you can use is:
	http://your.cucm.ip.address/ccmcip/authenticate.jsp?UserID=yourUserName&Password=yourPassword&devicename=SEP123456789123

	This request will return either 'Authorized' or 'Un-Authorized'.  If you are authorized, move on to the last step.

* If you've confirm the first two steps above and are still having authentiction issues with the IP Phone, browse to the main web interface for the IP Phone (does not require authentication) and select the 'Network' menu on the left hand side.  Within this page, confirm that the authentication URL your IP phone is using matches that of the one you used in step 3 above.  Make note of the URL address including hostname/ip and port number.

If you're using a custom authentication URL with a 3rd party app like InformaCast, you might need to troubleshoot a bit further but in a standard deployment these steps *should* get you through.

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
- Run gulp
~~~
gulp
~~~
- Configure your environment variables and database settings.  A template file named .env.example has been included.  Copy this file to .env and modify the settings as needed.
- Migrate the database
~~~
php artisan migrate
~~~
- Seed the database
~~~
php artisan db:seed
~~~

There will be a user created with an email of 'admin@admin.com' and password 'admin' which you can use to login for the first time.  After that, you can create your own local accounts and configure your CUCM cluster settings under Admin Settings->Clusters.
Updates are added frequently so if you pull a fresh copy of this repo, just run through the steps above once again.
