<?php
/**
 * Recruitment functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Recruitment
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function recruitment_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Recruitment, use a find and replace
		* to change 'recruitment' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'recruitment', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
    array(
        'Primary'     => esc_html__( 'Primary', 'recruitment' ),
        'admin-menu'  => esc_html__( 'Admin Menu', 'recruitment' ),
    )
);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'recruitment_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'recruitment_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function recruitment_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'recruitment_content_width', 640 );
}
add_action( 'after_setup_theme', 'recruitment_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function recruitment_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'recruitment' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'recruitment' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'recruitment_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function recruitment_scripts() {
	wp_enqueue_style( 'recruitment-style', get_stylesheet_uri(), array(), null );
	wp_style_add_data( 'recruitment-style', 'rtl', 'replace' );

	wp_enqueue_script( 'recruitment-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.slim.min.js', array(), '3.6.0', true);
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/jqueryCustom.js', array('jquery'), '1.0.0', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'recruitment_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



// post count functions

function set_job_post_views($postID) {
    $count_key = 'post_views_count';
    $count     = get_post_meta($postID, $count_key, true);

    if ($count === '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function track_job_views($post_id) {
    if (is_singular('jobs')) {
        set_job_post_views($post_id);
    }
}
add_action('wp_head', function() {
    if (is_singular('jobs')) {
        global $post;
        if ($post) {
            set_job_post_views($post->ID);
        }
    }
});

add_action('wp_logout', 'custom_logout_redirect');
function custom_logout_redirect() {

    wp_redirect(site_url().'/entering-system/');
    exit;
}


function create_custom_job_application_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      first_name varchar(255) NOT NULL,
      family_name varchar(255) NOT NULL,
      nationality varchar(100),
      birth_date date,
      contact_no varchar(100),
      email varchar(255),
      degree varchar(100),
      education_category varchar(150),
      specialization varchar(150),
      job_title varchar(150),
      exp_inside varchar(50),
      exp_outside varchar(50),
      password varchar(255),
      cv_path varchar(255),
      created_at datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_custom_job_application_table');

add_action('init', function () {
    if (!session_id()) {
        session_start();
    }
}, 1);



function get_user_data_by_id_callback() {
    global $wpdb;

    $user_id = intval($_POST['user_id']);
    echo "User ID received: " . $user_id . "<br>";

    $table = $wpdb->prefix . 'entering_system';

    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT user_name, role, password FROM $table WHERE id = %d",
        $user_id
    ));

    if ($user) {
        echo "User Name: " . $user->user_name . "<br>";
        echo "Role: " . $user->role . "<br>";
        echo "Password: " . $user->password . "<br>";
    } else {
        echo "User not found.<br>";
    }

    wp_die(); // Always end AJAX handler
}
add_action('wp_ajax_nopriv_get_user_data_by_id', 'get_user_data_by_id_callback');

add_action('wp_ajax_add_major', 'handle_add_major');
add_action('wp_ajax_nopriv_add_major', 'handle_add_major'); // Optional, only needed if not logged in

function handle_add_major() {
    global $wpdb;

    // 1. Check input
    if (!isset($_POST['major_name']) || empty(trim($_POST['major_name']))) {
        wp_send_json_error(['message' => 'Specialization name is required.']);
    }

    $major = sanitize_text_field($_POST['major_name']);
    $table = $wpdb->prefix . 'majors';

    // 2. Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        wp_send_json_error(['message' => "Table '$table' does not exist."]);
    }

    // 3. Prevent duplicates
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE major_name = %s", $major));
    if ($exists) {
        wp_send_json_error(['message' => 'Specialization already exists.']);
    }

    // 4. Insert
    $inserted = $wpdb->insert($table, ['major_name' => $major]);

    if ($inserted) {
        wp_send_json_success(['serial_no' => $wpdb->insert_id]);
    } else {
        wp_send_json_error(['message' => 'Failed to insert into database.']);
    }

    wp_die();
}

add_action('wp_ajax_search_cv', 'handle_cv_search');
function handle_cv_search() {
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';

    $where = [];
    $values = [];

    // Define the allowed fields and map to DB column
    $allowed_fields = [
        'educational_category' => 'education_category',
        'bachelor_degree'      => 'degree',
        'phd_degree'           => 'degree',
        'high_diploma'         => 'degree',
        'master_in'            => 'degree',
        'diploma_in'           => 'degree',
        'high_school'          => 'degree',
        'group'                => 'education_category',
        'by_serial'            => 'id',
        'by_institute'         => 'university',
        'by_average'           => 'degree',
        'mobile'               => 'contact_no',
        'gender'               => 'gender',
        'search_keys'          => 'specialization',
        'entry_date_from'      => 'created_at',
        'entry_date_to'        => 'created_at'
    ];

    foreach ($allowed_fields as $form_key => $column_name) {
        if (!empty($_POST[$form_key])) {
            if (in_array($form_key, ['entry_date_from', 'entry_date_to'])) continue; // handle below
            $where[] = "$column_name = %s";
            $values[] = sanitize_text_field($_POST[$form_key]);
        }
    }

    // Handle date range
    if (!empty($_POST['entry_date_from']) && !empty($_POST['entry_date_to'])) {
        $where[] = "created_at BETWEEN %s AND %s";
        $values[] = sanitize_text_field($_POST['entry_date_from']);
        $values[] = sanitize_text_field($_POST['entry_date_to']);
    }

    $sql = "SELECT * FROM $table";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $results = $wpdb->get_results($wpdb->prepare($sql, $values), ARRAY_A);
    wp_send_json($results);
}



