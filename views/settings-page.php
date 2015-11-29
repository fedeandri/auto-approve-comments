<?php
    if($_POST['email_list']) {

        $email_list = strtolower(trim(preg_replace('/\s+/', "\n", $_POST['email_list'])));
        update_option('email_list', $email_list);
    }
?>
<div class="wrap">
<h2>Auto Approve Comments</h2>
<p>
    Auto approve comments based on user email<br>
    (comments from users listed below will be always auto approved)
</p>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <?php settings_fields( 'auto-approve-comments-group' ); ?>
    <?php do_settings_sections( 'auto-approve-comments-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <td>
                <textarea name="email_list" id="email_list"  cols="80" rows="10" class="all-options"><?php echo esc_attr( get_option('email_list') ); ?></textarea>
                <label for="email_list"><strong>Email list</strong></label><br><br>
                <span class="description">
                    <strong>Type one email address per line, for ex.</strong><br>
                    AutoApprovedEmail01@mysite.com<br>
                    AutoApprovedEmail02@mysite.com<br>
                    AutoApprovedEmail03@mysite.com<br>
                </span>
            </td>                         
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>