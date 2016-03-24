<div class="wrap">
<h2>Auto Approve Comments</h2>
<p>
    Comments from commenters/users listed below will always be auto approved.
</p>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <?php settings_fields( 'auto-approve-comments-group' ); ?>
    <?php do_settings_sections( 'auto-approve-comments-group' ); ?>
    <h2 class="nav-tab-wrapper">
        <a href="#commenters-list" class="nav-tab nav-tab-active">Commenters</a>
        <a href="#users-list" class="nav-tab">Users</a>
    </h2>


    <div id="aac-sections">
        <section id="aac-commenters-list">
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
            <input type="button" id="add_commenter" class="button button-small" value="Add commenter">
            <div class="aac-inputdiv"><textarea name="commenters_list" id="commenters_list" class="aac-textarea"><?php echo esc_attr( get_option('aac_commenters_list') ); ?></textarea></div>
        </section>

        <section id="aac-users-list">
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
            <input type="button" id="add_username" class="button button-small" value="Add username">
            <div class="aac-inputdiv"><textarea name="usernames_list" id="usernames_list" class="aac-textarea"><?php echo esc_attr( get_option('aac_usernames_list') ); ?></textarea></div>
        </section>
    </div>
    <div id="aac-submit"><?php submit_button(); ?></div>

</form>
</div>