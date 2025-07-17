<?php
/*
Template Name: Employer info Template
*/

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0; 
if ($user_id <= 0) {
    wp_redirect(site_url('/employers-request/')); // Redirect to login page
}

get_header();

global $wpdb;

$employers = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}employers WHERE id = %d", 
    $user_id
), ARRAY_A );

if (isset($_POST['submit_request'])) {

    // Get Form Data
    $job_titles      = $_POST['job_title'] ?? [];
    $marital_status  = $_POST['marital_status'] ?? [];
    $gender          = $_POST['gender'] ?? [];
    $required_number = $_POST['required_number'] ?? [];
    $nationality     = $_POST['nationality'] ?? [];
    $qualification   = $_POST['qualification'] ?? [];
    $specialization  = $_POST['specialization'] ?? [];
    $min_exp         = $_POST['min_experience'] ?? [];
    $max_exp         = $_POST['max_experience'] ?? [];
    $nature_of_work  = $_POST['nature_of_work'] ?? [];
    $salary          = $_POST['salary'] ?? [];
    $remarks         = $_POST['remarks'] ?? [];
    $row_ids = $_POST['row_id'] ?? [];


    $table = $wpdb->prefix . 'employers_meta';

    // Loop through rows
    $total_rows = count($job_titles);

    for ($i = 0; $i < $total_rows; $i++) {
      if (empty($row_ids[$i])) {
        $experience = sanitize_text_field($min_exp[$i]) . ' - ' . sanitize_text_field($max_exp[$i]);
        
        $wpdb->insert($table, [
            'title'           => sanitize_text_field($job_titles[$i]),
            'marital_status'  => sanitize_text_field($marital_status[$i]),
            'gender'          => sanitize_text_field($gender[$i]),
            'required_number' => sanitize_text_field($required_number[$i]),
            'nationality'     => sanitize_text_field($nationality[$i]),
            'qualification'   => sanitize_text_field($qualification[$i]),
            'specialization'  => sanitize_text_field($specialization[$i]),
            'experience'      => $experience,
            'nature_of_work'  => sanitize_text_field($nature_of_work[$i]),
            'salary'          => sanitize_text_field($salary[$i]),
            'remarks'         => sanitize_text_field($remarks[$i]),
            'date'            => current_time('mysql'),
            'user_id'         => $user_id,
        ]);
      }
    }

    echo '<div style="color:green;">All Requests Saved Successfully!</div>';
}



$results = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}employers_meta WHERE user_id = %d", $user_id),
    ARRAY_A
);

?>


<?php 
if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}
?>

<div class="gulf-container">
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title"><?php echo $breadcrumb; ?></div>
<?php } ?>


