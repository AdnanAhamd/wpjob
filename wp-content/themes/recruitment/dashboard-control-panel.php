<?php
/*
Template Name: dashboard control panel
*/

// ✅ Start session if not already started
if (!session_id()) {
    session_start();
}

// ✅ Check session value
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    // 🔁 Redirect to login page (change slug if needed)
    wp_redirect(home_url('/entering-system')); 
    exit;
}

get_header('dashboard'); // Loads header-dashboard.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Control Panel</title>
  <link rel="stylesheet" href="control-panel.css" />
</head>
<body>
  <div class="container-control-panel">
    <div class="header-control-panel">Control panel</div>

    <div class="menu-section-control-panel">
      <!-- JOBS Column -->
      <div class="menu-column-control-panel">
        <div class="menu-title-control-panel ">JOBS</div>
        <div class="image-wrapper-control-panel">
          <img src="http://localhost/wpjob/wp-content/uploads/2025/07/job.png" alt="Jobs Icon" class="menu-image-control-panel2" />
        </div>
        <ul class="menu-list-control-panel">
          <li class="menu-item-control-panel"><a href="/wpjob/add-job/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> Add</a></li>
          <li class="menu-item-control-panel"><a href="/wpjob/jorden-job-view/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> List / Search Jobs in Jordan</a></li>
          <li class="menu-item-control-panel"><a href="/wpjob/gulf-job-view/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> List / Search Jobs in Gulf</a></li>
        </ul>
      </div>

      <!-- CLIENTS Column -->
      <div class="menu-column-control-panel">
        <div class="menu-title-control-panel margin-add-bottom">CLIENTS</div>
        <ul class="menu-list-control-panel">
          <li class="menu-item-control-panel"><a href="/wpjob/list-search/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> List / Search</a></li>
        </ul>
      </div>

      <!-- OTHER MASTERS Column -->
      <div class="menu-column-control-panel">
        <div class="menu-title-control-panel margin-add-bottom">OTHER MASTERS</div>
        <ul class="menu-list-control-panel">
          <li class="menu-item-control-panel"><a href="/wpjob/add-country/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> Country Master</a></li>
          <li class="menu-item-control-panel"><a href="/wpjob/add-university/"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> University Master</a></li>
          <li class="menu-item-control-panel"><a href="#"> <img src="http://localhost/wpjob/wp-content/uploads/2025/07/top.gif" alt="Jobs Icon" class="menu-image-control-panel" /> Advertisement Master</a></li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>

<?php get_footer(); ?>