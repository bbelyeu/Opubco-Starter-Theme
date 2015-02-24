<?php
/* 
***	Yo, welcome to functions.php.  Here you can put custom code.
*** If you are working locally, make sure to add this into your wp-config.php file

if ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) && ( '127.0.0.1' == $_SERVER['REMOTE_ADDR'] || '::1' == $_SERVER['REMOTE_ADDR'] ) ) {
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_DISPLAY', true );
	define( 'SCRIPT_DEBUG', true );
	define( 'CONCATENATE_SCRIPTS', false );
}
Recommended Plugins:
Regenerate Thumbnails - https://wordpress.org/plugins/regenerate-thumbnails/
Testimonials - https://wordpress.org/plugins/testimonialslider/
Slider - Soliloquy (have license)
Forms - Gravity Forms (have license)
Form Captcha - Don't use.  Enable honeypot spam protection and use Akismet in Gravity Forms
Backups, migration, and database search/replace - BackupBuddy (have license)
SEO and Sitemaps - https://wordpress.org/plugins/wordpress-seo/
Google Analytics - https://wordpress.org/plugins/google-analytics-for-wordpress/
Change Avatar - https://wordpress.org/plugins/metronet-profile-picture/
Spam Protection - (Requires WordPress.com account) - https://wordpress.org/plugins/akismet/
Widgets - https://wordpress.org/plugins/conditional-widgets/
Breadcrumbs - https://wordpress.org/plugins/breadcrumb-navxt/
Redirects - https://wordpress.org/plugins/quick-pagepost-redirect-plugin/
*/

/* Debugging function */
if ( !function_exists( 'wp_print_r' ) ) {
	function wp_print_r( $args, $die = true ) {
		$echo = '<pre>' . print_r( $args, true ) . '</pre>';
		if ( $die ) die( $echo );
		else echo $echo;
	}
}

if (!isset($content_width))
{
    $content_width = 900;
}

function opubco_setup() {
	// Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('slider', 1000, 350, true); // Slider Image

 
    //NEW HTML5 Galleries

    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('opubco', get_template_directory() . '/languages');
	
} //end opubco_setup
add_action( 'after_setup_theme', 'opubco_setup' );

/*------------------------------------*\
	Functions
\*------------------------------------*/

// OPUBCO Theme navigation
function opubco_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul itemscope=itemscope itemtype="http://www.schema.org/SiteNavigationElement">%3$s</ul>',
		'depth'           => 0,
		'walker'          => new opubco_walker_nav_menu
		)
	);
}


// Custom Walker to add schema markup to the li and a element.

class opubco_walker_nav_menu extends Walker_Nav_Menu {
	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filter the CSS class(es) applied to a menu item's <li>.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's <li>.
		 *
		 * @since 3.0.1
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string $menu_id The ID that is applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li itemprop=name' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item The current menu item.
		 * @param array  $args An array of wp_nav_menu() arguments.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		$item_output .= '<a itemprop=url'. $attributes .'>';
		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes $args->before, the opening <a>,
		 * the menu item's title, the closing </a>, and $args->after. Currently, there is
		 * no filter for modifying the opening and closing <li> for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

} // Walker_Nav_Menu


// Load OPUBCO Theme scripts (header.php)
function opubco_header_scripts()
{
    if (!is_admin()) {

    	wp_enqueue_script('jquery'); // Enqueue it!

        wp_enqueue_script('opubcothemescripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0.0', true ); // Custom scripts
        
        wp_enqueue_script( 'slicknav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array( 'jquery' ), true );

    }


}


// Load OPUBCO Theme styles
function opubco_theme_styles()
{
	/* Customize me - http://www.google.com/fonts */
    wp_enqueue_style( 'googleFonts', '//fonts.googleapis.com/css?family=Open+Sans:700,800,400|Open+Sans+Condensed:300,700|Oswald:400,700,300' );
	
	/* Useful for social icons, list items, etc - http://fortawesome.github.io/Font-Awesome/examples/ */
    wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );

    wp_enqueue_style( 'opubco', get_template_directory_uri() . '/style.css', array(), '1.0', 'all' ); // Enqueue it!

}

