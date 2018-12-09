=== Auto Approve Comments ===
Contributors: fedeandri
Tags: auto approve, comments, moderation, anti-spam
Requires at least: 3.8
Tested up to: 5.0
Stable tag: 2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Auto approve comments by Commenter (email, name, url), User and Role.


== Description ==

Auto approve comments by Commenter (email, name, url), User and Role.

It has been tested and works well with Akismet and wpDiscuz.



**Usage**

1. Open Comments -> Auto Approve Comments
2. Go to Settings -> Discussion and check "Comment must be manually approved" 
3. Optionally install and activate Akismet (comments flagged as SPAM will never get auto approved) 
4. Configure your auto approval filters in "Commenters", "Users" and "Roles"
5. Save and you're done

From now on all the comments that match at least one of the configurations in "Commenters", "Users" or "Roles" will always be auto approved.


**Commenters - example**

Add one Commenter per line, follow the example below:  
`
tom@myface.com
tom@myface.com,Tom
tom@myface.com,www.myface.com
tom@myface.com,www.myface.com,Tom
tom@myface.com,Tom,www.myface.com
`

**Users - example**

Add one Username per line, follow the example below:  
`
steveknobs76
marissabuyer012
larrymage98
marktuckerberg2004
`

**Roles - example**

Add one Role per line, follow the example below:  
`
contributor
editor
yourcustomrole
subscriber
`

**Developers**

Official Github repository:  
https://github.com/fedeandri/auto-approve-comments  


== Installation ==

1. Unzip the plugin file auto-approve-comments.zip
2. Upload the unzipped folder "auto-approve-comments" to the `/wp-content/plugins/` directory of your WordPress blog/website
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Commenters list tab
2. Users list tab
3. Roles list tab

== Changelog ==

= 2.6 =
* Better Akismet integration (comments flagged as SPAM will never get auto approved)

= 2.5 =
* Redesign the settings page
* Test compatibility with Akismet
* Test compatibility with wpDiscuz
* Test compatibility with WordPress 5.0
* Add a test to check if jQuery is loaded

= 2.1 =
* Add auto approval by role
* Fix a bug that prevented to approve a commenter if only the email was configured
* Change the email validation pattern to allow a wider set of charatcters

= 2.0 =
* Save and refresh changes via AJAX
* Add nonce when saving changes
* Add admin UI success/error notice
* Add feedback when there are no suggestions
* Some cleaning and refactoring

= 1.5 =
* Add new tabbed interface
* Change Users ID list to Usernames list
* Add commenters suggestion field
* Add usernames suggestion field
* Auto remove duplicates

= 1.2 =
* Add better input validation

= 1.1 =
* Auto approve comments based on commenter's email, name, URL and User ID

= 1.0 =
* First version, auto approve comments based on commenter's email

