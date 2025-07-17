<?php
/*
Template Name: Employers Template
*/

global $wpdb;

$error_message = '';

if (isset($_POST['employer_login'])) {
    $email    = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);
    $table = $wpdb->prefix . 'employers';

    $employer = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table WHERE email = %s", $email),
        ARRAY_A
    );

    if ($employer && $password === $employer['password']) {
        // Login success → Redirect with ID
        wp_redirect(home_url('/employer-info/') . '?id=' . $employer['id']);
        exit;
    } else {
        $error_message = 'Invalid Email or Password. Please try again.';
    }
}
?>
<?php

get_header();

if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}
?>

<div class="gulf-container">
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title"><?php echo $breadcrumb; ?></div>
<?php } ?>



<!-- Login Form -->
<form method="post">
  <div class="employer-container employer">
    <a href="#" class="employer-link">Login Here :-</a>

    <?php if ($error_message): ?>
      <div style="color: red; margin-bottom: 10px;"><?php echo esc_html($error_message); ?></div>
    <?php endif; ?>

    <div class="employer-form-group">
      <label class="employer-label">Email ID</label>
      <input type="email" name="email" class="employer-input" required>
    </div>

    <div class="employer-form-group">
      <label class="employer-label">Password</label>
      <input type="password" name="password" class="employer-input" required>
    </div>

    <button type="submit" name="employer_login" class="employer-button">Login</button>

    <div class="employer-link-group">
      <a href="#">Forget Password</a>
    </div>

    <a href="/employer-request/" class="employer-registration">Click Here For New Employer Registration</a>
  </div>
</form>

<?php

get_footer();