// Register OPUBCO Theme Navigation
function opubco_register_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
    	'header-top-nav' => 'Header Top Nav',
        'header-menu' => __('Header Main Menu', 'opubco'), // Main Navigation,
        'footer-menu' => __('Footer Menu', 'opubco') // Footer Navigation
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function opubco_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}


// Remove invalid rel attribute values in the categorylist
function opubco_remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function opubco_add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

function opubco_widgets_init() {
	// Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'opubco'),
        'description' => __('Description for this widget-area...', 'opubco'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));	
}
add_action( 'widgets_init', 'opubco_widgets_init' );

// Remove wp_head() injected Recent Comment styles
function opubco_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function opubco_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpt Function Example: opubco_custom_excerpt(40, 'Learn More');
//This will create an excerpt with 40 words and a Learn More link.
function opubco_custom_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    $content = get_the_content();
    $trimmed_content = wp_trim_words( $content, $length_callback, '... <a class="more" href="'. get_permalink() .'">' .$more_callback .'</a>' );
    echo $trimmed_content;
}

// Custom View Article link to Post
function opubco_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'opubco') . '</a>';
}


// Threaded Comments
function opubco_enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function opubcothemecomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

//Grabs LOGO
function opubco_logo(){

    if(of_get_option( 'logo_upload' )){
        $logo = of_get_option( 'logo_upload' );
    } else {
        $logo = get_template_directory_uri().'/img/logo.png';
    }

    return $logo;
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'opubco_header_scripts'); // Add Custom Scripts to wp_head
add_action('get_header', 'opubco_enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'opubco_theme_styles'); // Add Theme Stylesheet
add_action('init', 'opubco_register_menu'); // Add OPUBCO Theme Menu
add_action('init', 'opubco_register_post_types'); // Add our OPUBCO Theme Custom Post Type
add_action('widgets_init', 'opubco_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'opubco_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('body_class', 'opubco_add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'opubco_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'opubco_remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'opubco_view_article'); // Add 'View Article' button instead of [...] for Excerpts

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called OPUBCO-Theme
function opubco_register_post_types()
{
     //Register post types here (using register_post_type)
}

/*------------------------------------*\
    Custom Fields
\*------------------------------------*/

require_once( trailingslashit( get_template_directory() ). 'inc/custom-fields.php' );

/*
	THEME OPTIONS FRAMEWORK

 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 * Find options.php in root of the theme
 */

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';

/*
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 *
 * You can delete it if you not using that option
 */

add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { /*?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});

	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}

});
</script>

<?php */
}

/*------------------------------------*\
    CUSTOM H1
\*------------------------------------*/

function opubco_custom_title($title, $post_id ) {
    global $post;
	if ( is_admin() ) return $title;
	
    if ( in_the_loop() ) {

        if( get_post_meta($post->ID,'custom_h1', true ) ){
            return get_post_meta( $post->ID,'custom_h1', true );
        } else{
          return $title;  
        }
    } else {
	    $custom_h1 = get_post_meta( $post_id, 'custom_h1', true );
	    if ( $custom_h1 ) {
			$custom_h1 = trim( $custom_h1 );
			if ( !empty( $custom_h1 ) ) {
				$title = $custom_h1;
			}
	    }
    }

    return $title;
}
add_filter( 'the_title', 'opubco_custom_title', 10, 2);

/*------------------------------------*\
    CUSTOM MENU SHORTCODE
\*------------------------------------*/

function opubco_shortcode_menu($atts, $content = null) {

    extract(shortcode_atts(array(
        "menu_name" => ''
    ), $atts));

    ob_start();?>

    <div class="custom_menu">
                    
        <?php wp_nav_menu( array('menu' => $menu_name )); ?>
    </div>

    <?php $content = ob_get_contents();
    ob_end_clean();
    return $content;

}
add_shortcode('menu', 'opubco_shortcode_menu');


//ADD HOMEPAGE EDIT LINK IN ADMIN MENU UNDER DASHBOARD MENU
add_action( 'admin_menu' , 'opubco_admin_menu_new_items' );
function opubco_admin_menu_new_items() {
    global $submenu;
 
 $front_page = get_option('page_on_front');
 
 if($front_page != 0){
 $submenu['index.php'][500] = array( 'Edit Home Page', 'manage_options' , get_edit_post_link($front_page) ); 
 
 }
}

//ADD HOMEPAGE EDIT LINK TO ADMIN BAR
add_action('admin_bar_menu', 'opubco_add_toolbar_items',999);

function opubco_add_toolbar_items($admin_bar){
	
	$front_page = get_option('page_on_front');
	
 if($front_page != 0){	
    $admin_bar->add_menu( array(
        'id'    => 'edit-home',
        'parent' => 'site-name',
        'title' => 'Edit Home Page',
        'href'  => get_edit_post_link($front_page),
        'meta'  => array(
            'title' => __('Edit Home Page'),            
        ),
    ));
	}
}

function opubco_copyright( $start_year = false, $echo = true ) {
	//Ensure there is a range if the $start_year and $end_year are different (e.g., 2014-2015)
	$start_year = (string)$start_year;
	$end_year = date( 'Y' );
	if ( $start_year === $end_year || $start_year === false ) {
		$copyright_string = sprintf( '&copy; %s', $end_year );
	} else {
		$copyright_string = sprintf( '&copy; %s-%s', $start_year, $end_year );
	}
	
	if ( $echo ) {
		echo $copyright_string;
	} else {
		return $copyright_string;		
	}	
}

//Set constants
if ( !defined( 'WP_POST_REVISIONS' ) ) define( 'WP_POST_REVISIONS', 5 );

//Allow overriding of editor
class opubco_user_optin {
	public function __construct() {
		add_action( 'personal_options', array( &$this, 'add_interface' ) );
		
		//User update action
		add_action( 'edit_user_profile_update', array( &$this, 'save_user_profile' ) );
		add_action( 'personal_options_update', array( &$this, 'save_user_profile' ) );
		
		//Override caps
		add_filter( 'map_meta_cap', array( &$this, 'map_meta_cap' ), 11, 4 );
		//pply_filters( 'map_meta_cap', $caps, $cap, $user_id, $args );

	}
	public function map_meta_cap( $caps, $cap, $user_id, $args ) {
		if ( $cap == 'edit_plugins' || $cap == 'install_plugins' || $cap == 'delete_plugins' ) {
			if ( current_user_can( 'administrator' ) && 'on' == get_user_option( 'opubco_plugin_editor', $user_id ) ) {
				return array();
			} else {
				return array( 'do_not_allow' );
			}
		}
		if ( $cap == 'edit_themes' || $cap == 'install_themes' || $cap == 'delete_themes') {
			if ( current_user_can( 'administrator' ) && 'on' == get_user_option( 'opubco_theme_editor', $user_id ) ) {
				return array();
			} else {
				return array( 'do_not_allow' );
			}
		}
		return $caps;
	}
	public function add_interface() {
		if ( !current_user_can( 'administrator' ) ) return;
		$user_id = $this->get_user_id();
		?>
		</table>
		<h3>Administrator Opt-in</h3>
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php esc_html_e( "Allow Editors", "opubco" ); ?></th>
			<td>
				<input type="checkbox" name="opt-in-plugin-editor" id="opt-in-plugin-editor" value="on" <?php checked( 'on', get_user_option( 'opubco_plugin_editor', $user_id ) ); ?> /><label for="opt-in-plugin-editor"> Allow plugin editor?</label>
				<br /><br />
				<input type="checkbox" name="opt-in-theme-editor" id="opt-in-theme-editor" value="on" <?php checked( 'on', get_user_option( 'opubco_theme_editor', $user_id ) ); ?> /><label for="opt-in-theme-editor"> Allow theme editor?</label>
				</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( "WordPress Updates", "opubco" ); ?></th>
			<td>
				<input type="checkbox" name="opt-in-updates" id="opt-in-updates" value="on" <?php checked( 'on', get_user_option( 'opubco_wordpress_updates', $user_id ) ); ?> /><label for="opt-in-updates"> Show WordPress updates?</label>
			</td>
		</tr>

		</table>
		
		<?php
	} //end add_interface
	
	/**
	* get_user_id
	*
	* Gets a user ID for the user
	* 
	*@return int user_id
	* 
	@return int post_id
	*/
	private function get_user_id() {
		//Get user ID
		$user_id = isset( $_GET[ 'user_id' ] ) ? absint( $_GET[ 'user_id' ] ) : 0;
		if ( $user_id == 0 && IS_PROFILE_PAGE ) {
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
		}
		return $user_id;
	} //end get_user_id
	
	
	public function save_user_profile( $user_id ) {
		check_admin_referer( 'update-user_' . $user_id );
		if ( !current_user_can( 'administrator' ) ) return;
		
		$user_meta = array();
		$user_meta[ 'opubco_plugin_editor' ] = isset( $_POST[ 'opt-in-plugin-editor' ] ) ? 'on' : 'off';
		$user_meta[ 'opubco_theme_editor' ] = isset( $_POST[ 'opt-in-theme-editor' ] ) ? 'on' : 'off';
		$user_meta[ 'opubco_wordpress_updates' ] = isset( $_POST[ 'opt-in-updates' ] ) ? 'on' : 'off';
		foreach( $user_meta as $meta_key => $meta_value ) {
			update_user_option( $user_id, $meta_key, $meta_value );			
		}
		
	}
}
//From https://wordpress.org/plugins/disable-wordpress-updates/
class opubco_disable_updates {
	/**
	 * The OS_Disable_WordPress_Updates class constructor
	 * initializing required stuff for the plugin
	 *
	 * PHP 5 Constructor
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function __construct() {
		if ( current_user_can( 'administrator' ) ) {
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			if ( 'on' == get_user_option( 'opubco_wordpress_updates', $user_id ) ) {
				return;
			}
		}
		add_action('admin_init', array(&$this, 'admin_init'));
		
		
		/*
		 * Disable Theme Updates
		 * 2.8 to 3.0
		 */
		add_filter( 'pre_transient_update_themes', array($this, 'last_checked_now') );
		/*
		 * 3.0
		 */
		add_filter( 'pre_site_transient_update_themes', array($this, 'last_checked_now') );
		
		
		/*
		 * Disable Plugin Updates
		 * 2.8 to 3.0
		 */
		add_action( 'pre_transient_update_plugins', array(&$this, 'last_checked_now') );
		/*
		 * 3.0
		 */
		add_filter( 'pre_site_transient_update_plugins', array($this, 'last_checked_now') );
		
		
		/*
		 * Disable Core Updates
		 * 2.8 to 3.0
		 */
		add_filter( 'pre_transient_update_core', array($this, 'last_checked_now') );
		/*
		 * 3.0
		 */
		add_filter( 'pre_site_transient_update_core', array($this, 'last_checked_now') );
		

		/*
		 * Disable All Automatic Updates
		 * 3.7+
		 * 
		 * @author	sLa NGjI's @ slangji.wordpress.com
		 */
		add_filter( 'auto_update_translation', '__return_false' );
		add_filter( 'automatic_updater_disabled', '__return_true' );
		add_filter( 'allow_minor_auto_core_updates', '__return_false' );
		add_filter( 'allow_major_auto_core_updates', '__return_false' );
		add_filter( 'allow_dev_auto_core_updates', '__return_false' );
		add_filter( 'auto_update_core', '__return_false' );
		add_filter( 'wp_auto_update_core', '__return_false' );
		add_filter( 'auto_core_update_send_email', '__return_false' );
		add_filter( 'send_core_update_notification_email', '__return_false' );
		add_filter( 'auto_update_plugin', '__return_false' );
		add_filter( 'auto_update_theme', '__return_false' );
		add_filter( 'automatic_updates_send_debug_email', '__return_false' );
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
	}
	

	/**
	 * The OS_Disable_WordPress_Updates class constructor
	 * initializing required stuff for the plugin
	 *
	 * PHP 4 Compatible Constructor
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function opubco_disable_updates() {
		$this->__construct();
	}
	
	
	/**
	 * Initialize and load the plugin stuff
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function admin_init() {
		if ( !function_exists("remove_action") ) return;
	
		/*
		 * Disable Theme Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		/*
		 * 3.0
		 */
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		
		/*
		 * Disable Plugin Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		/*
		 * 3.0
		 */
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		
		/*
		 * Disable Core Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		
		/*
		 * 3.0
		 */
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		
		/*
		 * 3.7+
		 */
		remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
		remove_action( 'admin_init', 'wp_maybe_auto_update' );
		remove_action( 'admin_init', 'wp_auto_update_core' );
		wp_clear_scheduled_hook( 'wp_maybe_auto_update' );
	}
	



	/**
	 * Get version check info
	 *
	 * @since 		1.3.1
	 * @author 		flynsarmy (props & kudos!)
	 * @link		http://wordpress.org/support/topic/patch-incorrect-disabling-of-updates
	 */
	public function last_checked_now( $transient ) {
		include ABSPATH . WPINC . '/version.php';
		$current = new stdClass;
		$current->updates = array();
		$current->version_checked = $wp_version;
		$current->last_checked = time();
		
		return $current;
	}
	
}
new opubco_user_optin();
new opubco_disable_updates();

/*
 * Adds a bright red box on localhost
 * Box contains the server name
 *
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 */
function opubco_localhost() {
	// Do check for localhost IP (remove this if you want to ALWAYS display it)
	if ( '127.0.0.1' != $_SERVER['REMOTE_ADDR'] && '::1' != $_SERVER['REMOTE_ADDR'] ) {
		return;
	}
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) {
		$debug = 'WP_DEBUG=ON';
	} else {
		$debug = 'WP_DEBUG=OFF';
	}
	echo '
		<div style="
			position: fixed;
			right: 10px;
			bottom: 10px;
			width: auto;
			padding: 0 8px;
			height: 22px;
			background: #ff0000;
			border-radius: 5px;
			box-shadow: 0 2px 5px 2px rgba(0,0,0,0.3);
			z-index: 99999999999999;

			font-family: sans-serif;
			font-size: 13px;
			line-height: 22px;
			color: #fff;
			font-weight: bold;
			text-align: center;
			text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
		">' . php_uname( 'n' ) . ' ' . $debug . '</div>';
}
add_action( 'wp_footer', 'opubco_localhost' );
add_action( 'admin_footer', 'opubco_localhost' );

