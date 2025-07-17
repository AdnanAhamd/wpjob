<?php
/*
Template Name: dashboard-list-search
*/

if (!session_id()) session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    wp_redirect(home_url('/entering-system'));
    exit;
}

get_header('dashboard');
global $wpdb;

// ✅ Handle status update if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    foreach ($_POST['status'] as $meta_id => $new_status) {
        $wpdb->update(
            'wp_employers_meta',
            ['status_select' => sanitize_text_field($new_status)],
            ['id' => intval($meta_id)]
        );
    }

    // Optional: Show success message
    echo '<div style="color: green; margin: 10px;">Status updated successfully.</div>';
}

// ✅ Build WHERE clause
$where = "WHERE 1=1";
if (!empty($_GET['employer_name'])) {
    $where .= $wpdb->prepare(" AND emp.employer_name LIKE %s", '%' . $_GET['employer_name'] . '%');
}
if (!empty($_GET['email'])) {
    $where .= $wpdb->prepare(" AND emp.email LIKE %s", '%' . $_GET['email'] . '%');
}
if (!empty($_GET['from_date'])) {
    $where .= $wpdb->prepare(" AND meta.date >= %s", $_GET['from_date']);
}
if (!empty($_GET['to_date'])) {
    $where .= $wpdb->prepare(" AND meta.date <= %s", $_GET['to_date']);
}

// ✅ Get results
$results = $wpdb->get_results("
  SELECT 
    meta.id,
    meta.date,
    emp.employer_name,
    emp.contact_number,
    emp.email,
    meta.title,
    meta.marital_status,
    meta.gender,
    meta.required_number,
    meta.nationality,
    meta.qualification,
    meta.specialization,
    meta.experience,
    meta.salary,
    meta.status_select
  FROM wp_employers_meta meta
  LEFT JOIN wp_employers emp ON meta.user_id = emp.id
  $where
  ORDER BY meta.id DESC
");
?>

<div class="client-box-list-search">
  <div class="c
  lient-header-list-search">Client Requirements</div>

  <!-- Search Form -->
  <form method="get" class="search-section-list-search">
    <div><label>Search By :-</label></div>
    <div><input type="text" name="employer_name" placeholder="Employer Name" value="<?php echo esc_attr($_GET['employer_name'] ?? ''); ?>"></div>
    <div><input type="text" name="email" placeholder="Email ID" value="<?php echo esc_attr($_GET['email'] ?? ''); ?>"></div>
    <div><input type="date" name="from_date" value="<?php echo esc_attr($_GET['from_date'] ?? ''); ?>"></div>
    <div><input type="date" name="to_date" value="<?php echo esc_attr($_GET['to_date'] ?? ''); ?>"></div>
    <div><button type="submit">Search</button></div>
  </form>

  <!-- Requirements Table Form -->
  <form method="post">
    <div class="table-section-list-search">
      <label style="color: green; font-weight: bold;">All Requirements :-</label>

      <table class="table-list-search">
        <thead>
          <tr>
            <th>Sl.No</th>
            <th>Status</th>
            <th>Request Date</th>
            <th>Employer Name</th>
            <th>Contact No.</th>
            <th>Email Id</th>
            <th>Job Title</th>
            <th>Marital Status</th>
            <th>Gender</th>
            <th>Total Requirement</th>
            <th>Nationality</th>
            <th>Qualifications</th>
            <th>Specialization</th>
            <th>Experience (in Years)</th>
            <th>Salary</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($results)) :
            $sr = 1;
            foreach ($results as $row) : ?>
            <tr>
              <td><?php echo esc_html($sr++); ?></td>
              <td>
                <select name="status[<?php echo esc_attr($row->id); ?>]" class="status-select-list-search">
                  <option value="Received" <?php selected('Received', $row->status_select); ?>>Received</option>
                  <option value="Pending" <?php selected('Pending', $row->status_select); ?>>Pending</option>
                  <option value="Closed" <?php selected('Closed', $row->status_select); ?>>Closed</option>
                </select>
              </td>
              <td><?php echo esc_html($row->date ? date('d/m/Y', strtotime($row->date)) : 'N/A'); ?></td>
              <td><?php echo esc_html($row->employer_name ?: 'N/A'); ?></td>
              <td><?php echo esc_html($row->contact_number ?: '-'); ?></td>
              <td><?php echo esc_html($row->email ?: '-'); ?></td>
              <td><?php echo esc_html($row->title); ?></td>
              <td><?php echo esc_html($row->marital_status); ?></td>
              <td><?php echo esc_html($row->gender); ?></td>
              <td><?php echo esc_html($row->required_number); ?></td>
              <td><?php echo esc_html($row->nationality); ?></td>
              <td><?php echo esc_html($row->qualification); ?></td>
              <td><?php echo esc_html($row->specialization); ?></td>
              <td><?php echo esc_html($row->experience); ?></td>
              <td><?php echo esc_html($row->salary); ?></td>
            </tr>
          <?php endforeach;
          else : ?>
            <tr><td colspan="15">No requirements found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Submit button -->
      <div class="submit-button-list-search" style="margin-top: 15px;">
        <button type="submit">Submit Status Changes</button>
      </div>
    </div>
  </form>
</div>

<?php get_footer(); ?>
