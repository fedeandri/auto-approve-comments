=== Auto Approve Comments ===
Contributors: fedeandri
Tags: auto approve comments, auto-approve comments, commenting, comments, spam, comments approval, approve, approval, comment approved, comment moderator, user comments, moderate, moderation, moderator, anti-spam, comments spam
Requires at least: 3.8
Tested up to: 4.3.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically approve the comments of your most trustful readers.

== Description ==

Auto Approve Comments allows you to create a white list of commenters and to automatically approve their comments after checking their email, name, URL or User ID.  
  
Useful when you set your comments to be manually approved to avoid spam, but you still want to immediately approve the comments made by your most trustful readers.


**Usage**

1. Open Comments -> Auto Approve Comments
2. Configure the "Commenter list" or the "User ID list" (see some examples below)
3. Save and you're done

From now on all the commenters listed in one of the lists above will have his/her comment immediately approved even if you set your comments to be manually approved.


**Commenter list - example**

Add only one commenter per line, these are all valid configurations:  
`
user@mysite.com  
user@mysite.com,John  
user@mysite.com,www.mysite.com  
user@mysite.com,www.mysite.com,John  
user@mysite.com,John,www.mysite.com  
`

**User ID list - example**

Add only one User ID per line:  
`
1  
23  
4  
`

== Installation ==

1. Unzip the plugin file auto-approve-comments.zip
2. Upload the unzipped folder "auto-approve-comments" to the `/wp-content/plugins/` directory of your WordPress blog/website
3. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.2 =
* Add better input validation

= 1.1 =
* Auto approve comments based on commenter's email, name, URL and User ID

= 1.0 =
* First version, auto approve comments based on commenter's email