/* Easy filter for filtering content */
/* $text = apply_filters( 'opubco_the_content', 'text to filter' ); */
add_action( 'init', 'opubco_the_content', 1 );
function opubco_the_content() {
	//Create our own version of the_content so that others can't accidentally loop into our output - Taken from default-filters.php, shortcodes.php, and media.php
	if ( !has_filter( 'opubco_the_content', 'wptexturize' ) ) {
		add_filter( 'opubco_the_content', 'wptexturize'        );
		add_filter( 'opubco_the_content', 'convert_smilies'    );
		add_filter( 'opubco_the_content', 'convert_chars'      );
		add_filter( 'opubco_the_content', 'wpautop'            );
		add_filter( 'opubco_the_content', 'shortcode_unautop'  );
		add_filter( 'opubco_the_content', 'prepend_attachment' );
		$vidembed = new WP_Embed();
		add_filter( 'opubco_the_content', array( &$vidembed, 'run_shortcode'), 8 );
		add_filter( 'opubco_the_content', array( &$vidembed, 'autoembed'), 8 );
		add_filter( 'opubco_the_content', 'do_shortcode', 11);
	} //end has_filter
} //end opubco_the_content

/*------------------------------------*\
    Options Framework - Add Page Selector
\*------------------------------------*/
add_filter( 'optionsframework_page_select', 'opubco_page_select', 15, 3 );
function opubco_page_select( $option_name, $value, $val ) {
	$val = !empty( $val ) ? absint( $val ): 0;
	$id = !isset( $value[ 'id' ] ) ? 'home_page_select' : $value[ 'id' ];
	$pages = wp_dropdown_pages( array( 'depth' => -1, 'selected' => $val, 'echo' => false, 'name' => sprintf( '%s[%s]', $option_name, $id ), 'id' => $id  ) );
	return $pages;
}
add_filter( 'of_sanitize_page_select', 'opubco_page_select_sanitization', 10, 2);
function opubco_page_select_sanitization( $input, $option ) {
	return absint( $input );
}

