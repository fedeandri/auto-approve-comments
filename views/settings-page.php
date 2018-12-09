<div class="wrap">
<h2>Auto Approve Comments</h2>
<div id="aac-notice-success">
    <p class="aac-floatleft">All your changes have been successfully saved</p>
    <button id="aac-notice-success-dismiss" type="button"class="dashicons dashicons-dismiss"><span class="screen-reader-text">Close</span></button>
</div>

<div id="aac-notice-error">
    <p class="aac-floatleft">It was not possible to save your changes, please check your internet connection and try again</p>
    <button id="aac-notice-error-dismiss" type="button"class="dashicons dashicons-dismiss"><span class="screen-reader-text">Close</span></button>
</div>

<div id="aac-notice-error-jquery">
    <p class="aac-floatleft">Unable to load jQuery from WordPress core, this Setting page might not work as expected.<br>Please check your WordPress installation and if you're unable to fix it ask for <a href="https://wordpress.org/support/plugin/auto-approve-comments" target="_blank">support</a>.</p>
    <button id="aac-notice-error-dismiss-jquery" onclick="document.getElementById('aac-notice-error-jquery').style.display = 'none';" type="button"class="dashicons dashicons-dismiss"><span class="screen-reader-text">Close</span></button>
</div>

<div id="aac-main-form">
    <?php settings_fields( 'auto-approve-comments-group' ); ?>
    <?php do_settings_sections( 'auto-approve-comments-group' ); ?>
    <h2 class="nav-tab-wrapper">
        <a href="#aac-general-info" class="nav-tab nav-tab-active">General info</a>
        <a href="#aac-commenters-list" class="nav-tab">Commenters</a>
        <a href="#aac-users-list" class="nav-tab">Users</a>
        <a href="#aac-roles-list" class="nav-tab">Roles</a>
    </h2>

    <div id="aac-sections">
        <section id="aac-general-section">
            <div class="aac-helpdiv">
                <div>
                <strong>To effectively prevent SPAM while automatically approving comments:</strong>
                <br>- go to Settings -> <a href="<?php echo network_admin_url( 'options-discussion.php' ); ?>">Discussion</a> and check "Comment must be manually approved"
                <br>- optionally install and activate <a href="<?php echo network_admin_url( 'plugin-install.php?s=akismet&tab=search&type=term' ); ?>">Akismet</a> (<strong>comments flagged as SPAM will never get auto approved</strong>)
                <br>- configure your auto approval filters in "Commenters", "Users" and "Roles"
                </div>
                <br>
                <div>
                <strong>Auto Approve Comments has been tested and works well with:</strong>
                <br>- <a href="<?php echo network_admin_url( 'plugin-install.php?s=wpdiscuz&tab=search&type=term' ); ?>">wpDiscuz</a> comment extension plugin
                <br>- <a href="<?php echo network_admin_url( 'plugin-install.php?s=akismet&tab=search&type=term' ); ?>">Akismet</a> anti-spam plugin
                <br>
                <br>Do you find this plugin useful? <a href="https://wordpress.org/support/plugin/auto-approve-comments/reviews/" target="_blank">Show me some &hearts; with a review</a>
                <br>Bugs, feedback? <a href="https://wordpress.org/support/plugin/auto-approve-comments" target="_blank">Post on the support forum</a>
                </div>
            </div>
        </section>

        <section id="aac-commenters-section">
            <div class="aac-helpdiv">
                <strong>Commenters auto approval setup:</strong>
                <br>- Type at least the email address of each commenter that you want to auto approve
                <br>- For a more reliable filter add the name and/or the url separated by a comma
                <br>- Add only one commenter per line, follow the example below
                <br>
                <code>
                tom@myface.com<br>
                tom@myface.com,Tom<br>
                tom@myface.com,www.myface.com<br>
                tom@myface.com,www.myface.com,Tom<br>
                tom@myface.com,Tom,www.myface.com<br>
                </code>
            </div>

            <input id="aac-commenters-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-commenter" class="button button-small" value="Add commenter">
            <div class="aac-inputdiv"><textarea name="aac-commenters-list" id="aac-commenters-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_commenters_list') ); ?></textarea></div>
            <i>- Comments that match the Commenters above will always be auto approved -</i>
        </section>

        <section id="aac-users-section">
           <div class="aac-helpdiv">
                <strong>Users auto approval setup:</strong>
                <br>- Type the username of each user that you want to auto approve
                <br>- Add only one username per line, follow the example below
                <br>
                <code>
                    steveknobs76<br>
                    marissabuyer012<br>
                    larrymage98<br>
                    marktuckerberg2004<br>
                </code>
            </div>
            <input id="aac-usernames-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-username" class="button button-small" value="Add username">
            <div class="aac-inputdiv"><textarea name="aac-usernames-list" id="aac-usernames-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_usernames_list') ); ?></textarea></div>
            <i>- Comments that match the Usernames above will always be auto approved -</i>
        </section>
        
        <section id="aac-roles-section">
           <div class="aac-helpdiv">
                <strong>Roles auto approval setup:</strong>
                <br>- Type the role that you want to auto approve
                <br>- Add only one role per line, follow the example below
                <br>
                <code>
                    contributor<br>
                    editor<br>
                    yourcustomrole<br>
                    subscriber<br>
                </code>
            </div>

            <input id="aac-roles-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-role" class="button button-small" value="Add role">
            <div class="aac-inputdiv"><textarea name="aac-roles-list" id="aac-roles-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_roles_list') ); ?></textarea></div>
            <i>- Comments that match the Roles above will always be auto approved -</i>
        </section>
        
    </div>

    <input type="hidden" id="aac-save-configuration-nonce" value="<?php echo wp_create_nonce('aac-save-configuration-nonce') ?>">
    <input id="aac-submit" class="button-primary" type="button" value="Save Changes">

</div>
</div>