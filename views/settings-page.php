<div class="wrap">
<h2>Auto Approve Comments</h2>
<p>
    Comments that match Commenters, Users and Roles listed below will always be auto approved.<br>
    To effectively prevent SPAM remember to check "Comment must be manually approved" in Settings -> <a href="<?php echo network_admin_url( 'options-discussion.php' ); ?>">Discussion</a>
</p>

<div id="aac-notice-success">
    <p class="aac-floatleft">All your changes have been successfully saved</p>
    <button id="aac-notice-success-dismiss" type="button"class="dashicons dashicons-dismiss"><span class="screen-reader-text">Close</span></button>
</div>

<div id="aac-notice-error">
    <p class="aac-floatleft">It was not possible to save your changes, please check your internet connection and try again</p>
    <button id="aac-notice-error-dismiss" type="button"class="dashicons dashicons-dismiss"><span class="screen-reader-text">Close</span></button>
</div>

<div id="aac-main-form">
    <?php settings_fields( 'auto-approve-comments-group' ); ?>
    <?php do_settings_sections( 'auto-approve-comments-group' ); ?>
    <h2 class="nav-tab-wrapper">
        <a href="#aac-commenters-list" class="nav-tab nav-tab-active">Commenters</a>
        <a href="#aac-users-list" class="nav-tab">Users</a>
        <a href="#aac-roles-list" class="nav-tab">Roles</a>
    </h2>

    <div id="aac-sections">
        <section id="aac-commenters-section">
            <div class="aac-helpdiv">
                <strong>Type at least the email address of each commenter that you want to auto approve.</strong><br>
                For a more reliable filter, add the name and/or the url separated by a comma.<br>
                Add only one commenter per line, these are all valid configurations:<br>
                <code>
                mark@verynicesite.com<br>
                mark@verynicesite.com,Mark<br>
                mark@verynicesite.com,www.verynicesite.com<br>
                mark@verynicesite.com,www.verynicesite.com,Mark<br>
                mark@verynicesite.com,Mark,www.verynicesite.com<br>
                </code>
            </div>

            <input id="aac-commenters-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-commenter" class="button button-small" value="Add commenter">
            <div class="aac-inputdiv"><textarea name="aac-commenters-list" id="aac-commenters-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_commenters_list') ); ?></textarea></div>
        </section>

        <section id="aac-users-section">
           <div class="aac-helpdiv">
                <strong>Type the username of each user that you want to auto approve.</strong><br>
                Add only one username per line, like this:<br>
                <code>
                    username1<br>
                    username2<br>
                    username3<br>
                    username4<br>
                </code>
            </div>

            <input id="aac-usernames-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-username" class="button button-small" value="Add username">
            <div class="aac-inputdiv"><textarea name="aac-usernames-list" id="aac-usernames-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_usernames_list') ); ?></textarea></div>
        </section>
        
        <section id="aac-roles-section">
           <div class="aac-helpdiv">
                <strong>Type the role that you want to auto approve.</strong><br>
                Add only one role per line, like this:<br>
                <code>
                    role1<br>
                    role2<br>
                    role3<br>
                    role4<br>
                </code>
            </div>

            <input id="aac-roles-autocomplete" type="text" class="ui-autocomplete-input" autocomplete="off">
            <input type="button" id="aac-add-role" class="button button-small" value="Add role">
            <div class="aac-inputdiv"><textarea name="aac-roles-list" id="aac-roles-list" class="aac-textarea"><?php echo esc_attr( get_option('aac_roles_list') ); ?></textarea></div>
        </section>
        
    </div>

    <input type="hidden" id="aac-save-configuration-nonce" value="<?php echo wp_create_nonce('aac-save-configuration-nonce') ?>">
    <input id="aac-submit" class="button-primary" type="button" value="Save Changes">

</div>
</div>