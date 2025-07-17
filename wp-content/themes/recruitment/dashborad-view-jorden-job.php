<?php
/*
Template Name: dashboard-job-view-jorden
*/

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Handle single delete
if (isset($_GET['delete_single']) && current_user_can('delete_posts')) {
    $delete_id = intval($_GET['delete_single']);
    if (get_post_type($delete_id) === 'job') {
        wp_delete_post($delete_id, true);
        wp_redirect(add_query_arg('success', '1', remove_query_arg('delete_single')));
        exit;
    }
}

// Handle bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected']) && current_user_can('delete_posts')) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bulk_delete_jobs')) {
        wp_die('Security check failed');
    }
    
    if (!empty($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
        foreach ($_POST['delete_ids'] as $delete_id) {
            $delete_id = intval($delete_id);
            if (get_post_type($delete_id) === 'job') {
                wp_delete_post($delete_id, true);
            }
        }
        wp_redirect(add_query_arg('success', '1', remove_query_arg(['delete_selected', 'delete_ids'])));
        exit;
    }
}

// Get search input
 $title_keyword = isset($_GET['job_title']) ? sanitize_text_field($_GET['job_title']) : '';
$date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
$date_to   = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
// Only build query if job title is searched
$jobs_query = null;
$jobs_found = false;

if (!empty($title_keyword)) {
    $args = [
    'post_type'      => 'job',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [
        [
            'key'     => 'country',
            'value'   => 'Jordan',
            'compare' => '=',
        ],
    ],
];

// Add date filter
if (!empty($date_from) || !empty($date_to)) {
    $args['date_query'] = [[
        'after'     => $date_from ?: null,
        'before'    => $date_to ?: null,
        'inclusive' => true,
    ]];
}

    $jobs_query = new WP_Query($args);
    $jobs_found = $jobs_query->have_posts();
    // echo $total_jobs = $jobs_query->found_posts; die();
}


get_header('dashboard');
?>

<div class="main-container-job-view-jordan">
    <div class="header-job-view-jordan">View Jobs</div>
    <div class="sub-header-job-view-jordan">Search/View Jobs in Jordan</div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message-job-view-jordan">✅ Success: Selected job(s) deleted.</div>
    <?php endif; ?>

    <div class="search-filters-job-view-jordan">
        <label>Search By:</label>
        <form class="search-fields-job-view-jordan" method="get">
            <input type="date" name="date_from" value="<?php echo esc_attr($_GET['date_from'] ?? ''); ?>">
            <input type="date" name="date_to" value="<?php echo esc_attr($_GET['date_to'] ?? ''); ?>">
            <input type="text" name="job_title" placeholder="Job Title" value="<?php echo esc_attr($title_keyword); ?>">
            <button type="submit">Search</button>
            <button type="button" onclick="clearJobSearch()" class="btn-clear-job-view-jordan">Clear</button>

        </form>
    </div>

    <?php if (empty($title_keyword)) : ?>
        <p style="margin-top: 20px;">🔍 Please enter a job title above to search for jobs in Jordan.</p>
    <?php endif; ?>

    <?php if (!empty($title_keyword)) : ?>
        <label style="color: green; font-weight: bold;">Search Result:</label>

        <form method="post" action="">
            <?php wp_nonce_field('bulk_delete_jobs'); ?>
            <table class="results-table-job-view-jordan">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Sl.No.</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Publish Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jobs_found) : $i = 1; ?>
                        <?php while ($jobs_query->have_posts()) : $jobs_query->the_post(); ?>
                            <tr>
                                <td><input type="checkbox" name="delete_ids[]" value="<?php echo get_the_ID(); ?>"></td>
                                <td><?php echo $i++; ?></td>
                                <td><?php the_title(); ?></td>
                                <td><?php echo wp_trim_words(get_the_content(), 10); ?></td>
                                <td><?php echo get_the_date('d/m/Y'); ?></td>
                                <td><?php echo get_post_meta(get_the_ID(), 'status', true) ?: 'Pending'; ?></td>
                                <td>
                                    <a href="<?php echo add_query_arg('delete_single', get_the_ID()); ?>" 
                                       onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <tr><td colspan="7">No jobs found in Jordan matching your search.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($jobs_found) : ?>
                <button type="submit" name="delete_selected" class="btn-delete-multiple-job-view-jordan"
                        onclick="return confirm('Delete selected jobs?')">Delete Selected</button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAllBox = document.getElementById('checkAll');
    if (checkAllBox) {
        checkAllBox.addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>

<style>
.main-container-job-view-jordan {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header-job-view-jordan {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.sub-header-job-view-jordan {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.success-message-job-view-jordan {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.search-filters-job-view-jordan {
    margin-bottom: 20px;
}

.search-fields-job-view-jordan {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.search-fields-job-view-jordan input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    flex: 1;
}

.search-fields-job-view-jordan button {
    padding: 8px 16px;
    background: #007cba;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.results-table-job-view-jordan {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.results-table-job-view-jordan th,
.results-table-job-view-jordan td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.results-table-job-view-jordan th {
    background: #f5f5f5;
    font-weight: bold;
}

.btn-delete-multiple-job-view-jordan {
    margin-top: 20px;
    padding: 10px 20px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
</style>

<?php get_footer(); ?>
<script>
function clearJobSearch() {
    // Clear all input fields in the form
    document.querySelectorAll('.search-fields-job-view-jordan input').forEach(input => input.value = '');

    // Redirect to the same page without query parameters
    window.location.href = "<?php echo esc_url(get_permalink()); ?>";
}
</script>