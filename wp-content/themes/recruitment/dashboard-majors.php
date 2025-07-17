<?php
/*
Template Name: dashboard-majors
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


global $wpdb;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_name = $wpdb->prefix . 'majors';

    // ✅ Add Specialization
    if (isset($_POST['add_major'])) {
        $specialization = isset($_POST['specialization_input']) ? sanitize_text_field($_POST['specialization_input']) : '';

        if (!empty($specialization)) {
            // 🔍 Check if specialization already exists
            $existing = $wpdb->get_row(
                $wpdb->prepare("SELECT id, majors_name FROM $table_name WHERE LOWER(majors_name) = LOWER(%s)", $specialization)
            );

            if ($existing) {
                $_SESSION['error_message'] = 'This specialization already exists.';
            } else {
                $wpdb->insert(
                    $table_name,
                    ['majors_name' => $specialization],
                    ['%s']
                );
                $_SESSION['success_message'] = 'Specialization added successfully.';
            }

        } else {
            $_SESSION['error_message'] = 'Please enter a specialization name.';
        }

        wp_redirect(get_permalink());
        exit;
    }

    // ✅ Delete Specialization
    if (isset($_POST['delete_major'])) {
        $specialization = isset($_POST['specialization_input']) ? sanitize_text_field($_POST['specialization_input']) : '';

        if (!empty($specialization)) {
            // 🔍 Check if specialization exists
            $existing = $wpdb->get_row(
                $wpdb->prepare("SELECT id FROM $table_name WHERE LOWER(majors_name) = LOWER(%s)", $specialization)
            );

            if ($existing) {
                // 🗑️ Delete the specialization
                $wpdb->delete($table_name, ['id' => $existing->id], ['%d']);
                $_SESSION['success_message'] = 'Specialization deleted successfully.';
            } else {
                $_SESSION['error_message'] = 'Specialization not found.';
            }

        } else {
            $_SESSION['error_message'] = 'Please enter a specialization name to delete.';
        }

        wp_redirect(get_permalink());
        exit;
    }
}
get_header('dashboard'); // Loads header-dashboard.php

?>
<div style="max-width: 1000px; margin: auto;">

 <div class="header-majors-add">Majors</div>
  <div class="container-majors-add">
   
<form method="POST">
    <div class="form-section-majors-add">
    <div class="form-group-majors-add">
        <label>Select Degree</label>
        <select id="select-degree">
           <option value="1">High School</option>
           <option value="2">Diploma</option>
           <option value="3">Bachelor's Degree</option>
           <option value="4">High Diploma</option>
           <option value="5">Master Degree</option>
           <option value="6">PH.d</option>
        </select>
     </div>
      <div class="form-group-majors-add">
        <label>Educational Category</label>
        <select>
       <option value="1">Medical and health care sector</option>
       <option value="2">Sales &amp; Marketing and advertisement</option>
       <option value="3">Accounting and banking sector</option>
       <option value="4">Education Sector</option>
       <option value="5">Engineering Sector</option>
       <option value="6">HOTEL MANAGEMENT SECTOR</option>
       <option value="7">Information Technology and Computer science sector</option>
       <option value="8">Management Sector</option>
       <option value="9">Other sectors</option>
        </select>
      </div>
     <div class="form-group-majors-add">
    <label>Select Specialization</label>
      <select id="select-specialization">
           <option value="">-- Select Specialization --</option>
      </select>
    </div>

      <div class="form-group-majors-add">
  <label>Specialization</label>
 <input type="text" id="specialization-input" name="specialization_input" />
 <input type="hidden" id="specialization-id" name="specialization_id" value="0">

</div>

      <div class="buttons-majors-add">
        <button type="submit" name="add_major" class="btn-add-majors-add">Add</button>
       <button type="submit" name="delete_major" class="btn-delete-majors-add">Delete</button>
        <button class="btn-clear-majors-add">Clear</button>
      </div>
    </div>
</form>

    <div class="major-details-majors-add">
      <h3><a href="#">Major Name Details :-</a></h3>
      <table class="table-majors-add">
        <thead>
          <tr>
            <th>Major Name</th>
            <th>Serial No</th>
          </tr>
        </thead>
        <tbody>
  <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'majors';

    // Fetch all majors from the database
    $majors = $wpdb->get_results("SELECT id, majors_name FROM $table_name ORDER BY id DESC");

    if ($majors) {
        foreach ($majors as $index => $major) {
            echo '<tr>';
            echo '<td>' . esc_html($major->majors_name) . '</td>';
            echo '<td>' . 0 . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="2">No majors found.</td></tr>';
    }
  ?>
</tbody>
      </table>
    </div>
  </div>
</div>   
<script>
  const ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php get_footer(); ?>
<script>
  const specializationOptions = {
    "1": ["Material Manager", "new"], // High School
    "2": [
      "Administration hospitals",
      "Analytical Toxicology",
      "Bacteriology",
      "Biology",
      "Biostatistics",
      "Cardiology Nurse Practitioner",
      "Community Medicine",
      "Cytopathology",
      "Dental Hygiene",
      "Developmental Disabilities",
      "Embryology",
      "Emergency Medicine",
      "General Medicine",
      "Health Education",
      "Health Informatic",
      "Healthcare & Hospital Administration",
      "Histopathology",
      "Immunology",
      "Low Vision",
      "Medical analysis",
      "Medical Microbiology",
      "Medical records",
      "Medical tests",
      "Microbiology",
      "Midwife",
      "Midwifery",
      "Mycology",
      "Nuclear Medicine",
      "nursing",
      "Nursing Administration",
      "Nutrition",
      "Occupational Therapy",
      "Parasitology",
      "Physical Therapy",
      "Public Health",
      "Ray imaging",
      "Specialist functional cure",
      "Speech & Audiology",
      "Virology"
    ],
    "3": [
  "Acupuncture",
  "Addiction Medicine",
  "Administration hospitals",
  "Anesthesia",
  "Arthroplasty Surgery",
  "Audiology",
  "Audio-Vestibular Medicine",
  "Bacteriology",
  "Biology",
  "Biostatistics",
  "Body Imaging",
  "Cardiac Imaging",
  "Cardiac Surgery",
  "Cardiology Nurse Practitioner",
  "Catheter Lab Technology",
  "Clinical Pharmacy",
  "Community Medicine",
  "Cosmetic Surgeryة",
  "Craniofacial Surgery",
  "Cytopathology",
  "Dental Hygiene",
  "Dental Implantology",
  "Dental technician",
  "dentist",
  "Dermatologist",
  "Dermatology",
  "Developmental Disabilitiesو",
  "Diabetics Medicine",
  "Doctor Gynaecologists",
  "Embryology",
  "Endodontic",
  "Endoscopic Surgery",
  "Facioplastic",
  "Family Medicineة",
  "Foot & Ankle Surgery",
  "Forensic Medicine",
  "General Medicine",
  "Hair Implant",
  "Hand Surgery",
  "Health Education",
  "Health Informatic",
  "Healthcare & Hospital Administration",
  "Hematology",
  "Hepatobiliary Surgery",
  "Histopathology",
  "Immunology",
  "In Vitro Fertilization",
  "Low Vision",
  "Maxillofacial Surgery",
  "Medical analysis",
  "Medical Genetics",
  "Medical Microbiology",
  "Medical tests",
  "Microbiology",
  "Midwife",
  "Midwifery",
  "Mycology",
  "Neonatology",
  "Neurology",
  "Neurosurgery",
  "Neurosurgery/Oncology",
  "nursing",
  "Nursing Administration",
  "Nutrition",
  "OB/GYN",
  "Occupational Therapy",
  "Ophthalmology",
  "Optometry",
  "Oral Medicine",
  "Orthopedic Surger",
  "Pathology",
  "Pediatric Medicine",
  "Pediatric Neurology",
  "Pediatric Plastic Surgery",
  "Pediatrics Orthopedic",
  "Periodontics",
  "Pharmacy",
  "Physical Medicine & Rehabilitation",
  "Physical Therapy",
  "Plastic Surgery & Reconstructive",
  "Podiatry Technology",
  "Prosthetics & Orthotics",
  "Psychiatry",
  "Public Health",
  "Regional Anesthesia",
  "Restorative Dentistry",
  "Schizophrenia",
  "Specialist heard pronunciation",
  "Speech & Audiology",
  "Speech Disorders",
  "Spine Surgery",
  "Stem Cells",
  "Swallowing Disorders",
  "Tissue Typing & Organ Transplantation",
  "Urology",
  "Virology",
  "Voice Disorders",
  "Wound Care"
],
"4": [
  "Acupuncture",
  "Addiction Medicine",
  "Administration hospitals",
  "Anesthesia",
  "Arthroplasty Surgery",
  "Audiology",
  "Audio-Vestibular Medicine",
  "Bacteriology",
  "Biology",
  "Biostatistics",
  "Body Imaging",
  "Cardiac Imaging",
  "Cardiac Surgery",
  "Cardiology Nurse Practitioner",
  "Catheter Lab Technology",
  "Clinical Pharmacy",
  "Community Medicine",
  "Cosmetic Surgeryة",
  "Craniofacial Surgery",
  "Cytopathology",
  "Dental Hygiene",
  "Dental Implantology",
  "Dental technician",
  "dentist",
  "Dermatologist",
  "Dermatology",
  "Developmental Disabilitiesو",
  "Diabetics Medicine",
  "Doctor Gynaecologists",
  "Embryology",
  "Endodontic",
  "Endoscopic Surgery",
  "Facioplastic",
  "Family Medicineة",
  "Foot & Ankle Surgery",
  "Forensic Medicine",
  "General Medicine",
  "Hair Implant",
  "Hand Surgery",
  "Health Education",
  "Health Informatic",
  "Healthcare & Hospital Administration",
  "Hematology",
  "Hepatobiliary Surgery",
  "Histopathology",
  "Immunology",
  "In Vitro Fertilization",
  "Low Vision",
  "Maxillofacial Surgery",
  "Medical analysis",
  "Medical Genetics",
  "Medical Microbiology",
  "Medical tests",
  "Microbiology",
  "Midwife",
  "Midwifery",
  "Mycology",
  "Neonatology",
  "Neurology",
  "Neurosurgery",
  "Neurosurgery/Oncology",
  "nursing",
  "Nursing Administration",
  "Nutrition",
  "OB/GYN",
  "Occupational Therapy",
  "Ophthalmology",
  "Optometry",
  "Oral Medicine",
  "Orthopedic Surger",
  "Pathology",
  "Pediatric Medicine",
  "Pediatric Neurology",
  "Pediatric Plastic Surgery",
  "Pediatrics Orthopedic",
  "Periodontics",
  "Pharmacy",
  "Physical Medicine & Rehabilitation",
  "Physical Therapy",
  "Plastic Surgery & Reconstructive",
  "Podiatry Technology",
  "Prosthetics & Orthotics",
  "Psychiatry",
  "Public Health",
  "Regional Anesthesia",
  "Restorative Dentistry",
  "Schizophrenia",
  "Specialist heard pronunciation",
  "Speech & Audiology",
  "Speech Disorders",
  "Spine Surgery",
  "Stem Cells",
  "Swallowing Disorders",
  "Tissue Typing & Organ Transplantation",
  "Urology",
  "Virology",
  "Voice Disorders",
  "Wound Care"
],
"5": [
  "Acupuncture",
  "Addiction Medicine",
  "Administration hospitals",
  "Analytical Toxicology",
  "Anesthesia",
  "Arthroplasty Surgery",
  "Audiology",
  "Audio-Vestibular Medicine",
  "Bacteriology",
  "Biostatistics",
  "Body Imaging",
  "Cardiac Imaging",
  "Cardiac Surgery",
  "Cardiology Nurse Practitioner",
  "Catheter Lab Technology",
  "Clinical Pharmacy",
  "Community Medicine",
  "Cosmetic Surgery",
  "Craniofacial Surgery",
  "Cytopathology",
  "Dental anesthesia",
  "Dental Hygiene",
  "Dental Implantology",
  "dental surgery",
  "Dental technician",
  "dentist",
  "Dermatology",
  "Developmental Disabilities",
  "Diabetics Medicine",
  "Doctor Gynaecologists",
  "Embryology",
  "Emergency Medicine",
  "Endodontic",
  "Endoscopic Surgery",
  "Facioplastic",
  "Family Medicine",
  "Foot & Ankle Surgery",
  "Forensic Medicine",
  "General Doctor",
  "General Medicine",
  "General Surgery",
  "Gynecologist",
  "Hair Implant",
  "Hand Surgery",
  "Health Education",
  "Health Informatic",
  "Healthcare & Hospital Administration",
  "Hematology",
  "Hepatobiliary Surgery",
  "Histopathology",
  "Immunology",
  "In Vitro Fertilization",
  "Internal Medicine",
  "Leather and laser",
  "Low Vision",
  "Maxillofacial Surgery",
  "Medical analysis",
  "Medical Genetics",
  "Medical Microbiology",
  "Medical tests",
  "Microbiology",
  "Midwife",
  "Midwifery",
  "Mycology",
  "Neonatology",
  "Neurology",
  "Neurosurgery",
  "Neurosurgery/Oncology",
  "Nuclear Medicine",
  "Nursing Administration",
  "Nutrition",
  "OB/GYN",
  "Occupational Therapy",
  "Ophthalmology",
  "Optometry",
  "Oral Medicine",
  "Orthodontics",
  "Orthopedic Surgery",
  "Otolaryngology",
  "Parasitology",
  "Pathology",
  "Pediatric Medicine",
  "Pediatric Neurology",
  "Pediatric Plastic Surgery",
  "pediatrician",
  "Pediatrics Orthopedic",
  "Pedo-Dentistry",
  "Periodontics",
  "Pharmacy",
  "Physical Medicine & Rehabilitation",
  "Physical Therapy",
  "Physiotherapy",
  "Plastic Surgery & Reconstructive",
  "Podiatry Technology",
  "Prosthetics & Orthotics",
  "Psychiatry",
  "Public Health",
  "Regional Anesthesia",
  "Restorative Dentistry",
  "Schizophrenia",
  "Specialist functional cure",
  "Specialist heard pronunciation",
  "Specialist teeth",
  "Speech & Audiology",
  "Speech Disorders",
  "Spine Surgery",
  "Stem Cells",
  "Swallowing Disorders",
  "Tissue Typing & Organ Transplantation",
  "Urology",
  "veterinary doctor",
  "Virology",
  "Voice Disorders",
  "Wound Care",
  "السمع والنطق",
  "خبير استثمار",
  "خبير تحليل دراسات جدوى اقتصادية"
],
"6": [
  "Acupuncture",
  "Addiction Medicine",
  "Administration hospitals",
  "Analytical Toxicology",
  "Anesthesia",
  "Arthroplasty Surgery",
  "Audiology",
  "Audio-Vestibular Medicine",
  "Bacteriology",
  "Biology",
  "Biostatistics",
  "Body Imaging",
  "Cardiac Imaging",
  "Cardiac Surgery",
  "Cardiology Nurse Practitioner",
  "Catheter Lab Technology",
  "Clinical Pharmacy",
  "Community Medicine",
  "Craniofacial Surgery",
  "Cytopathology",
  "Dental anesthesia",
  "Dental Hygiene",
  "Dental Implantology",
  "dental surgery",
  "Dental technician",
  "dentist",
  "Dermatologist",
  "Dermatology",
  "Developmental Disabilities",
  "Diabetics Medicine",
  "Doctor Gynaecologists",
  "Embryology",
  "Emergency Medicine",
  "Endodontic",
  "Endoscopic Surgery",
  "Facioplastic",
  "Family Medicine",
  "Foot & Ankle Surgery",
  "Forensic Medicine",
  "General Medicine",
  "General Surgery",
  "Gynecologist",
  "Hair Implant",
  "Hand Surgery",
  "Health Education",
  "Health Informatic",
  "Healthcare & Hospital Administration",
  "Hematology",
  "Hepatobiliary Surgery",
  "Histopathology",
  "Immunology",
  "In Vitro Fertilization",
  "Internal Medicine",
  "Leather and laser",
  "Low Vision",
  "Maxillofacial Surgery",
  "Medical analysis",
  "Medical Genetics",
  "Medical Microbiology",
  "Medical tests",
  "Microbiology",
  "Midwife",
  "Midwifery",
  "Mycology",
  "Neonatology",
  "Neurology",
  "Neurosurgery",
  "Neurosurgery/Oncology",
  "Nuclear Medicine",
  "Nursing Administration",
  "Nutrition",
  "OB/GYN",
  "Occupational Therapy",
  "Ophthalmology",
  "Optometry",
  "Oral Medicine",
  "Orthodontics",
  "Orthopedic Surgery",
  "Otolaryngology",
  "Parasitology",
  "Pathology",
  "Pediatric Medicine",
  "Pediatric Neurology",
  "Pediatric Plastic Surgery",
  "pediatrician",
  "Pediatrics Orthopedic",
  "Pedo-Dentistry",
  "Periodontics",
  "Pharmacy",
  "Physical Medicine & Rehabilitation",
  "Physical Therapy",
  "Physiotherapy",
  "Plastic Surgery & Reconstructive",
  "Podiatry Technology",
  "Prosthetics & Orthotics",
  "Psychiatry",
  "Public Health",
  "Regional Anesthesia",
  "Restorative Dentistry",
  "Schizophrenia",
  "Specialist functional cure",
  "Specialist heard pronunciation",
  "Specialist teeth",
  "Speech & Audiology",
  "Speech Disorders",
  "Spine Surgery",
  "Stem Cells",
  "Swallowing Disorders",
  "Tissue Typing & Organ Transplantation",
  "Urology",
  "Virology",
  "Voice Disorders",
  "Wound Care",
  "خبير استثمار",
  "خبير تحليل دراسات جدوى اقتصادية"
]
  };

  document.addEventListener("DOMContentLoaded", function () {
  const degreeSelect = document.getElementById('select-degree');
  const specializationSelect = document.getElementById('select-specialization');
  const specializationInput = document.querySelector('.form-group-majors-add input[type="text"]');

  degreeSelect.addEventListener('change', function () {
    const selectedValue = this.value;
    specializationSelect.innerHTML = '<option value="">-- Select Specialization --</option>';
    if (specializationOptions[selectedValue]) {
      specializationOptions[selectedValue].forEach(function (spec) {
        const option = document.createElement('option');
        option.value = spec;
        option.textContent = spec;
        specializationSelect.appendChild(option);
      });
    }
  });

  specializationSelect.addEventListener('change', function () {
    specializationInput.value = this.value;
  });

  degreeSelect.dispatchEvent(new Event('change'));
});
// specializationSelect.addEventListener('change', function () {
//     specializationInput.value = this.value;
//     const selectedOption = this.options[this.selectedIndex];
//     specializationIdInput.value = selectedOption.dataset.id || 0;
// });
</script>


