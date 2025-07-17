<?php
/*
Template Name: dashboard search cv
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

<div class="form-grid-search-cv">
  <!-- Left Column -->
  <div class="column-left-search-cv">
    <form id="cv-search-form">
      <label>
        <input type="checkbox" class="toggle-field" data-target="#educational-category">
        Educational Category
      </label>
      <select class="select-search-cv" id="educational-category" name="educational_category" disabled>
        <option>Medical and health care sector</option>
        <option>Business and Economics</option>
        <option>Engineering</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#bachelor-degree">
        Bachelor's Degree In
      </label>
      <select class="select-search-cv" id="bachelor-degree" name="bachelor_degree" disabled>
        <option>Acupuncture</option>
        <option>Pharmacy</option>
        <option>Radiology</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#phd-degree">
        PHD In
      </label>
      <select class="select-search-cv" id="phd-degree" name="phd_degree" disabled>
        <option>Acupuncture</option>
        <option>Public Health</option>
        <option>Medical Informatics</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#high-diploma">
        High Diploma
      </label>
      <select class="select-search-cv" id="high-diploma" name="high_diploma" disabled>
        <option>Acupuncture</option>
        <option>Nursing</option>
        <option>Rehabilitation</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#experience">
        Experience
      </label>
      <select class="select-search-cv" id="experience" name="experience" disabled>
        <option>Fresher</option>
        <option>1-3 Years</option>
        <option>5+ Years</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#by-serial">
        By Serial
      </label>
      <input type="text" class="input-search-cv" id="by-serial" name="by_serial" disabled>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#by-institute">
        By Institute
      </label>
      <select class="select-search-cv" id="by-institute" name="by_institute" disabled>
        <option value="">Select an institute</option>
        <option>Ajloun National University جامعة عجلون الوطنية</option>
        <option>Jordan University of Science and Technology</option>
        <option>University of Jordan</option>
        <option>Philadelphia University</option>
      </select>

      <label>
        <input type="checkbox" class="toggle-field" data-target="#by-average">
        By Average
      </label>
      <select class="select-search-cv" id="by-average" name="by_average" disabled>
        <option>Accepted</option>
        <option>Excellent</option>
        <option>Very Good</option>
      </select>

      <div class="radio-group-search-cv">
        <label><input type="radio" name="filter_type" value="OR"> OR</label>
        <label><input type="radio" name="filter_type" value="AND"> AND</label>
        <label><input type="checkbox" name="with_photo"> With Photo</label>
        <label><input type="checkbox" name="without_photo"> Without Photo</label>
      </div>
  </div>

  <!-- Right Column -->
  <div class="column-right-search-cv">
    <label>
      <input type="checkbox" class="toggle-field" data-target="#master-in">
      Master In
    </label>
    <select class="select-search-cv" id="master-in" name="master_in" disabled>
      <option>Acupuncture</option>
      <option>Hospital Management</option>
      <option>Medical Technology</option>
    </select>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#diploma-in">
      Diploma In
    </label>
    <select class="select-search-cv" id="diploma-in" name="diploma_in" disabled>
      <option>Administration hospitals</option>
      <option>Clinical Support</option>
      <option>Healthcare IT</option>
    </select>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#high-school">
      High School
    </label>
    <select class="select-search-cv" id="high-school" name="high_school" disabled>
      <option>Material Manager</option>
      <option>Science</option>
      <option>Art</option>
    </select>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#group">
      Group
    </label>
    <select class="select-search-cv" id="group" name="group" disabled>
      <option>ACCOUNTING AND BANKING SECTORS</option>
      <option>MEDICAL SECTOR</option>
      <option>ENGINEERING SECTOR</option>
    </select>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#entry-date-from">
      Entry Date
    </label>
    <input type="text" class="input-search-cv" id="entry-date-from" name="entry_date_from" placeholder="From" disabled>
    <input type="text" class="input-search-cv" id="entry-date-to" name="entry_date_to" placeholder="To" disabled>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#mobile">
      Mobile/Phone
    </label>
    <input type="text" class="input-search-cv" id="mobile" name="mobile" disabled>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#gender">
      Gender
    </label>
    <select class="select-search-cv" id="gender" name="gender" disabled>
      <option>Male</option>
      <option>Female</option>
    </select>

    <label>
      <input type="checkbox" class="toggle-field" data-target="#search-keys">
      Search Keys
    </label>
    <input type="text" class="input-search-cv" id="search-keys" name="search_keys" disabled>
  </div>
</div>
  <div class="tabs-search-cv">
    <button type="button" class="tab-btn-search-cv" id="search-btn">Search</button>
    <button class="tab-btn-search-cv">Download</button>
    <button class="tab-btn-search-cv">Action</button>
    <button class="tab-btn-search-cv">Settings</button>
    <button class="tab-btn-search-cv">Master File</button>
  </div>
</form>

  <div class="buttons-section-search-cv">
    <button class="icon-btn-search-cv search"></button>
    <button class="icon-btn-search-cv clear-search"></button>
    <button class="icon-btn-search-cv clear-mark"></button>
    <button class="icon-btn-search-cv info"></button>
  </div>

  <textarea class="note-box-search-cv" rows="5"></textarea>

 <div class="scroll-container-search-cv">
  <table class="table-search-cv">
    <thead>
      <tr align="center" style="background-color:Tan;font-size:Small;font-weight:bold;white-space:nowrap;">
        <td>Select</td>
        <td>Name</td>
        <td>File Name</td>
        <td>CV Serial</td>
        <td>Major</td>
        <td>Group Name</td>
        <td>Entry Date</td>
        <td>UniAvgTest</td>
        <td>Email Address</td>
        <td>Institute</td>
        <td>Mobile No</td>
      </tr>
    </thead>
    <tbody>
      <!-- Example row -->
      <tr align="center">
        <td><input type="radio" name="select-cv"></td>
        <td>John Doe</td>
        <td>resume-john.pdf</td>
        <td>12345</td>
        <td>Computer Science</td>
        <td>IT Group</td>
        <td>2024-06-01</td>
        <td>88%</td>
        <td>john@example.com</td>
        <td>XYZ University</td>
        <td>+1234567890</td>
      </tr>
      <!-- Add more rows as needed -->
    </tbody>
  </table>
</div>

  <div class="note-section-search-cv">
    <label>Add Note On This CV</label>
    <input type="text" class="input-search-cv">
    <button class="save-note-search-cv">Save note for the selected CV</button>
    <button class="edit-cv-search-cv">Edit CV</button>
  </div>
</div>


<?php get_footer(); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.toggle-field').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      const targetSelector = this.getAttribute('data-target');
      const targetField = document.querySelector(targetSelector);
      if (targetField) {
        targetField.disabled = !this.checked;
      }
    });
  });

  document.getElementById('search-btn').addEventListener('click', function () {
    const form = document.getElementById('cv-search-form');
    const data = new FormData();

    // Collect only enabled fields
    form.querySelectorAll('[name]').forEach(el => {
      if (!el.disabled && el.value.trim() !== '') {
        data.append(el.name, el.value.trim());
      }
    });

    data.append('action', 'search_cv'); // required by WordPress

    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      body: data
    })
    .then(response => response.json())
    .then(results => {
      const tbody = document.querySelector('.table-search-cv tbody');
      tbody.innerHTML = ''; // clear previous

      if (results.length === 0) {
        tbody.innerHTML = '<tr><td colspan="11" align="center">No results found.</td></tr>';
        return;
      }

      results.forEach(row => {
        tbody.innerHTML += `
          <tr align="center">
            <td><input type="radio" name="select-cv"></td>
            <td>${row.first_name} ${row.family_name}</td>
            <td>${row.cv_path ?? 'N/A'}</td>
            <td>${row.id}</td>
            <td>${row.specialization}</td>
            <td>${row.education_category}</td>
            <td>${row.created_at}</td>
            <td>${row.degree}</td>
            <td>${row.email}</td>
            <td>${row.university}</td>
            <td>${row.contact_no}</td>
          </tr>
        `;
      });
    });
  });
});
</script>