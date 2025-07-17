<?php
/*
Template Name: Employer Signup Template
*/

get_header();

if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}
?>

<div class="gulf-container">
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title"><?php echo $breadcrumb; ?></div>
<?php } ?>


<?php
if (isset($_POST['save_employer'])) {
    global $wpdb;

    $employer_name   = sanitize_text_field($_POST['Employer_name']);
    $company_name   = sanitize_text_field($_POST['company_name']);
    $email          = sanitize_email($_POST['email']);
    $contact_number = sanitize_text_field($_POST['contact_number']);
    $password       = sanitize_text_field($_POST['password']);  // Password hashed securely

    $table = $wpdb->prefix . 'employers';

    $inserted = $wpdb->insert(
        $table,
        [
            'employer_name' => $employer_name,
            'company_name'   => $company_name,
            'email'          => $email,
            'contact_number' => $contact_number,
            'password'       => $password,
        ]
    );

    if ($inserted) {
        echo '<div style="color:green;">Employer Registered Successfully!</div>';
    } else {
        echo '<div style="color:red;">Failed to Register Employer!</div>';
    }
}
?>


<form method="post">
  <div class="employer-container employerRequestWrapp">
    <div class="employer-link">Register Here :-</div>

    <div class="employer-form-group">
      <label class="company-label">Employer Name</label>
      <input type="text" class="employer-input" name="Employer_name" required>
    </div>

    <div class="employer-form-group">
      <label class="company-label">Company Name</label>
      <input type="text" class="employer-input" name="company_name" required>
    </div>

    <div class="employer-form-group">
      <label class="employer-label">Email ID</label>
      <input type="email" class="employer-input" name="email" required>
    </div>

    <div class="employer-form-group">
      <label class="number-label">Contact Number</label>
      <input type="text" class="employer-input" name="contact_number" required>
    </div>

    <div class="employer-form-group">
      <label class="password-label">Password</label>
      <input type="password" class="employer-input" name="password" required>
    </div>

    <button type="submit" name="save_employer" class="employer-button">Save</button>
  </div>
</form>


<style type="text/css">
  .employerRequestWrapp{
    padding: 60px;

  }
  .employer-form-group{
    display: flex;
    justify-content: space-between;
  }
</style>
<?php

get_footer();