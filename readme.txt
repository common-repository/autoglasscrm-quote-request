=== AutoGlassCRM Quote Request===
Donate link: https://autoglasscrm.com/
Tags: AutoGlassCRM, Quote
Requires at least: 4.7
Requires PHP: 7.0
Tested up to: 5.6.1
Stable tag: 4.3
Contributors: autoglasscrm
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
AutoGlassCRM Quote plugin sends quote.


== Installation ==
Upload the AutoGlassCRM Quote plugin and activate it, and then enter user username and password in AGCRM page in wordpress admin dashboard.

Step 1: Create a new page and name the page quote request and publish the page.
Step 2: Go to Appearance->Widgets, under available widgets you will see Request Quote.
Before going to step 3 make sure you see Widgets for Shortcodes on the right side of this page. If you don\'t see Widgets for Shortcodes you should download the plugin amr shortcode any widget. 
Install and activate that plugin and do Step 2 again.
Step 3: Drag Request Quote widget to widget for shortcodes. Once added, you will click on Request Quote and you will see the shortcode. Copy and paste short code to the page you created in Step 1.  Publish the page and then try to go to your website and go to that page. You should see the quote tool.

== Frequently Asked Questions ==
Please go to AGCRM admin page before asking any questions.

== Screenshots ==
1. Create a new page and name the page quote request and publish the page.
2. Go to Appearance->Widgets, under available widgets you will see Request Quote.
3. Drag Request quote widget to widget for shortcodes.

== Changelog ==

= 1.0 (19th Feb, 2021) =

== Upgrade Notice ==
Launch of the plugin

== 3rd Party service ==
* https://demo.autoglasscrm.com/file/save
  We are using this our own service to save images to the 3rd storage
  
* https://pwhqni6edi.execute-api.us-east-1.amazonaws.com/prod
  Service's terms of use: https://aws.amazon.com/terms/?nc1=f_pr
  We have developed a model that we trained with images to detect features on a windshield of a vehicle. After the customer attaches images and submits
  We will detect by shape by running the images thru amazon aws sagemaker.

* https://demo.autoglasscrm.com/login/appauth
  We are using this our own service to validate the user token.