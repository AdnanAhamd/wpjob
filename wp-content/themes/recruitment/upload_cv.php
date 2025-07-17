<?php
/*
Template Name: Upload CV Template
*/



if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}


if (isset($_POST['employer_login'])) {
    global $wpdb;
    $email = sanitize_email($_POST['email']);
    $mobile = sanitize_text_field($_POST['mobile']);
    $password = sanitize_text_field($_POST['password']);
    $table = $wpdb->prefix . 'job_applications';



    // Find matching record
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE email = %s", $email));

    if ($user) {
        // Check Mobile No. or Password
        $mobile_match = ($mobile && $user->contact_no === $mobile);
        $password_match = ($password && $user->password === $password);

        if ($mobile_match || $password_match) {
            // ✅ Redirect with user ID
            wp_redirect(home_url() . '/user-information?user_id=' . $user->id);
            exit;
        } else {
            echo '<p style="color:red;">Invalid Mobile No. or Password!</p>';
        }
    } else {
        echo '<p style="color:red;">No account found with this Email ID!</p>';
    }
}
get_header();

?>

<div class="gulf-container">
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title"><?php echo $breadcrumb; ?></div>
<?php } ?>

    <form method="post">
      <div class="employer-container employer">
        <a href="#" class="employer-link">Login Here :-</a>

        <div class="employer-form-group">
          <label class="employer-label">Email ID</label>
          <input type="email" name="email" class="employer-input" required>
        </div>

        <div class="employer-form-group">
          <label class="employer-label">Mobile No.</label>
          <input type="text" name="mobile" class="employer-input">
        </div>

        <p style="text-align:center;">OR</p>

        <div class="employer-form-group">
          <label class="employer-label">Password</label>
          <input type="password" name="password" class="employer-input">
        </div>

        <button type="submit" name="employer_login" class="employer-button">Login</button>

        <div class="employer-link-group">
          <a href="#">Forget Password</a>
        </div>

        <a href="<?php echo site_url(); ?>/register-user/" class="employer-registration">Click Here For New Employer Registration</a>
      </div>
    </form>
</div>

<?php

get_footer();