<div class="form-container">
    <div class="form-row">
        <div class="form-label">Company Name</div>
        <div class="form-input"><?php echo esc_attr($employers['company_name'] ?? ''); ?></div>
    </div>
    <div class="form-row">
        <div class="form-label">Email ID</div>
        <div class="form-input"><?php echo esc_attr($employers['email'] ?? ''); ?></div>
    </div>
    <div class="form-row">
        <div class="form-label">Please Select Request Type</div>
        <div class="form-input">
            <select>
                <option value="1">1 : Request Date : 05/07/2025</option>
            </select>
        </div>
    </div>

    <h4 class="tableHeading">Please Fill Your Requirements :-</h4>
   <form method="post" enctype="multipart/form-data">
  <table class="requirementsTable">
    <thead>
      <tr>
        <th></th>
        <th>Job Title</th>
        <th>Marital Status</th>
        <th>Gender</th>
        <th>Required Number</th>
        <th>Nationality</th>
        <th>Qualifications</th>
        <th>Specialization</th>
        <th>Experience in Years</th>
        <th>Nature of Work</th>
        <th>Salary</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody id="requirementRows">
      <?php
      if ($results) {
          $index = 1;
          foreach ($results as $row) {
              $experience_parts = explode('-', $row['experience']);
              $min_exp = isset($experience_parts[0]) ? trim($experience_parts[0]) : '';
              $max_exp = isset($experience_parts[1]) ? trim($experience_parts[1]) : '';
              ?>
              <tr>
                <td>
                  <?= $index++; ?>
                  <input type="hidden" name="row_id[]" value="<?= esc_attr($row['id']); ?>">
                </td>
                <td><input type="text" name="job_title[]" value="<?= esc_attr($row['title']) ?>"></td>
                <td>
                  <select name="marital_status[]">
                    <option value="Married" <?= $row['marital_status'] === 'Married' ? 'selected' : '' ?>>Married</option>
                    <option value="Single" <?= $row['marital_status'] === 'Single' ? 'selected' : '' ?>>Unmarried</option>
                  </select>
                </td>
                <td>
                  <select name="gender[]">
                    <option value="Male" <?= $row['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $row['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                  </select>
                </td>
                <td>
                  <select name="required_number[]">
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                      <option value="<?= $i ?>" <?= $row['required_number'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td>
                  <select name="nationality[]">
                    <?php
                    $nations = ["Afghanistan", "Algeria", "Bahrain", "Britain", "China", "Egypt", "Germany", "Iraq", "Jordan", "Kuwait", "Lebanon", "Libya", "London", "Mauritania", "Morocco", "Palestine", "Qatar", "Russia", "Saudi Arabia", "Somalia", "Sudan", "Sudan السودان", "Sultanate of Oman", "Syria", "Thailand", "Tunis تونس", "Ukraine", "United Arab Emirates", "USA", "Yemen"];
                    foreach ($nations as $nation) {
                      echo '<option value="' . esc_attr($nation) . '" ' . ($row['nationality'] === $nation ? 'selected' : '') . '>' . esc_html($nation) . '</option>';
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="qualification[]" value="<?= esc_attr($row['qualification']) ?>"></td>
                <td><input type="text" name="specialization[]" value="<?= esc_attr($row['specialization']) ?>"></td>
                <td>
                  <div class="experienceWrapper">
                    <select name="min_experience[]" style="width: 60px;">
                      <option value="">minimum</option>
                      <?php for ($e = 0; $e <= 10; $e++) { ?>
                        <option value="<?= $e ?>" <?= $min_exp !== '' && $min_exp == $e ? 'selected' : '' ?>><?= $e ?></option>
                      <?php } ?>
                    </select>
                    <select name="max_experience[]" style="width: 60px;">
                      <option value="">maximum</option>
                      <?php for ($e = 0; $e <= 10; $e++) { ?>
                        <option value="<?= $e ?>" <?= $max_exp !== '' && $max_exp == $e ? 'selected' : '' ?>><?= $e ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </td>
                <td><input type="text" name="nature_of_work[]" value="<?= esc_attr($row['nature_of_work']) ?>"></td>
                <td><input type="text" name="salary[]" value="<?= esc_attr($row['salary']) ?>"></td>
                <td><input type="text" name="remarks[]" value="<?= esc_attr($row['remarks']) ?>"></td>
              </tr>
              <?php
          }
      } else {
?>
          <tr>
                <td>
                  <input type="hidden" name="row_id[]">
                </td>
                <td><input type="text" name="job_title[]"></td>
                <td>
                  <select name="marital_status[]">
                    <option value="Married" selected>Married</option>
                    <option value="Single">Unmarried</option>
                  </select>
                </td>
                <td>
                  <select name="gender[]">
                    <option value="Male" selected>Male</option>
                    <option value="Female">Female</option>
                  </select>
                </td>
                <td>
                  <select name="required_number[]">
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td>
                  <select name="nationality[]">
                    <?php
                    $nations = ["Afghanistan", "Algeria", "Bahrain", "Britain", "China", "Egypt", "Germany", "Iraq", "Jordan", "Kuwait", "Lebanon", "Libya", "London", "Mauritania", "Morocco", "Palestine", "Qatar", "Russia", "Saudi Arabia", "Somalia", "Sudan", "Sudan السودان", "Sultanate of Oman", "Syria", "Thailand", "Tunis تونس", "Ukraine", "United Arab Emirates", "USA", "Yemen"];
                    foreach ($nations as $nation) {
                      echo '<option value="' . esc_attr($nation) . '">' . esc_html($nation) . '</option>';
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="qualification[]"></td>
                <td><input type="text" name="specialization[]"></td>
                <td>
                  <div class="experienceWrapper">
                    <select name="min_experience[]" style="width: 60px;">
                      <option value="">minimum</option>
                      <?php for ($e = 0; $e <= 10; $e++) { ?>
                        <option value="<?= $e ?>"><?= $e ?></option>
                      <?php } ?>
                    </select>
                    <select name="max_experience[]" style="width: 60px;">
                      <option value="">maximum</option>
                      <?php for ($e = 0; $e <= 10; $e++) { ?>
                        <option value="<?= $e ?>"><?= $e ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </td>
                <td><input type="text" name="nature_of_work[]"></td>
                <td><input type="text" name="salary[]" ></td>
                <td><input type="text" name="remarks[]"></td>
              </tr>
              <?php
      }
      ?>
    </tbody>
  </table>

  <div class="cloneButton">
    <button type="button" id="addRowBtn" class="cloneRow" style="margin-right: 10px;">Add New Requirement</button>
  </div>
  <div style="text-align: center;">
    <button type="submit" name="submit_request" class="employer-button">Submit Request</button>
  </div>
</form>
    <h4 class="tableHeading">Previous Requirements :-</h4>
    
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Request Date</th>
                <th>Job Title</th>
                <th>Marital Status</th>
                <th>Gender</th>
                <th>Required Number</th>
                <th>Nationality</th>
                <th>Qualifications</th>
                <th>Specialization</th>
                <th>Year of Experience</th>
                <th>Salary</th>
                <th>Remarks</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
      <?php
      if ($results) {
          $index = 1;
          foreach ($results as $row) {
              ?>
            <tr>
                <td><?= $index++; ?></td>
                <td><?= date('d/m/Y', strtotime($row['date'])); ?></td>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $row['marital_status'] ?></td>
                <td><?php echo $row['gender'] ?></td>
                <td><?php echo $row['required_number'] ?></td>
                <td><?php echo $row['nationality'] ?></td>
                <td><?php echo $row['qualification'] ?></td>
                <td><?php echo $row['specialization'] ?></td>
                <td><?php echo $row['experience'] ?></td>
                <td><?php echo $row['salary'] ?></td>
                <td><?php echo $row['remarks'] ?></td>
                <td>Received</td>
            </tr>
          <?php }
          } ?>
        </tbody>
    </table>
</div>


<style type="text/css">
  .tableHeading{
    color: green;
  }
  .form-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
  }
  .form-row {
      display: flex;
      margin-bottom: 10px;
  }
  .form-label {
      width: 200px;
      font-weight: bold;
      font-size: 13px;
  }
  .form-input {
      flex: 1;
  }
  input[type="text"], input[type="number"] {
     width: 100px;
  }
  select{
     width: 50px;
  }
  table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 12px;
  }
  th, td {
      border: 1px solid #ddd;
      padding: 4px;
      text-align: left;
  }
  th {
      background-color: #f2f2f2;
  }
  .button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      cursor: pointer;
      font-size: 16px;
  }
  .button:hover {
      background-color: #45a049;
  }
  .experienceWrapper{
    display: flex;
    gap: 2px;
  }
  .cloneButton{
    display: flex; 
    justify-content: end;
  }
  .cloneButton button{
    color: blue;
    background: none;
    font-size: 14px;
    text-decoration: underline;
    border: none;
  }
</style>
<script>
jQuery(document).ready(function($) {
  $('#addRowBtn').on('click', function() {
    var lastRow = $('.requirementsTable tbody tr:last');
    var clonedRow = lastRow.clone();

    // Optional: Clear input/select values in the cloned row
    clonedRow.find('input').val('');
    clonedRow.find('select').each(function() {
      this.selectedIndex = 0;
    });

    // Update Serial Number (1st TD)
    var newRowNumber = $('.requirementsTable tbody tr').length + 1;
    clonedRow.find('td:first').text(newRowNumber);

    // Append cloned row
    $('.requirementsTable tbody').append(clonedRow);
  });
});
</script>
<?php

get_footer();