<?php
/*
Template Name: dashboard-experience
*/

if (!session_id()) {
    session_start();
}

ob_start(); // Prevent header already sent warning

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    wp_redirect(home_url('/entering-system'));
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'experience';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_range = sanitize_text_field($_POST['experience_range'] ?? '');
    $min = intval($_POST['min_experience'] ?? 0);
    $max = intval($_POST['max_experience'] ?? 0);

    // ✅ ADD
    if (isset($_POST['add_experience'])) {
        if (!empty($selected_range)) {
            [$auto_min, $auto_max] = explode('-', $selected_range);
            $min = intval($auto_min);
            $max = intval($auto_max);
        }

        $exists = $wpdb->get_row(
            $wpdb->prepare("SELECT id FROM $table_name WHERE min_experience = %d AND max_experience = %d", $min, $max)
        );

        if ($exists) {
            $_SESSION['error_message'] = 'This experience range already exists.';
        } else {
            $wpdb->insert($table_name, ['min_experience' => $min, 'max_experience' => $max], ['%d', '%d']);
            $_SESSION['success_message'] = 'Experience range added successfully.';
        }

        wp_redirect(get_permalink());
        exit;
    }

    // ✅ DELETE
    if (isset($_POST['delete_experience'])) {
        if (!empty($selected_range)) {
            [$sel_min, $sel_max] = explode('-', $selected_range);
            $sel_min = intval($sel_min);
            $sel_max = intval($sel_max);

            $deleted = $wpdb->delete(
                $table_name,
                ['min_experience' => $sel_min, 'max_experience' => $sel_max],
                ['%d', '%d']
            );

            if ($deleted) {
                $_SESSION['success_message'] = 'Experience range deleted successfully.';
            } else {
                $_SESSION['error_message'] = 'Experience range could not be deleted or was not found.';
            }
        } else {
            $_SESSION['error_message'] = 'Please select an experience range to delete.';
        }

        wp_redirect(get_permalink());
        exit;
    }

    // ✅ UPDATE
    if (isset($_POST['update_experience'])) {

        if (!empty($selected_range)) {
            [$old_min, $old_max] = explode('-', $selected_range);
            $old_min = intval($old_min);
            $old_max = intval($old_max);

            // Check if trying to update to a range that already exists
            $duplicate = $wpdb->get_row(
                $wpdb->prepare("SELECT id FROM $table_name WHERE min_experience = %d AND max_experience = %d", $min, $max)
            );

            if ($duplicate) {
                $_SESSION['error_message'] = 'Cannot update. The new experience range already exists.';
            } else {
                $updated = $wpdb->update(
                    $table_name,
                    ['min_experience' => $min, 'max_experience' => $max],
                    ['min_experience' => $old_min, 'max_experience' => $old_max],
                    ['%d', '%d'],
                    ['%d', '%d']
                );

                if ($updated !== false) {
                    $_SESSION['success_message'] = 'Experience range updated successfully.';
                } else {
                    $_SESSION['error_message'] = 'Experience range update failed.';
                }
            }
        } else {
            $_SESSION['error_message'] = 'Please select an experience range to update.';
        }

        wp_redirect(get_permalink());
        exit;
    }
}

get_header('dashboard');
?>

<div class="header-experience-add">Experience Master</div>
<div class="container-experience-add">

  <?php if (!empty($_SESSION['success_message'])): ?>
    <div class="notice success"><?php echo esc_html($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error_message'])): ?>
    <div class="notice error"><?php echo esc_html($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
  <?php endif; ?>

  <form id="experienceForm" method="POST">
    <div class="form-section-experience-add">
      <div class="form-row-experience-add">
        <div class="form-group-experience-add">
          <label>Select Experience</label>
          <select name="experience_range" id="experience_range">
            <option value="">Select Experience</option>
            <?php
            $results = $wpdb->get_results("SELECT min_experience, max_experience FROM $table_name ORDER BY min_experience ASC");
            if ($results) {
              foreach ($results as $row) {
                $range_value = $row->min_experience . '-' . $row->max_experience;
                $range_label = $row->min_experience . ' - ' . $row->max_experience . ' years';
                echo '<option value="' . esc_attr($range_value) . '">' . esc_html($range_label) . '</option>';
              }
            }
            ?>
          </select>
        </div>

        <div class="form-group-experience-add">
          <label>Experience(Years): Min</label>
          <select name="min_experience" id="min_experience" required>
            <?php for ($i = 0; $i <= 25; $i++) echo "<option value='$i'>$i</option>"; ?>
          </select>
        </div>

        <div class="form-group-experience-add">
          <label>Max.</label>
          <select name="max_experience" id="max_experience" required>
            <?php for ($i = 0; $i <= 25; $i++) echo "<option value='$i'>$i</option>"; ?>
          </select>
        </div>
      </div>

      <div class="buttonsWrapper">
        <div class="buttons-experience-add">
          <button type="submit" name="add_experience" class="btn-add-experience-add">Add</button>
        </div>
        <div class="buttons-experience-add">
          <button type="submit" name="update_experience" class="btn-add-experience-add">Update</button>
        </div>
        <div class="buttons-experience-add">
          <button type="submit" name="delete_experience" class="btn-add-experience-add">Delete</button>
        </div>
        <div class="buttons-experience-add">
          <button type="reset" class="btn-add-experience-add">Clear</button>
        </div>
      </div>
    </div>
  </form>

  <div class="details-experience-add">
    <h3><a href="#">Experience Details:-</a></h3>
    <table class="table-experience-add">
      <thead>
        <tr>
          <th>Experience Range</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rows = $wpdb->get_results("SELECT min_experience, max_experience FROM $table_name ORDER BY min_experience ASC");
        foreach ($rows as $row) {
          echo '<tr><td>' . esc_html($row->min_experience) . ' - ' . esc_html($row->max_experience) . ' Years</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('experience_range').addEventListener('change', function () {
  const val = this.value.split('-');
  console.log("helkooooo",val);
  if (val.length === 2) {
    document.getElementById('min_experience').value = val[0];
    document.getElementById('max_experience').value = val[1];
  }
});
</script>

<style>
  .buttonsWrapper {
    display: flex;
    gap: 20px;
  }
</style>

<?php get_footer(); ob_end_flush(); ?>
