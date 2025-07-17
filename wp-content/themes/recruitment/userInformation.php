<?php
/*
Template Name: User Information
*/
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    wp_redirect(site_url('/entering-system')); // Redirect to login page
}



get_header();

$user_application = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}job_applications WHERE id = %d", 
    $user_id
), ARRAY_A );
echo "<div class='userProfileName'>Welcome: ".$user_application['first_name']." ".$user_application['family_name']."</div>";

if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}

?>



<div class="gulf-container">
  <div class="button-container">
      <a href="<?php  echo site_url(); ?>"><button class="nav-button">Home</button></a>
      <a href="<?php  echo site_url().'/recruitment/entering-system/'; ?>"><button class="nav-button">Edit My CV</button></a>
      <a href="<?php  echo site_url().'/recruitment/upload-your-cv/'; ?>"><button class="nav-button">Logout</button></a>
  </div>
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title userInformationBreadCrumb"><?php echo $breadcrumb; ?>
    <p>الرجاء تعبئة وتحميل السيرة الذاتية باللغة العربية.</p>
    <a href="/cv-upload-help/" target="_blank"><h2>Steps CV Upload</h2></a>
    </div>
    
<?php } ?>


<?php
global $wpdb;
$current_user_id = get_current_user_id();

// Fetch user's job application data (adjust where clause if needed)
$user_application = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}job_applications WHERE id = %d", 
    $user_id
), ARRAY_A );

