<?php
/*
Template Name: admin-dashboard
*/

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

global $wpdb;
$table = $wpdb->prefix . 'entering_system';

// Handle Add or Delete User form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $user_name     = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : '';
    $user_role     = isset($_POST['userType']) ? sanitize_text_field($_POST['userType']) : 'user';
    $user_password = isset($_POST['user_password']) ? sanitize_text_field($_POST['user_password']) : '';

    if ($_POST['form_action'] === 'add_user') {
        if (!empty($user_name) && !empty($user_role) && !empty($user_password)) {
            if ($user_id > 0) {
                // Update existing user
                $wpdb->update(
                    $table,
                    [
                        'user_name'     => $user_name,
                        'user_role'     => $user_role,
                        'user_password' => $user_password,
                    ],
                    ['id' => $user_id]
                );
            } else {
                // Insert new user
                $wpdb->insert(
                    $table,
                    [
                        'user_name'     => $user_name,
                        'user_role'     => $user_role,
                        'user_password' => $user_password,
                    ]
                );
            }
            wp_redirect(home_url('/wpjob/user/'));
            exit;
        }
    }

    if ($_POST['form_action'] === 'delete_user') {
        if ($user_id > 0) {
            $wpdb->delete($table, ['id' => $user_id]);
            wp_redirect(home_url('/wpjob/user/'));
            exit;
        }
    }
}


$selected_user = null;
$user_id = 0;
$user_name = '';
$user_role = 'user'; // default
$user_password = '';

// Handle user selection from dropdown
if (isset($_POST['selected_user_id']) && !empty($_POST['selected_user_id'])) {
    $user_id = intval($_POST['selected_user_id']);
    $selected_user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $user_id));

    if ($selected_user) {
        $user_name = $selected_user->user_name;
        $user_role = $selected_user->user_role;
        $user_password = $selected_user->user_password;
    }
}

// Handle Add or Delete User form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $user_name     = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : '';
    $user_role     = isset($_POST['userType']) ? sanitize_text_field($_POST['userType']) : 'user';
    $user_password = isset($_POST['user_password']) ? sanitize_text_field($_POST['user_password']) : '';

    echo  $user_password;

    if ($_POST['form_action'] === 'add_user') {
        if (!empty($user_name) && !empty($user_role) && !empty($user_password)) {
            if ($user_id > 0) {
                // Update existing user
                $wpdb->update(
                    $table,
                    [
                        'user_name'     => $user_name,
                        'user_role'     => $user_role,
                        'user_password' => $user_password,
                    ],
                    ['id' => $user_id]
                );
            } else {
                // Insert new user
                $wpdb->insert(
                    $table,
                    [
                        'user_name'     => $user_name,
                        'user_role'     => $user_role,
                        'user_password' => $user_password,
                    ]
                );
            }
            wp_redirect(home_url('/wpjob/user/'));
            exit;
        }
    }

    if ($_POST['form_action'] === 'delete_user') {
        if ($user_id > 0) {
            $wpdb->delete($table, ['id' => $user_id]);
            wp_redirect(home_url('/wpjob/user/'));
            exit;
        }
    }
}

get_header('dashboard');

// Get list of users for dropdown
$users = $wpdb->get_results("SELECT id, user_name FROM $table");
?>

<div class="container3">
    <h2 class="header3">User Master</h2>
 <div class="form-box3">
    <!-- User select dropdown form -->
    <form method="POST" action="">
        <div class="form-group3">
            <label for="selectUser">Select User Name</label>
            <select id="selectUser" name="selected_user_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo esc_attr($user->id); ?>" <?php selected($user_id, $user->id); ?>>
                        <?php echo esc_html($user->user_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- Add/Edit user form -->
   
        <form method="POST" action="">
            <input type="hidden" name="form_action" value="add_user">
            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">

            <div class="form-grid3">
                <div class="form-group3">
                    <label>User Type</label>
                    <div class="radio-group3">
                        <label><input type="radio" name="userType" value="admin" <?php checked($user_role, 'admin'); ?>> Admin</label>
                        <label><input type="radio" name="userType" value="user" <?php checked($user_role, 'user'); ?>> User</label>
                    </div>
                </div>

                <div class="form-group3">
                    <label for="userName">User Name</label>
                    <input type="text" id="userName" name="user_name" value="<?php echo esc_attr($user_name); ?>" required>
                </div>

                <div class="form-group3">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="user_password" value="<?php echo esc_attr($user_password); ?>" required>
                </div>
            </div>

            <div class="button-group3">
                <button type="submit" class="btn3">Save</button>

                <button 
                  type="submit" 
                  class="btn3" 
                  onclick="return confirm('Are you sure you want to delete this user?');" 
                  name="form_action" 
                  value="delete_user"
                  <?php echo ($user_id == 0) ? 'disabled' : ''; ?>
                >Delete</button>

                <button type="button" class="btn3" id="clearBtn">Clear</button> 
            </div>
        </form>
    </div>

    <div class="user-details3">
        <h3><span class="green-text3">User Details:-</span></h3>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>User Type</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_details = $wpdb->get_results("SELECT id, user_name, user_role, user_password FROM $table");
                if (!empty($user_details)) :
                    foreach ($user_details as $user) :
                ?>
                        <tr>
                            <td><?php echo esc_html($user->user_name); ?></td>
                            <td><?php echo esc_html($user->user_role); ?></td>
                            <td><?php echo esc_html($user->user_password); ?></td>
                        </tr>
                <?php
                    endforeach;
                else :
                ?>
                    <tr>
                        <td colspan="3">No user data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.getElementById('clearBtn').addEventListener('click', function() {
    // Clear text inputs
    document.getElementById('userName').value = '';
    document.getElementById('password').value = '';

    // Reset radio buttons to default ('user')
    let radios = document.getElementsByName('userType');
    radios.forEach(radio => {
        if (radio.value === 'user') {
            radio.checked = true;
        } else {
            radio.checked = false;
        }
    });

    // Reset the hidden user_id field
    document.querySelector('input[name="user_id"]').value = '';

    // Also reset the select dropdown by submitting empty selection
    // or you can reset the select outside the form:
    let selectUser = document.getElementById('selectUser');
    if (selectUser) {
        selectUser.value = '';  // Set dropdown to no selection
    }
});
</script>


<?php get_footer(); ?>
