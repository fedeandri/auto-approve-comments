<div class="wrap">
<h2>Auto Approve Comments</h2>
<p>
    Auto approve comments based on the configuration below<br>
    (comments from commenters/users listed below will be always auto approved)
</p>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <?php settings_fields( 'auto-approve-comments-group' ); ?>
    <?php do_settings_sections( 'auto-approve-comments-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <td>
                <label for="commenters_list"><strong>Commenters list</strong></label><br>
                <div style="float:left; padding-right: 20px;"><textarea name="commenters_list" id="commenters_list" cols="40" rows="10"><?php echo esc_attr( get_option('commenters_list') ); ?></textarea></div>
                <p>
                    <strong>For each commenter that you want to auto-approve type at least the email address.</strong><br>
                    But if you want to perform a more secure check, add the name and/or the url separated by a comma.<br>
                    Add only one commenter per line. These are all valid configurations:<br>
                    user@mysite.com<br>
                    user@mysite.com,John<br>
                    user@mysite.com,www.mysite.com<br>
                    user@mysite.com,www.mysite.com,John<br>
                    user@mysite.com,John,www.mysite.com<br>
                </p>
            </td>                         
        </tr>
        <tr valign="top">
            <td>
                <label for="userid_list"><strong>User ID list</strong></label><br>
                <div style="float:left; padding-right: 20px;"><textarea name="userid_list" id="userid_list" cols="10" rows="10"><?php echo esc_attr( get_option('userid_list') ); ?></textarea></div>
                <p>
                    <strong>Type the User ID of each commenter that you want to auto-approve.</strong><br>
                    Be sure to write only one User ID per line, like this:<br>
                    1<br>
                    23<br>
                    4<br>
                </p>
            </td>                         
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>