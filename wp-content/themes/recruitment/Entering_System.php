<?php
/*
Template Name: Entering System
*/

get_header();  // Loads header.php or header-dashboard.php
?>

<?php
if (isset($_POST['login_employer'])) {
    global $wpdb;

    $user_name = trim(sanitize_text_field($_POST['user_name']));
    $password  = trim(sanitize_text_field($_POST['password']));
    $table     = 'wp_entering_system';

    $user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table WHERE user_name = %s AND user_password = %s",
            $user_name,
            $password
        )
    );

    if ($user) {
    $_SESSION['user_id'] = $user->id;  // or $user->ID if your column is uppercase
    $_SESSION['enter_system'] = 'entering_system';

    echo '<div style="color:green;">Login successful! Redirecting...</div>';
    echo '<script>setTimeout(function() { window.location.href = "' . home_url('/admin-dashboard') . '"; }, 1000);</script>';
    exit;
} else {
        echo '<div style="color:red;">Invalid username or password.</div>';
    }
}
?>

<form method="post">
  <div class="employer-container employerRequestWrapp">
    <div class="employer-link">Entering System</div>

    <div class="employer-form-group">
      <label class="company-label">User Name</label>
      <input type="text" class="employer-input" name="user_name" required>
    </div>
    <div class="employer-form-group">
      <label class="company-label">Password</label>
      <input type="text" class="employer-input" name="password" required>
    </div>
    <button type="submit" name="login_employer" class="employer-button">Login</button>
  </div>
</form>

<style>
  .employerRequestWrapp {
    padding: 60px;
    max-width: 600px;
    margin: auto;
  }
  .employer-form-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
  }
</style>

<?php get_footer(); ?>
