<?php
/*
Template Name: dashboard-job-add
*/

// ✅ Optional session-based login check
if (!session_id()) {
    session_start();
}
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    wp_redirect(home_url('/entering-system'));
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_job') {
    $title     = sanitize_text_field($_POST['title']);
    $city      = sanitize_text_field($_POST['city']);
    $country   = sanitize_text_field($_POST['country']);
    $details   = sanitize_textarea_field($_POST['details']);
    $category  = intval($_POST['job_category']);
    $is_gulf   = isset($_POST['gulfJob']) ? 1 : 0;

    // Create job post array
    $new_post = [
        'post_title'   => $title,
        'post_type'    => 'job', // Ensure 'job' post type exists
        'post_status'  => 'publish', // Change to 'draft' if needed
        'post_content' => $details,
    ];

    $post_id = wp_insert_post($new_post);

    if ($post_id && !is_wp_error($post_id)) {
        // Save custom fields
        update_field('city', $city, $post_id);
        update_field('country', $country, $post_id);
        update_field('is_gulf_job', $is_gulf, $post_id);

        // Assign taxonomy
        if ($category) {
            wp_set_post_terms($post_id, [$category], 'job-category');
        }

        // Debug: Log post ID
        error_log('New job post created with ID: ' . $post_id);

        // Redirect after success
        $redirect_url = home_url("/add-job");

        // Optional check: Does the URL exist?
        if (!url_to_postid($redirect_url)) {
            error_log('Redirect URL does not exist: ' . $redirect_url);
            // fallback to home page
            $redirect_url = home_url();
        }

        wp_redirect($redirect_url);
        exit;
    } else {
        // Log error if post creation failed
        error_log('Failed to create job post: ' . (is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error'));
    }
}

get_header('dashboard');
?>

<div class="container add-user">
  <div class="header add-user">Add New Job</div>
  <button class="back-button add-user" onclick="history.back()">&lt; BACK</button>

  <form class="add-user" method="post">
    <label for="country" class="add-user">Country</label>
    <select id="country" name="country" class="add-user">
      <option value="Afghanistan">Afghanistan</option>
      <option value="Jordan">Jordan</option>
      <option value="United Arab Emirates">United Arab Emirates</option>
      <option value="Saudi Arabia">Saudi Arabia</option>
      <option value="Qatar">Qatar</option>
      <option value="Kuwait">Kuwait</option>
    </select>

    <label for="city" class="add-user">City</label>
    <input type="text" id="city" name="city" class="add-user" placeholder="" required>

    <label for="job_category" class="add-user">Category</label>
    <select id="job_category" name="job_category" class="add-user" required>
      <option value="">Select a category</option>
      <?php
      $terms = get_terms([
          'taxonomy' => 'job-category',
          'hide_empty' => false,
      ]);
      if (!is_wp_error($terms) && !empty($terms)) {
          foreach ($terms as $term) {
              echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
          }
      }
      ?>
    </select>

    <label for="title" class="add-user">Title</label>
    <input type="text" id="title" name="title" class="add-user" placeholder="" required>

    <label for="details" class="add-user">Details</label>
    <textarea id="details" name="details" class="add-user" required></textarea>

    <div class="checkbox-container add-user">
      <input type="checkbox" id="gulfJob" name="gulfJob" class="add-user">
      <label for="gulfJob" class="add-user">Is this a Gulf job?</label>
    </div>

    <input type="hidden" name="action" value="add_new_job">

    <div class="buttons add-user">
      <button type="submit" class="add-user">Save</button>
      <button type="button" class="cancel add-user" onclick="history.back()">Cancel</button>
    </div>
  </form>
</div>

<?php get_footer(); ?>
