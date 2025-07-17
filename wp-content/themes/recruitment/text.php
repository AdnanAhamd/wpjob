<?php
/* Template Name: Add Major Simple */
get_header();

// Create DB table (optional for dev)
global $wpdb;
$table = $wpdb->prefix . 'majors';
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table (
  id INT NOT NULL AUTO_INCREMENT,
  major_name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) $charset_collate;";
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
dbDelta($sql);
?>

<div class="wrap">
  <h2>Add Specialization</h2>

  <input type="text" id="major-input" placeholder="Enter specialization" />
  <button id="add-major-btn">Add</button>

  <table border="1" cellpadding="10" cellspacing="0" style="margin-top:20px;">
    <thead>
      <tr><th>Specialization</th><th>ID</th></tr>
    </thead>
    <tbody id="majors-table">
      <?php
      $majors = $wpdb->get_results("SELECT * FROM $table");
      foreach ($majors as $major) {
          echo '<tr><td>' . esc_html($major->major_name) . '</td><td>' . esc_html($major->id) . '</td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("major-input");
  const button = document.getElementById("add-major-btn");
  const table = document.getElementById("majors-table");

  button.addEventListener("click", function () {
    const majorName = input.value.trim();
    if (!majorName) {
      alert("Please enter a specialization.");
      return;
    }

    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        action: 'add_major',
        major_name: majorName
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const newRow = `<tr><td>${majorName}</td><td>${data.serial_no}</td></tr>`;
        table.insertAdjacentHTML("beforeend", newRow);
        input.value = "";
      } else {
        alert(data.message || "Error adding specialization.");
      }
    })
    .catch(err => {
      console.error(err);
      alert("AJAX request failed.");
    });
  });
});
</script>

<?php get_footer(); ?>