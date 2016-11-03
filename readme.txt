=== Auto Approve Comments ===
Contributors: fedeandri
Tags: auto approve comments, auto-approve comments, commenting, comments, spam, comments approval, approve, approval, comment approved, comment moderator, user comments, moderate, moderation, moderator, anti-spam, comments spam, username, user, users, role, roles, email, url, admin
Requires at least: 3.8
Tested up to: 4.7
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically approve comments by commenter (email, name, url), user and role even if you set the comments to be manually approved to avoid spam.


== Description ==

Auto Approve Comments allows you to create a white list of commenters and to automatically approve their comments after checking their email/name/URL, username or user role (it works with custom roles).  
  
Useful when you set the comments to be manually approved to avoid spam, but you still want to immediately approve the comments made by your most trustful commenters/users.


**Usage**

1. Open Comments -> Auto Approve Comments
2. Configure the "Commenter list" and/or the "Users list" (see some examples below)
3. Save and you're done

From now on all the commenters listed in one of the lists below will have their comments immediately approved even if you set the comments to be manually approved.


**Commenters list - example**

Add only one commenter per line, these are all valid configurations:  
`
mark@verynicesite.com  
mark@verynicesite.com,Mark  
mark@verynicesite.com,www.verynicesite.com  
mark@verynicesite.com,www.verynicesite.com,Mark  
mark@verynicesite.com,Mark,www.verynicesite.com  
`

**Users list - example**

Add only one username per line:  
`
username1
username2
username3
username4
`

**Roles list - example**

Add only one role per line:  
`
role1
role2
role3
role4
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