if (isset($_POST['update_application'])) {
    $user_id = $_POST['user_id']; 

    // Sanitize Inputs
    $first_name = sanitize_text_field($_POST['first_name']);
    $family_name = sanitize_text_field($_POST['family_name']);
    $nationality = sanitize_text_field($_POST['nationality']);
    $gender = sanitize_text_field($_POST['gender']);
    $marital_status = sanitize_text_field($_POST['marital_status']);
    $birth_date = sanitize_text_field($_POST['birth_date']);
    $resident_place = sanitize_text_field($_POST['resident_place']);
    $contact_no = sanitize_text_field($_POST['contact_no']);
    $email = sanitize_email($_POST['email']);
    $degree = sanitize_text_field($_POST['degree']);
    $university = sanitize_text_field($_POST['university']);
    $education_category = sanitize_text_field($_POST['education_category']);
    $specialization = sanitize_text_field($_POST['specialization']);
    $job_title = sanitize_text_field($_POST['job_title']);

    $exp_inside = sanitize_text_field($_POST['exp_inside']) . ',' . sanitize_text_field($_POST['exp_inside_months']);
    $exp_outside = sanitize_text_field($_POST['exp_outside']) . ',' . sanitize_text_field($_POST['exp_outside_months']);

    $language = implode(',', array_map('sanitize_text_field', $_POST['language'] ?? []));

    $password =sanitize_text_field($_POST['password']);

    // $cv_path = sanitize_text_field($_POST['cv_file']);
    $cv_path = $user_application['cv_path']; // Keep old path by default

    if (isset($_FILES['cv_file']) && !empty($_FILES['cv_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $cv = wp_handle_upload($_FILES['cv_file'], ['test_form' => false]);

        if (empty($cv['error'])) {
            $cv_path = $cv['url']; // Update only if upload success
        }
    }

   $photo_path = $user_application['photo'];
    if (isset($_FILES['photo_file']) && !empty($_FILES['photo_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $photo = wp_handle_upload($_FILES['photo_file'], ['test_form' => false]);
        if (empty($photo['error'])) {
            $photo_path = $photo['url'];
        }
    }


    // Update DB
    $wpdb->update(
        $wpdb->prefix . 'job_applications',
        [
            'first_name' => $first_name,
            'family_name' => $family_name,
            'nationality' => $nationality,
            'gender' => $gender,
            'marital_status' => $marital_status,
            'birth_date' => $birth_date,
            'place_of_resident' => $resident_place,
            'contact_no' => $contact_no,
            'email' => $email,
            'degree' => $degree,
            'university' => $university,
            'education_category' => $education_category,
            'specialization' => $specialization,
            'job_title' => $job_title,
            'exp_inside' => $exp_inside,
            'exp_outside' => $exp_outside,
            'language' => $language,
            'password' => $password,
            'cv_path' => $cv_path,
            'photo' => $photo_path,
        ],
        ['id' => $user_id]
    );

    echo '<div style="color:green;">Application updated successfully!</div>';
}

$user_application = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}job_applications WHERE id = %d", 
    $user_id
), ARRAY_A );


$exp_inside_parts = isset($user_application['exp_inside']) ? explode(',', $user_application['exp_inside']) : [];
$exp_inside_years = isset($exp_inside_parts[0]) ? intval($exp_inside_parts[0]) : '';
$exp_inside_months = isset($exp_inside_parts[1]) ? intval($exp_inside_parts[1]) : '';

// Split exp_outside into years and months
$exp_outside_parts = isset($user_application['exp_outside']) ? explode(',', $user_application['exp_outside']) : [];
$exp_outside_years = isset($exp_outside_parts[0]) ? intval($exp_outside_parts[0]) : '';
$exp_outside_months = isset($exp_outside_parts[1]) ? intval($exp_outside_parts[1]) : '';

// echo "<pre>";
// print_r($user_application);
// echo "</pre>";

?>

<form method="post" enctype="multipart/form-data">
    <table>
      <!-- General Information -->
      <tr><th colspan="4" class="section-title">GENERAL INFORMATION</th></tr>
      <tr>
        <td>* Full Name</td>
        <td><input type="text" name="first_name" value="<?php echo esc_attr($user_application['first_name'] ?? ''); ?>" placeholder="First Name" /></td>
        <td></td>
        <td><input type="text" name="family_name" value="<?php echo esc_attr($user_application['family_name'] ?? ''); ?>" placeholder="Family Name" /></td>
      </tr>
      <tr>
        <td>* Nationality</td>
        <td colspan="3">
          <select name="nationality" class="CVform-control" style="font-size:15px;width:320px;font-weight: bold">
            <?php
            $nationalities = ['Afghanistan', 'Algeria', 'Bahrain', 'Britain', 'China', 'Egypt', 'Germany', 'Iraq', 'Jordan', 'Kuwait', 'Lebanon', 'Libya', 'London', 'Mauritania', 'Morocco', 'Palestine', 'Qatar', 'Russia', 'Saudi Arabia', 'Somalia', 'Sudan', 'Sudan السودان', 'Sultanate of Oman', 'Syria', 'Thailand', 'Tunis تونس', 'Ukraine', 'United Arab Emirates', 'USA', 'Yemen'];
            foreach ($nationalities as $country) {
              $selected = ($user_application['nationality'] ?? '') == $country ? 'selected' : '';
              echo '<option value="'.esc_attr($country).'" '.$selected.'>'.esc_html($country).'</option>';
            }
            ?>
          </select>
        </td>
      </tr>
      <input type="hidden" name="user_id" value="<?php echo $user_application['id'] ?>">
      <tr>
        <td>* Gender</td>
        <td colspan="3">
          <select name="gender" class="CVform-control" style="font-size:15px;width:200px;">
            <?php
            $gender = ['Male', 'Female'];
            foreach ($gender as $gen) {
              $selected = ($user_application['gender'] ?? '') == $gen ? 'selected' : '';
              echo '<option value="'.esc_attr($gen).'" '.$selected.'>'.esc_html($gen).'</option>';
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>* Merital Status</td>
        <td colspan="3">
          <select name="marital_status" class="CVform-control" style="font-size:15px;width:200px;">
            <?php
            $m_status = ['Married', 'Unmarried'];
            foreach ($m_status as $ms) {
              $selected = ($user_application['marital_status'] ?? '') == $ms ? 'selected' : '';
              echo '<option value="'.esc_attr($ms).'" '.$selected.'>'.esc_html($ms).'</option>';
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>* Birth Date (dd/MM/yyyy)</td>
        <td colspan="3"><input type="date" name="birth_date" value="<?php echo esc_attr($user_application['birth_date'] ?? ''); ?>" /></td>
      </tr>

      <!-- Contact Details -->
      <tr><th colspan="4" class="section-title">CONTACT DETAILS</th></tr>
      <tr>
        <td> Place of Resident (City,Country)</td>
        <td colspan="3"><input type="text" name="resident_place" value="<?php echo esc_attr($user_application['place_of_resident'] ?? ''); ?>"  /></td>
      </tr>
      <tr>
        <td>* Permanent Contact No.</td>
        <td colspan="3"><input type="text" name="contact_no" value="<?php echo esc_attr($user_application['contact_no'] ?? ''); ?>" /></td>
      </tr>
      <tr>
        <td>* Permanent Email</td>
        <td colspan="3"><input type="email" name="email" value="<?php echo esc_attr($user_application['email'] ?? ''); ?>" /></td>
      </tr>

      <!-- Educational Qualification -->
      <tr><th colspan="4" class="section-title">EDUCATIONAL QUALIFICATION</th></tr>
      <tr>
        <td>Degree</td>
        <td colspan="3">
          <select name="degree" class="form-control" style="font-size:15px;font-weight:bold;width:400px;">
            <?php
            $degrees = ['H.School' => 'High School', 'Diploma' => 'Diploma', "Bachelor's Degree" => "Bachelor's Degree", 'High Diploma' => 'High Diploma', 'Master Degree' => 'Master Degree', 'PH.d' => 'PH.d'];
            foreach ($degrees as $key) {
              $selected = ($user_application['degree'] ?? '') == $key ? 'selected' : '';
              echo '<option value="'.esc_attr($key).'" '.$selected.'>'.$key.'</option>';
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>University</td>
        <td colspan="3">
          <select name="university" class="form-control" style="font-size:15px;width:400px;">
            <?php
            $uni = ['abc','ab123','xyz','xy123'];
            foreach ($uni as $un) {
              $selected = ($user_application['university'] ?? '') == $un ? 'selected' : '';
              echo '<option value="'.$un.'" '.$selected.'>'.$un.'</option>';
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>* Educational Category</td>
        <td colspan="3">
          <select name="education_category" class="form-control" style="font-size:15px;font-weight:bold;width:400px;">
          <?php
          $categories = get_terms(array(
              'taxonomy' => 'job-category',
              'hide_empty' => false,
          ));
          if (!empty($categories) && !is_wp_error($categories)) {
              foreach ($categories as $category) {
                  $selected = ($user_application['education_category'] ?? '') == $category->name ? 'selected' : '';
                  echo '<option value="'.esc_attr($category->name).'" '.$selected.'>'.esc_html($category->name).'</option>';
              }
          }
          ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>* Specialization</td>
        <td colspan="3">
          <select id="specialization" name="specialization">
            <option <?php selected($user_application['specialization'] ?? '', 'Administration hospitals'); ?>>Administration hospitals</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>* Job Title (Position)</td>
        <td colspan="3"><input type="text" name="job_title" value="<?php echo esc_attr($user_application['job_title'] ?? ''); ?>" /></td>
      </tr>

      <!-- Experience -->
      <tr><th colspan="4" class="section-title">EXPERIENCE</th></tr>
      <tr>
        <td>Total Experience Inside Jordan</td>
        <td>
          <select name="exp_inside">
            <option>Years</option>
            <?php
            for ($i = 0; $i <= 40; $i++) {
              $selected = ($exp_inside_years == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </td>
        <td colspan="2">
          <select name="exp_inside_months">
            <option value="">Months</option>
            <?php
            for ($i = 0; $i <= 11; $i++) {
              $selected = ($exp_inside_months == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Total Experience Outside Jordan</td>
        <td>
          <select name="exp_outside">
            <option>Years</option>
            <?php
            for ($i = 0; $i <= 40; $i++) {
              $selected = ($exp_outside_years == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </td>
        <td colspan="2">
          <select name="exp_outside_months">
            <option value="">Months</option>
            <?php
            for ($i = 0; $i <= 11; $i++) {
              $selected = ($exp_outside_months == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </td>
      </tr>

      <tr>
        <td>Languages</td>
        <td colspan="3">
          <?php
          $selected_languages = isset($user_application['language']) ? explode(',', $user_application['language']) : [];
          ?>
          <label style="display: inline-block; margin-right: 15px;">
            <input type="checkbox" name="language[]" value="English" <?php echo in_array('English', $selected_languages) ? 'checked' : ''; ?>>
            English
          </label>
          <label style="display: inline-block; margin-right: 15px;">
            <input type="checkbox" name="language[]" value="Urdu" <?php echo in_array('Urdu', $selected_languages) ? 'checked' : ''; ?>>
            Urdu
          </label>
        </td>
      </tr>

      <!-- Upload CV Row -->
      <tr>
        <td>* Upload CV</td>
        <td colspan="3" style="padding-left: 15px;display: flex;gap:30px" class="cvUpload">
          <a href="<?php echo esc_url($user_application['cv_path'] ?? '#'); ?>" target="_blank">
            <?php echo basename($user_application['cv_path'] ?? 'proposal.docx'); ?>
          </a>
          &nbsp;&nbsp;&nbsp;
          <a href="#" onclick="document.getElementById('cv_file').click(); return false;">Upload New</a>
          <input type="file" name="cv_file" id="cv_file" style="display: none;">
        </td>
      </tr>

      <!-- Upload Photo Row -->
    <tr>
      <td>* Upload Photo</td>
      <td colspan="3" style="padding-left: 15px;" class="cvUpload">
          <div style="display: flex; align-items: center; gap: 20px;">
              <?php if (!empty($user_application['photo'])): ?>
                  <a href="<?php echo esc_url($user_application['photo']); ?>" target="_blank">
                      <img src="<?php echo esc_url($user_application['photo']); ?>" alt="Photo" style="width: 100px; height: auto; border: 1px solid #ccc; padding: 2px;">
                  </a>
              <?php else: ?>
                  <span>No photo uploaded</span>
              <?php endif; ?>
              <div>
                  <a href="#" onclick="document.getElementById('photo_file').click(); return false;" style="display: inline-block; margin-bottom: 5px;">Upload New</a>
                  <input type="file" name="photo_file" id="photo_file" style="display: none;" onchange="showFileName(this)">
                  <div id="photo_filename" style="font-size: 14px; color: #333;"></div>
              </div>
          </div>
      </td>
  </tr>

  <script>
  function showFileName(input) {
      const fileName = input.files.length > 0 ? input.files[0].name : '';
      document.getElementById('photo_filename').textContent = fileName;
  }
  </script>


      <!-- User Account Details -->
      <tr><th colspan="4" class="section-title">USER ACCOUNT DETAILS</th></tr>
      <tr>
        <td>* Password</td>
        <td colspan="3"><input type="text" name="password" placeholder="Change Password (Optional)" value="<?php echo esc_attr($user_application['password']); ?>" /></td>
      </tr>

      <!-- Submit Buttons -->
      <tr>
        <td colspan="4" class="submit-btns">
          <button type="submit" name="update_application">Save</button>
          <button type="reset" style="background-color: #f7b620;">Reset</button>
        </td>
      </tr>
    </table>
</form>

</div>

<script>
function closeAlert(id) {
  document.getElementById(id).style.display = "none";
}
jQuery(document).ready(function($) {
  function updateSpecialization() {
    var degree = $('#MainContent_uc_cv_ddlDegree').val();
    var category = $('#MainContent_uc_cv_ddlJobCategory').val();
    
    var $specialization = $('#specialization');
    $specialization.empty(); // Clear previous options

    if (degree === 'H.School' && category === '5') {
      $specialization.append('<option value="1510">Material Manager</option>');
      $specialization.append('<option value="1511">waitrest</option>');
    } else if((degree === 'Diploma') && category === '5'){
      var options = `
        <option value="236">Administration hospitals</option>
        <option value="924">Analytical Toxicology</option>
        <option value="939">Bacteriology</option>
        <option value="630">Biology</option>
        <option value="1043">Biostatistics</option>
        <option value="1033">Cardiology Nurse Practitioner</option>
        <option value="700">Community Medicine</option>
        <option value="972">Cytopathology</option>
        <option value="909">Dental Hygiene</option>
        <option value="1010">Developmental Disabilities</option>
        <option value="981">Embryology</option>
        <option value="687">Emergency Medicine</option>
        <option value="1256">General Medicine</option>
        <option value="915">Health Education</option>
        <option value="679">Health Informatic</option>
        <option value="674">Healthcare &amp; Hospital Administration</option>
        <option value="967">Histopathology</option>
        <option value="954">Immunology</option>
        <option value="989">Low Vision</option>
        <option value="246">Medical analysis</option>
        <option value="929">Medical Microbiology</option>
        <option value="136">Medical records</option>
        <option value="1096">Medical tests</option>
        <option value="635">Microbiology</option>
        <option value="241">Midwife</option>
        <option value="1038">Midwifery</option>
        <option value="949">Mycology</option>
        <option value="994">Nuclear Medicine</option>
        <option value="31">nursing</option>
        <option value="1028">Nursing Administration</option>
        <option value="1023">Nutrition</option>
        <option value="891">Occupational Therapy</option>
        <option value="944">Parasitology</option>
        <option value="897">Physical Therapy</option>
        <option value="919">Public Health</option>
        <option value="118">Ray imaging</option>
        <option value="363">Specialist functional cure</option>
        <option value="887">Speech &amp; Audiology</option>
        <option value="934">Virology</option>`;
        $specialization.append(options);
    } else if((degree === 'H.Diploma' || degree === 'BSC') && category === '5'){
      var options=`<option value="839">Acupuncture</option>
      <option value="808">Addiction Medicine</option>
      <option value="237">Administration hospitals</option>
      <option value="925">Analytical Toxicology</option>
      <option value="880">Audiology</option>
      <option value="840">Audio-Vestibular Medicine</option>
      <option value="629">Biology</option>
      <option value="1044">Biostatistics</option>
      <option value="820">Body Imaging</option>
      <option value="827">Cardiac Imaging</option>
      <option value="747">Cardiac Surgery</option>
      <option value="1034">Cardiology Nurse Practitioner</option>
      <option value="874">Clinical Pharmacy</option>
      <option value="699">Community Medicine</option>
      <option value="768">Cosmetic Surgery</option>
      <option value="771">Craniofacial Surgery</option>
      <option value="971">Cytopathology</option>
      <option value="908">Dental Hygiene</option>
      <option value="869">Dental Implantology</option>
      <option value="208">dental surgery</option>
      <option value="10">Dental technician</option>
      <option value="169">dentist</option>
      <option value="11">Dermatologist</option>
      <option value="800">Diabetics Medicine</option>
      <option value="12">Doctor Gynaecologists</option>
      <option value="979">Embryology</option>
      <option value="688">Emergency Medicine</option>
      <option value="848">Endodontic</option>
      <option value="833">Endoscopic Surgery</option>
      <option value="696">Family Medicine</option>
      <option value="722">Forensic Medicine</option>
      <option value="1257">General Medicine</option>
      <option value="1377">General Surgery</option>
      <option value="805">Hair Implant</option>
      <option value="764">Hand Surgery</option>
      <option value="914">Health Education</option>
      <option value="680">Health Informatic</option>
      <option value="675">Healthcare &amp; Hospital Administration</option>
      <option value="828">Hematology</option>
      <option value="968">Histopathology</option>
      <option value="955">Immunology</option>
      <option value="1315">Implantology</option>
      <option value="990">Low Vision</option>
      <option value="1320">Lymopha Pathology</option>
      <option value="1321">Male infertility and Andrology</option>
      <option value="855">Maxillofacial Surgery</option>
      <option value="247">Medical analysis</option>
      <option value="962">Medical Genetics</option>
      <option value="928">Medical Microbiology</option>
      <option value="135">Medical records</option>
      <option value="1097">Medical tests</option>
      <option value="636">Microbiology</option>
      <option value="240">Midwife</option>
      <option value="1037">Midwifery</option>
      <option value="948">Mycology</option>
      <option value="756">Neurology</option>
      <option value="1322">Neuro-otology &amp;Otology</option>
      <option value="1015">nurce</option>
      <option value="13">nursing</option>
      <option value="1027">Nursing Administration</option>
      <option value="1024">Nutrition</option>
      <option value="692">OB/GYN</option>
      <option value="890">Occupational Therapy</option>
      <option value="735">Ophthalmology</option>
      <option value="879">Optometry</option>
      <option value="864">Oral Medicine</option>
      <option value="108">Orthodontics</option>
      <option value="729">Orthopedic Surgery</option>
      <option value="734">Otolaryngology</option>
      <option value="945">Parasitology</option>
      <option value="716">Pathology</option>
      <option value="759">Pediatric Neurology</option>
      <option value="775">Pediatric Plastic Surgery</option>
      <option value="856">Pedo-Dentistr</option>
      <option value="861">Periodontics</option>
      <option value="113">Pharmacy</option>
      <option value="1314">Pharmacy/Medicinal Chemistry &amp; Drug Analysis</option>
      <option value="724">Physical Medicine &amp; Rehabilitation</option>
      <option value="896">Physical Therapy</option>
      <option value="114">Physiotherapy</option>
      <option value="741">Plastic Surgery &amp; Reconstructive</option>
      <option value="900">Podiatry Technology</option>
      <option value="905">Prosthetics &amp; Orthotics</option>
      <option value="707">Psychiatry</option>
      <option value="918">Public Health</option>
      <option value="1509">PURCHASING MANGER</option>
      <option value="117">Ray imaging</option>
      <option value="819">Regional Anesthesia</option>
      <option value="847">Restorative Dentistry</option>
      <option value="812">Schizophrenia</option>
      <option value="362">Specialist functional cure</option>
      <option value="358">Specialist heard pronunciation</option>
      <option value="886">Speech &amp; Audiology</option>
      <option value="778">Spine Surgery</option>
      <option value="978">Stem Cells</option>
      <option value="999">Swallowing Disorders</option>
      <option value="958">Tissue Typing &amp; Organ Transplantation</option>
      <option value="752">Urology</option>
      <option value="148">veterinary doctor</option>
      <option value="935">Virology</option>
      <option value="1004">Voice Disorders</option>
      <option value="1018">Wound Care</option>`;
       $specialization.append(options);

    }

    else if(degree === 'Masters' && category === '5'){
      var options=`<option value="837">Acupuncture</option>
      <option value="809">Addiction Medicine</option>
      <option value="235">Administration hospitals</option>
      <option value="923">Analytical Toxicology</option>
      <option value="712">Anesthesia</option>
      <option value="788">Arthroplasty Surgery</option>
      <option value="882">Audiology</option>
      <option value="842">Audio-Vestibular Medicine</option>
      <option value="940">Bacteriology</option>
      <option value="631">Biology</option>
      <option value="1042">Biostatistics</option>
      <option value="822">Body Imaging</option>
      <option value="825">Cardiac Imaging</option>
      <option value="748">Cardiac Surgery</option>
      <option value="1032">Cardiology Nurse Practitioner</option>
      <option value="996">Catheter Lab Technology</option>
      <option value="873">Clinical Pharmacy</option>
      <option value="701">Community Medicine</option>
      <option value="767">Cosmetic Surgery</option>
      <option value="770">Craniofacial Surgery</option>
      <option value="973">Cytopathology</option>
      <option value="33">Dental anesthesia</option>
      <option value="910">Dental Hygiene</option>
      <option value="868">Dental Implantology</option>
      <option value="209">dental surgery</option>
      <option value="18">Dental technician</option>
      <option value="20">dentist</option>
      <option value="704">Dermatology</option>
      <option value="1011">Developmental Disabilities</option>
      <option value="801">Diabetics Medicine</option>
      <option value="19">Doctor Gynaecologists</option>
      <option value="982">Embryology</option>
      <option value="686">Emergency Medicine</option>
      <option value="850">Endodontic</option>
      <option value="834">Endoscopic Surgery</option>
      <option value="794">Facioplastic</option>
      <option value="695">Family Medicine</option>
      <option value="791">Foot &amp; Ankle Surgery</option>
      <option value="720">Forensic Medicine</option>
      <option value="1375">General Doctor</option>
      <option value="1255">General Medicine</option>
      <option value="53">General Surgery</option>
      <option value="127">Gynecologist</option>
      <option value="804">Hair Implant</option>
      <option value="763">Hand Surgery</option>
      <option value="913">Health Education</option>
      <option value="678">Health Informatic</option>
      <option value="673">Healthcare &amp; Hospital Administration</option>
      <option value="829">Hematology</option>
      <option value="797">Hepatobiliary Surgery</option>
      <option value="966">Histopathology</option>
      <option value="953">Immunology</option>
      <option value="985">In Vitro Fertilization</option>
      <option value="89">Internal Medicine</option>
      <option value="347">Leather and laser</option>
      <option value="988">Low Vision</option>
      <option value="853">Maxillofacial Surgery</option>
      <option value="245">Medical analysis</option>
      <option value="963">Medical Genetics</option>
      <option value="930">Medical Microbiology</option>
      <option value="1095">Medical tests</option>
      <option value="634">Microbiology</option>
      <option value="242">Midwife</option>
      <option value="1039">Midwifery</option>
      <option value="950">Mycology</option>
      <option value="1014">Neonatology</option>
      <option value="755">Neurology</option>
      <option value="744">Neurosurgery</option>
      <option value="782">Neurosurgery/Oncology</option>
      <option value="992">Nuclear Medicine</option>
      <option value="1029">Nursing Administration</option>
      <option value="1022">Nutrition</option>
      <option value="691">OB/GYN</option>
      <option value="892">Occupational Therapy</option>
      <option value="737">Ophthalmology</option>
      <option value="877">Optometry</option>
      <option value="865">Oral Medicine</option>
      <option value="109">Orthodontics</option>
      <option value="728">Orthopedic Surgery</option>
      <option value="733">Otolaryngology</option>
      <option value="943">Parasitology</option>
      <option value="717">Pathology</option>
      <option value="683">Pediatric Medicine</option>
      <option value="760">Pediatric Neurology</option>
      <option value="774">Pediatric Plastic Surgery</option>
      <option value="346">pediatrician</option>
      <option value="785">Pediatrics Orthopedic</option>
      <option value="857">Pedo-Dentistry</option>
      <option value="860">Periodontics</option>
      <option value="112">Pharmacy</option>
      <option value="725">Physical Medicine &amp; Rehabilitation</option>
      <option value="895">Physical Therapy</option>
      <option value="115">Physiotherapy</option>
      <option value="740">Plastic Surgery &amp; Reconstructive</option>
      <option value="901">Podiatry Technology</option>
      <option value="904">Prosthetics &amp; Orthotics</option>
      <option value="708">Psychiatry</option>
      <option value="920">Public Health</option>
      <option value="817">Regional Anesthesia</option>
      <option value="845">Restorative Dentistry</option>
      <option value="814">Schizophrenia</option>
      <option value="364">Specialist functional cure</option>
      <option value="360">Specialist heard pronunciation</option>
      <option value="351">Specialist teeth</option>
      <option value="885">Speech &amp; Audiology</option>
      <option value="1007">Speech Disorders</option>
      <option value="779">Spine Surgery</option>
      <option value="976">Stem Cells</option>
      <option value="1000">Swallowing Disorders</option>
      <option value="959">Tissue Typing &amp; Organ Transplantation</option>
      <option value="751">Urology</option>
      <option value="149">veterinary doctor</option>
      <option value="933">Virology</option>
      <option value="1003">Voice Disorders ا</option>
      <option value="1019">Wound Care</option>
      <option value="1467">السمع والنطق</option>
      <option value="1517">خبير استثمار</option>
      <option value="1520">خبير تحليل دراسات جدوى اقتصادية</option>`;
       $specialization.append(options);

    }
    else if((degree === 'PHD' || degree === 'H.School') && (category === '5')){
      var options=`<option value="836">Acupuncture</option>
      <option value="810">Addiction Medicine</option>
      <option value="234">Administration hospitals</option>
      <option value="922">Analytical Toxicology</option>
      <option value="713">Anesthesia</option>
      <option value="787">Arthroplasty Surgery</option>
      <option value="883">Audiology</option>
      <option value="843">Audio-Vestibular Medicine</option>
      <option value="941">Bacteriology</option>
      <option value="632">Biology</option>
      <option value="1041">Biostatistics</option>
      <option value="823">Body Imaging</option>
      <option value="824">Cardiac Imaging</option>
      <option value="749">Cardiac Surgery</option>
      <option value="1031">Cardiology Nurse Practitioner</option>
      <option value="995">Catheter Lab Technology</option>
      <option value="872">Clinical Pharmacy</option>
      <option value="702">Community Medicine</option>
      <option value="772">Craniofacial Surgery</option>
      <option value="974">Cytopathology</option>
      <option value="34">Dental anesthesia</option>
      <option value="911">Dental Hygiene</option>
      <option value="867">Dental Implantology</option>
      <option value="210">dental surgery</option>
      <option value="24">Dental technician</option>
      <option value="170">dentist</option>
      <option value="25">Dermatologist</option>
      <option value="703">Dermatology</option>
      <option value="1012">Developmental Disabilities</option>
      <option value="802">Diabetics Medicine</option>
      <option value="26">Doctor Gynaecologists</option>
      <option value="983">Embryology</option>
      <option value="685">Emergency Medicine</option>
      <option value="851">Endodontic</option>
      <option value="835">Endoscopic Surgery</option>
      <option value="793">Facioplastic</option>
      <option value="694">Family Medicine</option>
      <option value="792">Foot &amp; Ankle Surgery</option>
      <option value="719">Forensic Medicine</option>
      <option value="1254">General Medicine</option>
      <option value="52">General Surgery</option>
      <option value="126">Gynecologist</option>
      <option value="803">Hair Implant</option>
      <option value="762">Hand Surgery</option>
      <option value="912">Health Education</option>
      <option value="677">Health Informatic</option>
      <option value="672">Healthcare &amp; Hospital Administration</option>
      <option value="830">Hematology</option>
      <option value="798">Hepatobiliary Surgery</option>
      <option value="965">Histopathology</option>
      <option value="952">Immunology</option>
      <option value="986">In Vitro Fertilization</option>
      <option value="88">Internal Medicine</option>
      <option value="348">Leather and laser</option>
      <option value="987">Low Vision</option>
      <option value="852">Maxillofacial Surgery</option>
      <option value="244">Medical analysis</option>
      <option value="964">Medical Genetics</option>
      <option value="931">Medical Microbiology</option>
      <option value="1094">Medical tests</option>
      <option value="633">Microbiology</option>
      <option value="243">Midwife</option>
      <option value="1040">Midwifery</option>
      <option value="951">Mycology</option>
      <option value="1013">Neonatology</option>
      <option value="754">Neurology</option>
      <option value="743">Neurosurgery</option>
      <option value="781">Neurosurgery/Oncology</option>
      <option value="993">Nuclear Medicine</option>
      <option value="1030">Nursing Administration</option>
      <option value="1021">Nutrition</option>
      <option value="693">OB/GYN</option>
      <option value="893">Occupational Therapy</option>
      <option value="738">Ophthalmology</option>
      <option value="878">Optometry</option>
      <option value="866">Oral Medicine</option>
      <option value="110">Orthodontics</option>
      <option value="727">Orthopedic Surgery</option>
      <option value="732">Otolaryngology</option>
      <option value="942">Parasitology</option>
      <option value="718">Pathology</option>
      <option value="684">Pediatric Medicine</option>
      <option value="761">Pediatric Neurology</option>
      <option value="773">Pediatric Plastic Surgery</option>
      <option value="345">pediatrician</option>
      <option value="786">Pediatrics Orthopedic</option>
      <option value="858">Pedo-Dentistry</option>
      <option value="859">Periodontics</option>
      <option value="111">Pharmacy</option>
      <option value="726">Physical Medicine &amp; Rehabilitation</option>
      <option value="894">Physical Therapy</option>
      <option value="116">Physiotherapy</option>
      <option value="739">Plastic Surgery &amp; Reconstructive</option>
      <option value="902">Podiatry Technology</option>
      <option value="903">Prosthetics &amp; Orthotics</option>
      <option value="709">Psychiatry</option>
      <option value="921">Public Health</option>
      <option value="816">Regional Anesthesia</option>
      <option value="844">Restorative Dentistry</option>
      <option value="811">Schizophrenia</option>
      <option value="365">Specialist functional cure</option>
      <option value="361">Specialist heard pronunciation</option>
      <option value="352">Specialist teeth</option>
      <option value="884">Speech &amp; Audiology</option>
      <option value="1008">Speech Disorders</option>
      <option value="780">Spine Surgery</option>
      <option value="975">Stem Cells</option>
      <option value="1001">Swallowing Disorders</option>
      <option value="960">Tissue Typing &amp; Organ Transplantation</option>
      <option value="750">Urology</option>
      <option value="932">Virology</option>
      <option value="1002">Voice Disorders</option>
      <option value="1020">Wound Care</option>
      <option value="1518">خبير استثمار</option>
      <option value="1519">خبير تحليل دراسات جدوى اقتصادية</option>`;
       $specialization.append(options);


    }
    else if(degree === 'Diploma'  &&  category === '6'){
      var options=`<option value="1132">Banking and Finance العلوم المالية والمصرفية</option>`;
       $specialization.append(options);
     }
      else if(degree === 'BSC'  &&  category === '6'){
      var options=`<option value="1131">Banking and Finance</option>
      <option value="1307">Business Administration</option>
      <option value="1050">Finance</option>
      <option value="1390">Insurance</option>
      <option value="1308">Marketing</option>`;
       $specialization.append(options);
     }
      else if(degree === 'H.Diploma'  &&  category === '6'){
      var options=`<option value="1130">Banking and Finance</option>
     <option value="1135">Finance</option>
     <option value="1391">Insurance</option>
     <option value="1138">marketing</option>`;
       $specialization.append(options);
     }
      else if(degree === 'Masters'  &&  category === '6'){
      var options=`<option value="1133">Banking and Finance</option>
     <option value="1136">Finance</option>
     <option value="1139">marketing</option>
     <option value="1373">Public Relations العلاقات العامة</option>`;
       $specialization.append(options);
     }
      else if(degree === 'PHD'  &&  category === '6'){
      var options=`<option value="1134">Banking and Finance</option>
     <option value="1137">Finance</option>
     <option value="1140">marketing</option>`;
       $specialization.append(options);
     }
    // 
     
       else if(degree === 'H.School'  &&  (category === '7'||category==='8')){
       $specialization.append(options);
     }
      else if((degree === 'Diploma'||degree==='H.Diploma')  &&  category === '7'){
      var options=`<option value="1387">finance manager</option>`;
       $specialization.append(options);
     }
      else if(degree === 'BSC'  &&  category === '7'){
      var options=`<option value="1383">finance manager</option>
       <option value="1297">material engineering</option>
       <option value="1047">Restaurant Manager</option>
       <option value="1525">اخصائي ادارة مشاريع</option>
       <option value="1048">خبير استثمار</option>
       <option value="1521">خبير تحليل دراسات جدوى اقتصادية</option>
       <option value="1516">خبير تطوير اعمال</option>
       <option value="1522">خبير حاضنات ومراكز رياده اعمال</option>`;
       $specialization.append(options);
     }
      else if(degree === 'Masters'  &&  category === '7'){
      var options=`<option value="1384">finance manager</option>
      <option value="1471">infrastructural engineering</option>
      <option value="1392">Insurance</option>
      <option value="1526">اخصائي ادارة مشاريع</option>
      <option value="1514">خبير تطوير اعمال</option>
      <option value="1523">خبير حاضنات ومراكز رياده اعمال</option>
      <option value="1485">موظف استقبال</option>`;
       $specialization.append(options);
     }
      else if(degree === 'PHD'  &&  category === '7'){
      var options=`<option value="1385">finance manager</option>
      <option value="1527">اخصائي ادارة مشاريع</option>
      <option value="1515">خبير تطوير اعمال</option>
      <option value="1524">خبير حاضنات ومراكز رياده اعمال</option>`;
       $specialization.append(options);
     }
    // 
      else if(degree === 'Diploma'  &&  category === '8'){
      var options=`<option value="1343">Arabic Language</option>
      <option value="1347">Arabic Literature</option>
      <option value="1344">English Language</option>
      <option value="1348">English Literature</option>
      <option value="1346">Mathematics</option>
      <option value="1365">physical education</option>
      <option value="1345">Social Studies</option>
      <option value="1342">Special Education</option>
      <option value="1349">Translation-English</option>`;
       $specialization.append(options);
     }
      else if(degree === 'BSC'  &&  category === '8'){
      var options=`<option value="1354">Arabic Language</option>
        <option value="1362">Arabic Linguistics</option>
        <option value="1358">Arabic Literature</option>
        <option value="1359">Arabic Morphology and Syntax</option>
        <option value="1352">Art Education</option>
        <option value="1351">Civilization And History</option>
        <option value="1319">Curricula and Educational Supervision</option>
        <option value="1368">Curricula And Teaching Methods</option>
        <option value="1357">Educational Means and Curricula</option>
        <option value="1355">English Language</option>
        <option value="1360">English Literature</option>
        <option value="1382">Family Education</option>
        <option value="1350">History</option>
        <option value="1353">Kindergartens</option>
        <option value="1369">Language and Gramma</option>
        <option value="1356">Mathematics</option>
        <option value="1364">physical education</option>
        <option value="1472">school principle</option>
        <option value="1316">Science</option>
        <option value="1379">Social Studies</option>
        <option value="1381">Special Education</option>
        <option value="1380">teacher</option>
        <option value="1361">Translation-English</option>`;
       $specialization.append(options);
     }
      else if(degree === 'H.Diploma'  &&  category === '8'){
      var options=`<option value="1363">physical education</option>`;
       $specialization.append(options);
     }
      else if(degree === 'Masters'  &&  category === '8'){
      var options=`<option value="1323">Arabic Language</option>
                  <option value="1328">Arabic Literature</option>
                  <option value="1330">Educational and Vocational Guidance</option>
                  <option value="1324">English Language</option>
                  <option value="1329">English Literature</option>
                  <option value="1370">Language and Grammar</option>
                  <option value="1366">physical education</option>
                  <option value="1327">Postgraduate Arabic Language</option>
                  <option value="1326">Psychology</option>
                  <option value="1473">school principle</option>
                  <option value="1325">Social Studies</option>
                  <option value="1331">Special Education</option>`;
       $specialization.append(options);
     }
      else if(degree === 'PHD'  &&  category === '8'){
      var options=`<option value="1333">Arabic Language</option>
                  <option value="1338">Arabic Linguistics</option>
                  <option value="1334">English Language</option>
                  <option value="1339">English Literature</option>
                  <option value="1341">Geography</option>
                  <option value="1371">Language and Grammar</option>
                  <option value="1337">Mathematics</option>
                  <option value="1367">physical education</option>
                  <option value="1372">Postgraduate Arabic Language</option>
                  <option value="1474">school principle</option>
                  <option value="1336">Science</option>
                  <option value="1335">Social Studies</option>
                  <option value="1332">Special Education</option>
                  <option value="1340">Translation-English</option>
                  <option value="1464">علوم سياسية</option>`;
       $specialization.append(options);
     }


    else {
      $specialization.append('<option>No Specialization Available</option>');
    }
  }

  $('#MainContent_uc_cv_ddlDegree, #MainContent_uc_cv_ddlJobCategory').on('change', function() {
    updateSpecialization();
  });

  // Trigger on page load if needed:
  updateSpecialization();
});
</script>

<style>
    table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
    }
    td {
      padding: 5px;
      border: 1px solid #f7c171;
      vertical-align: middle;
    }
    th {
      background-color: #fff0cc;
      padding: 10px;
      text-align: left;
      font-size: 16px;
      color: #f7941d;
    }
    input, select {
      width: 95%;
      padding: 5px;
    }
    .section-title {
      background-color: #fff0cc;
      font-weight: bold;
      color: #f7941d;
      padding: 8px;
    }
    .header {
      background-color: #ffcc66;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      padding: 10px;
      border-radius: 8px;
    }
    .submit-btns {
      text-align: center;
      padding: 15px;
    }
    .submit-btns button {
      padding: 10px 25px;
      font-size: 16px;
      margin: 0 10px;
      background-color: #f7941d;
      border: none;
      color: white;
      border-radius: 5px;
    }
    .upload-btn {
      background: url('upload-icon.png') no-repeat left center;
      padding-left: 30px;
      background-size: 20px;
      border: none;
      color: #f7941d;
      font-weight: bold;
    }

/*  badge  */
   .custom-alert {
      padding: 15px 20px;
      border-radius: 4px;
      margin: 20px 0;
      position: relative;
      font-weight: 600;
    }
    .success-alert {
      background-color: #d4edda;
      color: #155724;
      border-left: 5px solid #28a745;
    }
    .error-alert {
      background-color: #f8d7da;
      color: #721c24;
      border-left: 5px solid #dc3545;
    }
    .custom-alert .close-btn {
      position: absolute;
      top: 5px;
      right: 10px;
      cursor: pointer;
      font-size: 20px;
    }
    .cvUpload a{
      font-size: 13px;
    }
    .button-container {
        display: flex;
        gap: 10px;
    }
    .nav-button {
        padding: 10px 20px;
        background-color: #fdbd5b;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }
    .nav-button:hover {
        background-color: #efb358;
    }
  </style>


<?php

get_footer();