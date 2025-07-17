<?php
/*
Template Name: dashboard add university
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
$table_name = $wpdb->prefix . 'university_add';  // Your table name

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // sanitize inputs
    $country_input = sanitize_text_field($_POST['group_value'] ?? '');
    $selected_country = sanitize_text_field($_POST['selected_country'] ?? '');

    if (isset($_POST['add_group'])) {

        if (empty($country_input)) {
            $_SESSION['error_message'] = 'Please enter a country name.';
            wp_redirect(get_permalink());
            exit;
        }

        // Check if country_input already exists (case insensitive)
        $existing_country = $wpdb->get_row(
            $wpdb->prepare("SELECT id FROM $table_name WHERE LOWER(university_name) = LOWER(%s)", $country_input)
        );

        // User selected an existing country and changed the name => Update
        if (!empty($selected_country)) {
            // Get ID of selected country
            $selected_row = $wpdb->get_row(
                $wpdb->prepare("SELECT id FROM $table_name WHERE university_name = %s", $selected_country)
            );

            if ($selected_row) {
                // If the new name is same as old, no need to update
                if (strcasecmp($selected_country, $country_input) === 0) {
                    $_SESSION['success_message'] = 'No changes made.';
                } else {
                    // Check if the new name already exists in another record
                    if ($existing_country && $existing_country->id != $selected_row->id) {
                        $_SESSION['error_message'] = 'This country name already exists.';
                        wp_redirect(get_permalink());
                        exit;
                    }
                    // Update country name
                    $wpdb->update(
                        $table_name,
                        ['university_name' => $country_input],
                        ['id' => $selected_row->id],
                        ['%s'],
                        ['%d']
                    );
                    $_SESSION['success_message'] = 'Country updated successfully.';
                }
            } else {
                $_SESSION['error_message'] = 'Selected country not found.';
            }
        } else {
            // No selected country, so insert new country if not exists
            if ($existing_country) {
                $_SESSION['error_message'] = 'This country already exists.';
            } else {
                $wpdb->insert($table_name, ['university_name' => $country_input], ['%s']);
                $_SESSION['success_message'] = 'Country added successfully.';
            }
        }

        wp_redirect(get_permalink());
        exit;
    }

    if (isset($_POST['delete_group'])) {
        if (empty($country_input)) {
            $_SESSION['error_message'] = 'Please enter a country name to delete.';
            wp_redirect(get_permalink());
            exit;
        }

        $existing = $wpdb->get_row(
            $wpdb->prepare("SELECT id FROM $table_name WHERE LOWER(university_name) = LOWER(%s)", $country_input)
        );

        if ($existing) {
            $wpdb->delete($table_name, ['id' => $existing->id], ['%d']);
            $_SESSION['success_message'] = 'Country deleted successfully.';
        } else {
            $_SESSION['error_message'] = 'Country not found.';
        }

        wp_redirect(get_permalink());
        exit;
    }
}

get_header('dashboard');
?>

<div class="groups-header">University Master</div>
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
        <label for="groupSelect" class="groups-label">Select University</label>
        <select id="groupSelect" name="selected_country" class="groups-select">
          <option value="">-- Select University --</option>
          <?php
          $countries = $wpdb->get_results("SELECT university_name FROM $table_name ORDER BY university_name ASC");
          if ($countries) {
              foreach ($countries as $c) {
                  echo '<option value="' . esc_attr($c->university_name) . '">' . esc_html($c->university_name) . '</option>';
              }
          }
          ?>
        </select>
      </div>

      <div class="groups-form-group">
        <label for="groupName" class="groups-label">University Name</label>
        <input type="text" id="groupName" name="group_value" class="groups-input" placeholder="Enter university name" autocomplete="off">
      </div>
    </div>

    <div class="groups-button-group">
      <button type="submit" name="add_group" class="groups-btn">Save</button>
      <button type="submit" name="delete_group" class="groups-btn">Delete</button>
      <button type="reset" class="groups-btn" id="clearBtn">Clear</button>
    </div>
  </form>

  <div class="groups-details-title">Country Details:</div>

  <table class="groups-table">
    <thead>
      <tr>
        <th>Country Name</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($countries) {
          foreach ($countries as $c) {
              echo '<tr><td>' . esc_html($c->university_name) . '</td></tr>';
          }
      } else {
          echo '<tr><td>No countries found.</td></tr>';
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
  const clearBtn = document.getElementById("clearBtn");

  groupSelect.addEventListener("change", function () {
    groupName.value = this.value;
  });

  clearBtn.addEventListener("click", function () {
    groupSelect.value = "";
    groupName.value = "";
  });
});
</script>
