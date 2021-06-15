=== Auto Approve Comments ===
Contributors: fedeandri
Tags: auto approve, comments, moderation, anti-spam
Requires at least: 3.8
Tested up to: 5.8
Stable tag: 2.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Auto approve comments by Commenter (email, name, url), User and Role (Akismet and wpDiscuz compatible)


== Description ==

Auto approve comments by Commenter (email, name, url), User and Role (Akismet and wpDiscuz compatible).



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
jeffmezos012
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

1. Commenters tab
2. Users tab
3. Roles tab

== Changelog ==

= 2.8 =
* Adds a notice when trying to save while current nonce is expired
* Adds the settings page link on plugins page
* Better script loading chain
* Minor fixes and code refactoring

= 2.7 =
* Updates the test to check if jQuery is loaded
* Fixes a minor CSS issue

= 2.6 =
* Better Akismet integration (comments flagged as SPAM will never get auto approved)

= 2.5 =
* Redesigns the settings page for better usability
* Guarantees compatibility with Akismet
* Guarantees compatibility with wpDiscuz
* Adds a test to check if jQuery is loaded

= 2.1 =
* Adds auto approval by role
* Fixes a bug that prevented to approve a commenter if only the email was configured
* Updates the email validation pattern to allow a wider set of charatcters

= 2.0 =
* Updates "save and refresh" via AJAX
* Adds nonce when saving changes
* Adds admin UI success/error notice
* Adds feedback when there are no suggestions
* Some cleaning and refactoring

= 1.5 =
* Adds new tabbed interface
* Changes Users ID list to Usernames list
* Adds commenters suggestion field
* Adds usernames suggestion field
* Auto remove duplicates

= 1.2 =
* Adds better input validation

= 1.1 =
* Auto approve comments based on commenter's email, name, URL and User ID

= 1.0 =
* First version, auto approve comments based on commenter's email

