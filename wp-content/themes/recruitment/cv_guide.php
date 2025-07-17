<?php
/*
Template Name: CV Guide Template
*/

get_header();

if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}
?>

<div class="gulf-container">
	<?php if(!empty($breadcrumb)){ ?>
    <div class="gulf-title"><?php echo $breadcrumb; ?></div>
<?php } ?>

 <h1>Please follow below steps for application form filling.(Apply for Job) :-</h1>
    
    <table>
        <thead>
            <tr>
                <th>Steps</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Select the language.</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Your CV should be ready on the desktop.</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Write the Fields marked with a star or full mobilization (optional).</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Upload CV in Arabic.</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Upload your image personal.</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Write the password.</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Click on save.</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Show message the CV has been saved successfully.</td>
            </tr>
        </tbody>
    </table>
    <style type="text/css">
        h1 {
            color: #333;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>


</div>


<?php
get_footer();