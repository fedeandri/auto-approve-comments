=== Auto Approve Comments ===
Contributors: fedeandri
Tags: auto approve comments, auto-approve comments, commenting, comments, spam, comments approval, approve, approval, comment approved, comment moderator, user comments, moderate, moderation, moderator, anti-spam, comments spam
Requires at least: 3.8
Tested up to: 4.4.2
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically approve the comments of your most trustful readers.


== Description ==

Auto Approve Comments allows you to create a white list of commenters and to automatically approve their comments after checking their email/name/URL or username.  
  
Useful when you set your comments to be manually approved to avoid spam, but you still want to immediately approve the comments made by your most trustful readers.


**Usage**

1. Open Comments -> Auto Approve Comments
2. Configure the "Commenter list" or the "Users list" (see some examples below)
3. Save and you're done

From now on all the commenters listed in one of the lists above will have their comments immediately approved even if you set the comments to be manually approved.


**Commenter list - example**

Add only one commenter per line, these are all valid configurations:  
`
user@mysite.com  
user@mysite.com, Mark  
user@mysite.com, www.mysite.com  
user@mysite.com, www.mysite.com, Mark  
user@mysite.com, Mark, www.mysite.com  
`

**Users list - example**

Add only one User ID per line:  
`
username1
username2
username3
username4
`

== Installation ==

1. Unzip the plugin file auto-approve-comments.zip
2. Upload the unzipped folder "auto-approve-comments" to the `/wp-content/plugins/` directory of your WordPress blog/website
3. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

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