/*------------------------------------*\
    Disable comments on media pages
\*------------------------------------*/
function opubco_filter_media_comment_status( $open, $post_id ) {
	$post = get_post( $post_id );
	if( $post->post_type == 'attachment' ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'opubco_filter_media_comment_status', 10 , 2 );

//Get attachment image ID from URL
function opubco_get_attachment_id_from_url( $attachment_url = '' ) {
 
	global $wpdb;
	$attachment_id = false;
 
	// If there is no url, return.
	if ( '' == $attachment_url )
		return;
 
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();
 
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
 
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
 
		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
 
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
 
	}
 
	return $attachment_id;
}
//Options Framework
function optionsframework_option_name() {
	return get_option( 'stylesheet' );
}

/* Disable Admin Bar for all users who are not administrators */
add_action( 'after_setup_theme', 'opubco_remove_admin_bar' );
function opubco_remove_admin_bar() {
	if ( !current_user_can('administrator') && !is_admin() ) {
		show_admin_bar(false);
	}
}

/* WordPress - Add Edit Link to Options-Reading Pages */
class opubco_reader_edit {
	public function __construct() {
		add_action( 'admin_head-options-reading.php', array( $this, 'init' ) );
	}
	public function init() {
		add_filter( 'wp_dropdown_pages', array( 'opubco_reader_edit', 'add_edit_links' ) );
	}
	public static function add_edit_links( $output ) {
		if ( 'page' != get_option( 'show_on_front' ) ) return $output;
		
		if ( strstr( $output, 'page_on_front' ) ) {
			$page_id = absint( get_option( 'page_on_front' ) );
			if ( $page_id > 0 ) {
				$output = $output . sprintf( '&nbsp;&nbsp;<a href="%s">Edit</a>', esc_url( add_query_arg( array( 'post' => $page_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) ) );
			}
		} elseif ( strstr( $output, 'page_for_posts' ) ) {
			$page_id = absint( get_option( 'page_for_posts' ) );
			if ( $page_id > 0 ) {
				$output = $output . sprintf( '&nbsp;&nbsp;<a href="%s">Edit</a>', esc_url( add_query_arg( array( 'post' => $page_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) ) );
			}
		}
		return $output;	
	}
}
new opubco_reader_edit;

//Retrieve social media icons! - Takes an array of social names and retrieves them from the options framework (see options.php)
//You can pass display names like Facebook, YouTube, Twitter, and it should still work
function opubco_get_social_html( $icons = array(), $wrapper = 'ul', $item = 'li', $echo = true ) {
	$html = '';
	$has_icons = false;
	if ( !empty( $icons ) && is_array( $icons ) ) {
		$html .= sprintf( '<%s>', esc_html( $wrapper ) );
		foreach( $icons as $index => $icon_name ) {
			$icon_lower = $font_awesome = strtolower( $icon_name );
			$option = of_get_option( $icon_lower, false );
			if ( $icon_lower == 'google' ) {
				$font_awesome = 'google-plus';
			} 
			if ( !$option || empty( $option ) ) continue;
			$html .= sprintf( '<%1$s><a href="%2$s" target="_blank"><i class="fa fa-%3$s fa-fw"></i><span class="fa-content">&nbsp;%4$s</span></a></%1$s>', esc_html( $item ), esc_url( $option ), esc_attr( $font_awesome ), esc_html( $icon_name ) );
			$has_icons = true;
		}
		$html .= sprintf( '</%s>', esc_html( $wrapper ) );
	}
	if ( !$has_icons ) {
		$html = '';
	}
	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}
