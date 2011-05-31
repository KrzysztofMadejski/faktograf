<?php

locate_template(array('inc/roots-activation.php'), true, true);	// activation
locate_template(array('inc/roots-admin.php'), true, true);		// admin additions/mods
locate_template(array('inc/roots-options.php'), true, true);	// theme options menu
locate_template(array('inc/roots-cleanup.php'), true, true);	// code cleanup/removal
locate_template(array('inc/roots-htaccess.php'), true, true);	// h5bp htaccess
locate_template(array('inc/roots-hooks.php'), true, true);		// hooks
locate_template(array('inc/roots-actions.php'), true, true);	// actions
locate_template(array('inc/roots-widgets.php'), true, true);	// widgets
locate_template(array('inc/roots-custom.php'), true, true);		// custom functions

// get active theme directory name
// this allows you to rename the theme directory without breaking anything
$theme_name = next(explode('/themes/', get_template_directory()));

// set the value of the main container class depending on the selected grid framework
$options = roots_get_theme_options();
$roots_css_framework = $options['css_grid_framework'];
if (!defined('roots_container_class')) {
	switch ($roots_css_framework) {
		case 'blueprint':	define('roots_container_class', 'span-24');			break;
		case '960gs_12':	define('roots_container_class', 'container_12');	break;
		case '960gs_16':	define('roots_container_class', 'container_16');    break;
		case '960gs_24':	define('roots_container_class', 'container_24');    break;
		case '1140':		define('roots_container_class', 'container');       break;
		default:			define('roots_container_class', '');				break;
	}
}

function get_roots_stylesheets() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	
	$template_uri = get_template_directory_uri();
	$styles = '';

	if ($roots_css_framework === 'blueprint') {
		$styles .= "<link rel=\"stylesheet\" href=\"$template_uri/css/blueprint/screen.css\">\n";
	} elseif ($roots_css_framework === '960gs_12' || $roots_css_framework === '960gs_16') {
		$styles .= "<link rel=\"stylesheet\" href=\"$template_uri/css/960/reset.css\">\n";
		$styles .= "\t<link rel=\"stylesheet\" href=\"$template_uri/css/960/text.css\">\n";
		$styles .= "\t<link rel=\"stylesheet\" href=\"$template_uri/css/960/960.css\">\n";
	} elseif ($roots_css_framework === '960gs_24') {
		$styles .= "<link rel=\"stylesheet\" href=\"$template_uri/css/960/reset.css\">\n";
		$styles .= "\t<link rel=\"stylesheet\" href=\"$template_uri/css/960/text.css\">\n";
		$styles .= "\t<link rel=\"stylesheet\" href=\"$template_uri/css/960/960_24_col.css\">\n";
	} elseif ($roots_css_framework === '1140') {
		$styles .= "<link rel=\"stylesheet\" href=\"$template_uri/css/1140/1140.css\">\n";
	}

	if (class_exists('RGForms')) {
		$styles .= "\t<link rel=\"stylesheet\" href=\"" . plugins_url(). "/gravityforms/css/forms.css\">\n";
	}

	$styles .= "\t<link rel=\"stylesheet\" href=\"$template_uri/css/style.css\">\n";

	if ($roots_css_framework === 'blueprint') {
		$styles .= "\t<!--[if lt IE 8]><link rel=\"stylesheet\" href=\"$template_uri/css/blueprint/ie.css\"><![endif]-->\n";
	} elseif ($roots_css_framework === '1140') {
		$styles .= "\t<!--[if lt IE 8]><link rel=\"stylesheet\" href=\"$template_uri/css/1140/ie.css\"><![endif]-->\n";
	}

	return $styles;
}
	
// set the maximum 'Large' image width to the maximum grid width
if (!isset($content_width)) {
	switch ($roots_css_framework) {
	    case 'blueprint':	$content_width = 950;	break;
	    case '960gs_12':	$content_width = 940;	break;
	    case '960gs_16':	$content_width = 940;	break;
	    case '960gs_24':	$content_width = 940;	break;
	    case '1140':		$content_width = 1140;	break;
	    default:			$content_width = 950;	break;
	}
}

// tell the TinyMCE editor to use editor-style.css
// if you have issues with getting the editor to show your changes then use the following line:
// add_editor_style('editor-style.css?' . time());
add_editor_style('editor-style.css');

add_theme_support('post-thumbnails');

// http://codex.wordpress.org/Post_Formats
// add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

add_theme_support('menus');
register_nav_menus(
	array(
		'primary_navigation' => 'Primary Navigation',
		'utility_navigation' => 'Utility Navigation'
	)
);

// remove container from menus
function roots_nav_menu_args($args = '') {
	$args['container'] = false;
	return $args;
}

add_filter('wp_nav_menu_args', 'roots_nav_menu_args');

// create widget areas: sidebar, footer
$sidebars = array('Sidebar', 'Footer');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
		'after_widget' => '</div></article>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

// add to robots.txt
// http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
add_action('do_robots', 'roots_robots');

function roots_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}

?>