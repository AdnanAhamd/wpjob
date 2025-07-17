<?php
/*
Template Name: dashboard job category
*/

// Start session
if (!session_id()) {
    session_start();
}

// Check if user is allowed to access
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    wp_redirect(home_url('/entering-system'));
    exit;
}

// === 🟠 Form handling logic must come BEFORE get_header() ===

// 🗑️ Delete category
if (isset($_POST['delete_category'])) {
    $term_id = intval($_POST['term_id']);
    if ($term_id > 0) {
        $result = wp_delete_term($term_id, 'job-category');

        $redirect_url = add_query_arg(array(
            'job-category-notice' => '1',
        ), wp_get_referer());

        if (is_wp_error($result)) {
            $redirect_url = add_query_arg(array(
                'job-category-notice-type' => 'error',
                'job-category-message' => urlencode($result->get_error_message())
            ), $redirect_url);
        } else {
            $redirect_url = add_query_arg(array(
                'job-category-notice-type' => 'success',
                'job-category-message' => urlencode('Category deleted successfully!')
            ), $redirect_url);
        }

        wp_redirect($redirect_url);
        exit;
    }
}

// 📝 Update category
if (isset($_POST['update_category'])) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update_job_category')) {
        wp_die('Security check failed');
    }

    $term_id = intval($_POST['term_id']);
    $name = sanitize_text_field($_POST['termName']);

    if (!empty($name) && $term_id > 0) {
        $result = wp_update_term(
            $term_id,
            'job-category',
            array(
                'name' => $name,
                'slug' => sanitize_title($name),
            )
        );
    }
}

// ➕ Save new category
if (isset($_POST['save_category'])) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_job_category')) {
        wp_die('Security check failed');
    }

    $name = sanitize_text_field($_POST['termName']);

    if (!empty($name)) {
        $result = wp_insert_term(
            $name,
            'job-category',
            array(
                'description' => '',
                'slug' => sanitize_title($name),
            )
        );
    }
}

// ✅ NOW it's safe to call get_header()


// Prepare search argument
$search = sanitize_text_field($_POST['search'] ?? '');
$args = [
    'taxonomy'   => 'job-category', // consistent taxonomy slug
    'hide_empty' => false,
];

if (!empty($search)) {
    $args['name__like'] = $search;
}

$terms = get_terms($args);
get_header('dashboard');
?>


<div class="container-job-category">
    <div class="header-job-category">
        <h2><?php echo isset($_POST['edit_mode']) ? 'Edit' : 'Add'; ?> Job Category</h2>
    </div>

    <form method="POST">
        <?php 
        if (isset($_POST['edit_mode'])) {
            wp_nonce_field('update_job_category');
        } else {
            wp_nonce_field('save_job_category');
        }
        ?>
        
        <div class="form-section-job-category">
            <label for="name-job-category">Name</label>
            <input type="text" id="name-job-category" class="input-job-category" name="termName" value="<?php echo esc_attr($_POST['edit_name'] ?? ''); ?>">

            <?php if (isset($_POST['edit_mode'])): ?>
                <input type="hidden" name="term_id" value="<?php echo intval($_POST['term_id']); ?>">
                <button type="submit" name="update_category" class="btn-save-job-category">Update</button>
            <?php else: ?>
                <button type="submit" name="save_category" class="btn-save-job-category">Save</button>
            <?php endif; ?>

            <button type="button" onclick="window.location.href=window.location.href" class="btn-reset-job-category">Reset</button>
        </div>
    </form>

    <hr class="divider-job-category">

    <form method="POST">
        <div class="search-section-job-category">
            <label for="search-job-category">Search name:</label>
            <input type="text" id="search-job-category" class="input-job-category" name="search" value="<?php echo esc_attr($search); ?>">
            <button class="btn-search-job-category">Search</button>
            <button type="button" onclick="window.location.href=window.location.href" class="btn-reset-job-category">Clear Search</button>
        </div>
    </form>

    <div class="divider-job-category">
        <h2>All Job Categories</h2>

        <table class="styled-job-category-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($terms) && !is_wp_error($terms)) :
                $count = 1;
                foreach ($terms as $term) : ?>
                    <tr>
                        <td><span class="arrow-icon">▶</span> <?php echo $count++; ?></td>
                        <td><?php echo esc_html($term->name); ?></td>
                        <td class="action-buttons">
                            <!-- Edit form -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="term_id" value="<?php echo intval($term->term_id); ?>">
                                <input type="hidden" name="edit_name" value="<?php echo esc_attr($term->name); ?>">
                                <button type="submit" name="edit_mode" class="icon-btn" title="Edit">
                                   <img src="<?php echo get_template_directory_uri(); ?>/images/edit-icon.png" alt="Edit" width="20" height="20"><!-- Delete Icon -->
                                </button>
                            </form>
                            <!-- Delete form -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="term_id" value="<?php echo intval($term->term_id); ?>">
                                <button type="submit" name="delete_category" class="icon-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                   <img src="<?php echo get_template_directory_uri(); ?>/images/delete-icon.png" alt="Delete" width="20" height="20">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
            else : ?>
                <tr><td colspan="3">No job categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<?php get_footer(); ?>