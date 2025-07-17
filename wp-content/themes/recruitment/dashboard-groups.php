<?php
/*
Template Name: dashboard Groups
*/

if (!session_id()) {
    session_start();
}

// ✅ Check session for login
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    wp_redirect(home_url('/entering-system'));
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'groups';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Add Group
    if (isset($_POST['add_group'])) {
        $group = sanitize_text_field($_POST['group_value'] ?? '');

        if (!empty($group)) {
            $existing = $wpdb->get_row(
                $wpdb->prepare("SELECT id FROM $table_name WHERE LOWER(group_name) = LOWER(%s)", $group)
            );

            if ($existing) {
                $_SESSION['error_message'] = 'This Group already exists.';
            } else {
                $wpdb->insert($table_name, ['group_name' => $group], ['%s']);
                $_SESSION['success_message'] = 'Group added successfully.';
            }
        } else {
            $_SESSION['error_message'] = 'Please enter a Group name.';
        }

        wp_redirect(get_permalink());
        exit;
    }

    // ✅ Delete Group
    if (isset($_POST['delete_group'])) {
        $group = sanitize_text_field($_POST['group_value'] ?? '');

        if (!empty($group)) {
            $existing = $wpdb->get_row(
                $wpdb->prepare("SELECT id FROM $table_name WHERE LOWER(group_name) = LOWER(%s)", $group)
            );

            if ($existing) {
                $wpdb->delete($table_name, ['id' => $existing->id], ['%d']);
                $_SESSION['success_message'] = 'Group deleted successfully.';
            } else {
                $_SESSION['error_message'] = 'Group not found.';
            }
        } else {
            $_SESSION['error_message'] = 'Please enter a Group name to delete.';
        }

        wp_redirect(get_permalink());
        exit;
    }
}

get_header('dashboard');
?>

<div class="groups-header">Groups</div>
<div class="groups-container">

  <?php if (!empty($_SESSION['success_message'])): ?>
    <div class="notice success"><?php echo esc_html($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error_message'])): ?>
    <div class="notice error"><?php echo esc_html($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
  <?php endif; ?>

  <form class="groups-form" method="POST">
    <div class="groups-form-row">
      <div class="groups-form-group">
        <label for="groupSelect" class="groups-label">Select Group</label>
        <select id="groupSelect" class="groups-select">
          <option value="">-- Select Group --</option>
          <?php
          $groups = $wpdb->get_results("SELECT group_name FROM $table_name ORDER BY group_name ASC");
          if ($groups) {
              foreach ($groups as $g) {
                  echo '<option value="' . esc_attr($g->group_name) . '">' . esc_html($g->group_name) . '</option>';
              }
          }
          ?>
        </select>
      </div>

      <div class="groups-form-group">
        <label for="groupName" class="groups-label">Group Name</label>
        <input type="text" id="groupName" name="group_value" class="groups-input" placeholder="Enter group name">
      </div>
    </div>

    <div class="groups-button-group">
      <button type="submit" name="add_group" class="groups-btn">Add</button>
      <button type="submit" name="delete_group" class="groups-btn">Delete</button>
      <button type="reset" class="groups-btn">Clear</button>
    </div>
  </form>

  <div class="groups-details-title">Group Details:-</div>

  <table class="groups-table">
    <thead>
      <tr>
        <th>Group Name</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($groups) {
          foreach ($groups as $g) {
              echo '<tr><td>' . esc_html($g->group_name) . '</td></tr>';
          }
      } else {
          echo '<tr><td>No groups found.</td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<?php get_footer(); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const groupSelect = document.getElementById("groupSelect");
  const groupName = document.getElementById("groupName");

  groupSelect.addEventListener("change", function () {
    groupName.value = this.options[this.selectedIndex].text;
  });
});
</script>
