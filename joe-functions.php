<?php
/*
Plugin Name: Joe Functions
Description: A plugin Created to help get started clean.
Version: 1.0
Author: Joseph Michel

Table of Content:
- Removing Menu Items for Non-Administrators
- Customize the Footer
- Remove Dashboard Widgets
- Remove WordPress 3.3 Admin Bar Menu Items
- Remove Help tab
- Remove Pages Columns
- Remove Posts Columns
- Change the login and admin Logo
- Add custom text to WordPress login page
- Disable Upgrade Now Message for Non-Administrators
- Disable browser upgrade warning in wordpress
- hide the new dashboard welcome panel
- Remove meta boxes from Pages
- Remove meta boxes from Posts
- unregister all default WP Widgets
- Set permalink structure
- Change Default Excerpt Title and Paragraph under box
- Change Default Help Text from Page Attributes Metabox
- Allow SVG through WordPress Media Uploader
- Add/Remove buttons to text editor
- Add custom styles to TinyMCE editor
- Loading stylesheets for WordPress admin
- Loading Javascript for wordpress admin
- Add Custom Dashboard Metabox
- Add theme support for Menus
- Add theme support for thumbnails
- Add menu,sub menu,top level menu in on admin panel 
- Give Editors permission to edit menus
- Enable Automatic Updates for Major WordPress Releases
- Improve the excerpt

///Other Good Ones///
- Add image size
- Exclude pages from None admin
- Display Excerpt metabox on pages or specific pages
- Remove Width and Height Attributes From Inserted Images
- Remove Menu Item
- Remove meta boxes from specific pages
- Enqueue scripts and styles for the front end
- Show Notification Message in WordPress Admin Pages
- Excerpt Length
- Remove Network Admin Dashboard Widgets
- Add a widget to the Network Admin dashboard
- Disable My Sites menu in toolbar for Super Admins & replace with custom menu
- Change Default Post Label
- Change “Enter Title Here” Prompt for Custom Post Types
- Change “Featured Image” and “Excerpt” Title for a spacific post type
- Change post type icon
- Add Post Type
- Add taxonomies to post type
- Add featured thumbnail to admin post columns
- Add Metabox to a spacific page
- Adding custom html to a page
*/

////////////////////////////////////////////////////////////////////////
/*Removing Menu Items for Non-Administrators */
function remove_menu_items() {
	if ( !current_user_can('manage_options') ) {
	  global $menu;
  		$restricted = array(__('Links'), __('Tools'), __('Comments'), __('Appearance'), __('Profile'), __('Media'));
  		end ($menu);
  		while (prev($menu)){
    	$value = explode(' ',$menu[key($menu)][0]);
    	if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
     	unset($menu[key($menu)]);}
    	}
	}
}
add_action('admin_menu', 'remove_menu_items');
//-------------
function remove_menu_items_from_admin() {
    global $menu;
      $restricted = array(__('Links'), __('Comments'), __('Media'));
      end ($menu);
      while (prev($menu)){
      $value = explode(' ',$menu[key($menu)][0]);
      if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
      unset($menu[key($menu)]);}
      }
  }
add_action('admin_menu', 'remove_menu_items_from_admin');
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Customize the Footer*/
function modify_footer_admin () {
  echo 'Created and Powered by <a href="http://www.inktankcommunications.com">Inktank Communications</a>.';
}
add_filter('admin_footer_text', 'modify_footer_admin');
/* END */
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove Dashboard Widgets*/
function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_quick_press']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove WordPress 3.3 Admin Bar Menu Items*/
function dashboard_tweaks() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
    $wp_admin_bar->remove_menu('view-site');
    $wp_admin_bar->remove_menu('new-content');
    $wp_admin_bar->remove_menu('themes');
    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_menu('menus');
}
add_action( 'wp_before_admin_bar_render', 'dashboard_tweaks' );
//-------------
function dashboard_tweaks_editors() {
	if ( !current_user_can('manage_options') ) {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('my-sites');
	}
}
add_action( 'wp_before_admin_bar_render', 'dashboard_tweaks_editors' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove Help tab*/
add_filter( 'contextual_help', 'mytheme_remove_help_tabs', 999, 3 );
function mytheme_remove_help_tabs($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Remove Pages Columns */
function remove_pages_columns($defaults) {
  unset($defaults['comments']);
  unset($defaults['author']);
  unset($defaults['date']);
  return $defaults;
}
add_filter('manage_pages_columns', 'remove_pages_columns');
/* END */
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Remove Posts Columns */
add_action( 'admin_init', 'fb_deactivate_support' );
function fb_deactivate_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'author' );
}
/* END */
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change the login and admin Logo*/
function custom_login_logo() {
  echo '<style type="text/css">
    .login h1 a { background-image:url('.plugins_url( 'images/inktankweb-logo.png' , __FILE__ ).') !important; height:auto !important; padding-bottom:50px; !important; background-size:auto !important; width:300px !important}
    </style>';
}
add_action('login_head', 'custom_login_logo');
function inktankweb_url(){
    return ('http://www.inktankcommunications.com');
    }
add_filter('login_headerurl', 'inktankweb_url');
// Change title for login screen
add_filter('login_headertitle', create_function(false,"return 'Inktank Communications';"));
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add custom text to WordPress login page*/
function wps_login_message( $message ) {
    if ( empty($message) ){
        return "<p class='message'>Welcome to the Inktank Websites.<br/>Please log in to continue</p>";
    } else {
        return $message;
    }
}
add_filter( 'login_message', 'wps_login_message' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Disable Upgrade Now Message for Non-Administrators */
function remove_wp_update_notice() {
	if ( !current_user_can('manage_options') ) {
	  remove_action( 'admin_notices', 'update_nag', 3);
	  }
}
add_action('admin_init', 'remove_wp_update_notice');
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Disable browser upgrade warning in wordpress*/
function disable_browser_upgrade_warning() {
    remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'disable_browser_upgrade_warning' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*hide the new dashboard welcome panel*/
remove_action('welcome_panel', 'wp_welcome_panel');
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove meta boxes from Pages*/
function my_remove_meta_boxes() {
  remove_meta_box('postcustom', 'page', 'normal');
  remove_meta_box('commentsdiv', 'page', 'normal');
  remove_meta_box('commentstatusdiv', 'page', 'normal');
  remove_meta_box('slugdiv', 'page', 'normal');
  remove_meta_box('authordiv', 'page', 'normal');
  remove_meta_box('revisionsdiv', 'page', 'normal');
 }
add_action( 'admin_menu', 'my_remove_meta_boxes' );
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove meta boxes from Posts*/
function jp_remove_tags_post_meta_box() {
  remove_meta_box( 'slugdiv', 'post', 'normal' );
	remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'formatdiv', 'post', 'normal' );
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
	remove_meta_box('revisionsdiv', 'post', 'normal');
}
add_action( 'admin_menu', 'jp_remove_tags_post_meta_box' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*unregister all default WP Widgets*/
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Set permalink structure*/
add_action( 'init', function() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
} );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change Default Excerpt Title and Paragraph under box*/
add_filter( 'gettext', 'wpse22764_gettext', 10, 2 );
function wpse22764_gettext( $translation, $original )
{
    if ( 'Excerpt' == $original ) {
        return 'Excerpt';
    }else{
        $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');
        if ($pos !== false) {
            return  '';
        }
    }
    return $translation;
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change Default Help Text from Page Attributes Metabox*/
class RemovePageAttributesHelpText {
  static function on_load() {
    add_filter('gettext',array(__CLASS__,'gettext'),10,4);
  }
  function gettext($translation, $text, $domain) {
    if ($text=='Need help? Use the Help tab in the upper right of your screen.') {
      $translation = '';
    }
    return $translation;
  }
}
RemovePageAttributesHelpText::on_load();
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Allow SVG through WordPress Media Uploader*/
function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add/Remove buttons to text editor*/
function enable_more_buttons($buttons) {
  $buttons[] = 'superscript';
  $buttons[] = 'subscript';
  $buttons[] = 'fontsizeselect';
  return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");


// Customize mce editor colors
function make_mce_awesome( $init ) {
    $init['block_formats'] = 'Header 2=h2;Header 3=h3;Paragraph=p';
    $custom_colours = '
        "002D62", "Blue",
        "72CEF5", "Light Blue",
        "7BBE4A", "Green",
        "F8B22E", "Yellow",
        "ED0285", "Pink",
        "222222", "Black",
        ';
    $init['textcolor_map'] = '['.$custom_colours.']';
    $init['textcolor_rows'] = 6; // expand colour grid to 6 rows
    return $init;
}
add_filter('tiny_mce_before_init', 'make_mce_awesome');


// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
  function wpex_mce_text_sizes( $initArray ){
    $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 22px";
    return $initArray;
  }
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );


/* Row 1 */
function userf_buttons_1($buttons) {
  $remove = array('blockquote','strikethrough');
  foreach($remove as $rem) {
    if ( in_array($rem, $buttons) )
      unset($buttons[array_search($rem, $buttons)]);
  }
  return $buttons;
}
add_filter("mce_buttons", "userf_buttons_1");

/* Row 2 */
function userf_buttons_2($buttons) {
  $remove = array('underline','pastetext','keyboardshortcuts');
  foreach($remove as $rem) {
    if ( in_array($rem, $buttons) )
      unset($buttons[array_search($rem, $buttons)]);
  }
  return $buttons;
}
add_filter("mce_buttons_2", "userf_buttons_2");
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add custom styles to TinyMCE editor*/
function custom_mce_css($wp) {
    return $wp .= ',' . plugins_url( "css/tinymce.css" , __FILE__ );
}
add_filter( 'mce_css', 'custom_mce_css' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Loading stylesheets for WordPress admin */
function add_style_to_admin() {
    if(is_admin()){
        wp_enqueue_style( 'my-style', plugins_url( "css/admin-styles.css" , __FILE__ ), false, '1.0', 'all' );
    }   
}
add_action( 'admin_head', 'add_style_to_admin' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Loading Javascript for WordPress admin */
function add_script_to_admin() {
    if(is_admin()){
        wp_enqueue_script( 'my-script', plugins_url('js/admin-scripts.js', __FILE__), array('jquery'),'1.0', true );
    }   
}
add_action( 'admin_head', 'add_script_to_admin' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Add Custom Dashboard Metabox */
function dashboard_widget_function() {
    echo 'For help please contact <a href="http://inktankcommunications.com" target="_blank">Inktank Communications</a>';
}
function add_dashboard_widgets() {
    	wp_add_dashboard_widget('cube3x_dashboard_widget', 'Inktank Websites', 'dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Add theme support for Menus */
add_theme_support( 'menus' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add theme support for thumbnails*/
add_theme_support( 'post-thumbnails', array( 'post' ) ); 
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add menu,sub menu,top level menu in on admin panel 
http://www.maheshchari.com/wordpress-add-admin-menu/ 
http://wordpress.stackexchange.com/questions/1039/adding-an-arbitrary-link-to-the-admin-menu*/
add_action('admin_menu', 'create_navigation_link');
function create_navigation_link() {
  add_pages_page(
    'Menus',
    'Menus',
    'delete_pages', // Capability that can "delete pages"
    'nav-menus.php'
  );
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Give Editors permission to edit menus*/
add_action( 'init', 'my_role_modification' );
function my_role_modification() {
  $role = get_role( 'editor' );
  $role->add_cap( 'edit_theme_options' );
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Enable Automatic Updates for Major WordPress Releases*/
add_filter( 'allow_major_auto_core_updates', '__return_true' );
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Improve the excerpt*/
add_filter('the_excerpt', 'new_excerpt_hellip');
function new_excerpt_hellip($text)
{
   return str_replace('[...]', '&hellip;[+]', $text);
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change admin post/page color by status*/
add_action('admin_footer','posts_status_color');
function posts_status_color(){
?>
<style>
.status-draft{background: #FCE3F2 !important;}
.status-pending{background: #87C5D6 !important;}
.status-publish{/* no background keep wp alternating colors */}
.status-future{background: #C6EBF5 !important;}
.status-private{background:#F2D46F;}
</style>
<?php
}
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Other Good Ones*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add image size*/
/*if ( function_exists( 'add_image_size' ) ) { 
  add_image_size( 'thumbonetwenty', 200, 200, true );
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Exclude pages from None admin*/
/*function jp_exclude_pages_from_admin($query) {
  if ( ! is_admin() )
    return $query;
 
  global $pagenow, $post_type;
 
  if ( !current_user_can( 'administrator' ) && is_admin() && $pagenow == 'edit.php' && $post_type == 'page' )
    $query->query_vars['post__not_in'] = array( '445' ); // Enter your page IDs here
}
add_filter( 'parse_query', 'jp_exclude_pages_from_admin' );*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Display Excerpt metabox on pages or specific pages*/
/*function mytheme_addbox() {
    //display excerpt metabox on page with the id 10
    //$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    //if ( $post_id == "10" ){
    //display excerpt metabox on pages children of page with the id 10
    $pid = (isset($_GET['post']) ? $_GET['post'] : $_POST['post_ID']); 
    $page_att = get_page( $pid );
    $page_parent = $page_att->post_parent;
    if(12 == $page_parent){
         add_meta_box('postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', 'page', 'normal', 'core');  
    }
}
add_action( 'admin_init', 'mytheme_addbox' );*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove Width and Height Attributes From Inserted Images*/
/*add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove Menu Item*/
/*add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
  remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove meta boxes from specific pages*/
/*function unused_meta_boxes() {
    //display excerpt metabox on page with the id 10
    //$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    //if ( $post_id == "10" ){
    //display excerpt metabox on pages children of page with the id 10
    $pid = (isset($_GET['post']) ? $_GET['post'] : $_POST['post_ID']); 
    $page_att = get_page( $pid );
    $page_parent = $page_att->post_parent;
    if(12 !== $page_parent){
         remove_meta_box('postimagediv','page','side'); // Featured Image
  }
}
add_action('admin_head', 'unused_meta_boxes');*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Enqueue scripts and styles for the front end */
/*function james_adds_to_the_head() { // Our own unique function called james_adds_to_the_head
    wp_enqueue_script('jquery');  // Enqueue jQuery that's already built into WordPress
    wp_register_script( 'add-bx-js', get_template_directory_uri() . '/jquery.bxslider/jquery.bxslider.min.js', array('jquery'),'',true  ); // Register our first script for BX Slider, to be brought out in the footer
    wp_register_script( 'add-bx-custom-js', get_template_directory_uri() . '/jquery.bxslider/custom.js', '', null,''  ); // Register our second custom script for BX
    wp_register_style( 'add-bx-css', get_template_directory_uri() . '/jquery.bxslider/jquery.bxslider.css','','', 'screen' ); // Register the BX Stylsheet
    
    wp_enqueue_script( 'add-bx-js' );  // Enqueue our first script
    wp_enqueue_script( 'add-bx-custom-js' ); // Enqueue our second script
    wp_enqueue_style( 'add-bx-css' ); // Enqueue our stylesheet
}
add_action( 'wp_enqueue_scripts', 'james_adds_to_the_head' ); //Hooks our custom function into WP's wp_enqueue_scripts function*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Show Notification Message in WordPress Admin Pages*/
/*function wps_wp_admin_area_notice() {  
   echo ' <div class="error">
                 <p>We are performing website Maintenance. Please dont do any activity until further notice!</p>
          </div>';
}
add_action( 'admin_notices', 'wps_wp_admin_area_notice' );*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Excerpt Length */
/*function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Remove Network Admin Dashboard Widgets*/
/*function remove_network_widgets() {
        global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard-network']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard-network']['side']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard-network']['normal']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard-network']['normal']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard-network']['normal']['core']['dashboard_plugins']);
        unset($wp_meta_boxes['dashboard-network']['normal']['core']['network_dashboard_right_now']);
}
add_action('wp_network_dashboard_setup', 'remove_network_widgets', 20, 0);*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add a widget to the Network Admin dashboard*/
/*function example_add_dashboard_widgets() {
  wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Add New Inktank Website',         // Title.
                 'example_dashboard_widget_function' // Display function.
        );  
}
add_action( 'wp_network_dashboard_setup', 'example_add_dashboard_widgets' );
function example_dashboard_widget_function() {
  echo '<a href="http://inktank.co/wp-admin/network/site-new.php">+ Create New Site</a>';
  
  
  echo '<p style="text-align:center">---<p>';
  
  
  $blog_count = get_blog_count();
  echo 'There are currently '.$blog_count.' Sites running on this Network.';
  
  
  $user_id = 1;
  $user_blogs = get_blogs_of_user( $user_id );
  foreach ($user_blogs AS $user_blog) {
    echo '<li><a href="'.$user_blog->site_id.'">'.$user_blog->blogname.'</a></li>';
  }
  echo '</ul>';
} */
/* END */
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Disable My Sites menu in toolbar for Super Admins & replace with custom menu*/
/*function jpl_remove_my_sites( $wp_admin_bar ) {
  if (current_user_can('manage_network'))
    $wp_admin_bar->remove_node('my-sites');
}
add_action( 'admin_bar_menu', 'jpl_remove_my_sites', 999 );
function jpl_my_sites($admin_bar) {
  if (current_user_can('manage_network'))
  $admin_bar->add_menu( array(
    'id'    => 'jpl-my-sites',
    'title' => 'Inktank Sites',
    'href'  => admin_url('my-sites.php'),
    'meta'  => array(
      'title' => __('Inktank Sites'),     
    ),
  ));
  $admin_bar->add_menu( array(
    'id'    => 'jpl-network-admin',
    'parent' => 'jpl-my-sites',
    'title' => 'Network Dashboard',
    'href'  => network_admin_url(),
  ));
  $admin_bar->add_menu( array(
    'id'    => 'jpl-network-sites',
    'parent' => 'jpl-my-sites',
    'title' => 'Network Sites',
    'href'  => network_admin_url('sites.php'),
  ));
  $admin_bar->add_menu( array(
    'id'    => 'jpl-network-users',
    'parent' => 'jpl-my-sites',
    'title' => 'Network Users',
    'href'  => network_admin_url('users.php'),
  ));
}
add_action('admin_bar_menu', 'jpl_my_sites', 20);*/
/* END */
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change Default Post Label (changed to "News Releases")*/
/*function frl_change_post_labels($post_type, $args){ 
    global $wp_post_types;
     
    if($post_type != 'post')
        return;
     
    $labels = new stdClass();
     
    $labels->name               = __('News Releases', 'frl');
    $labels->singular_name      = __('News Release', 'frl');
    $labels->add_new            = __('Add new', 'frl');
    $labels->add_new_item       = __('Add new News Release', 'frl');
    $labels->edit_item          = __('Edit News Release', 'frl');
    $labels->new_item           = __('New News Release', 'frl');
    $labels->view_item          = __('View News Release', 'frl');
    $labels->search_items       = __('Search News Releases', 'frl');
    $labels->not_found          = __('No News Releases found', 'frl');
    $labels->not_found_in_trash = __('No News Releases found in Trash.', 'frl');
    $labels->parent_item_colon  = NULL;
    $labels->all_items          = __('All News Releases', 'frl');
    $labels->menu_name          = __('News Releases', 'frl');
    $labels->name_admin_bar     = __('News Release', 'frl');
     
    $wp_post_types[$post_type]->labels = $labels;
}
add_action('registered_post_type', 'frl_change_post_labels', 2, 2);
 
function frl_change_post_menu_labels(){ // change adming menu labels 
    global $menu, $submenu;
     
    $post_type_object = get_post_type_object('post');
    $sub_label = $post_type_object->labels->all_items; 
    $top_label = $post_type_object->labels->name;
     
    // find proper top menu item 
    $post_menu_order = 0;
    foreach($menu as $order => $item){
         
        if($item[2] == 'edit.php'){
            $menu[$order][0] = $top_label;
            $post_menu_order = $order;
            break;
        }
    }
     
    // find proper submenu 
    $submenu['edit.php'][$post_menu_order][0] = $sub_label;
}
add_action('admin_menu', 'frl_change_post_menu_labels');
 
function frl_change_post_updated_labels($messages){     // change updated post labels 
    global $post;
         
    $permalink = get_permalink($post->ID);
         
    $messages['post'] = array(
         
    0 => '', 
    1 => sprintf( __('News Release updated. <a href="%s">View post</a>', 'frl'), esc_url($permalink)),
    2 => __('Custom field updated.', 'frl'),
    3 => __('Custom field deleted.', 'frl'),
    4 => __('News Release updated.', 'frl'),    
    5 => isset($_GET['revision']) ? sprintf(__('News Release restored to revision from %s', 'frl'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
    6 => sprintf( __('News Release published. <a href="%s">View post</a>'), esc_url($permalink)),
    7 => __('News Release saved.', 'frl'),
    8 => sprintf( __('News Release submitted. <a target="_blank" href="%s">Preview</a>', 'frl'), esc_url(add_query_arg('preview','true', $permalink))),
    9 => __('News Release scheduled. <a target="_blank" href="%2$s">Preview</a>', 'frl'),
    10 => sprintf( __('News Release draft updated. <a target="_blank" href="%s">Preview</a>', 'frl'), esc_url(add_query_arg('preview', 'true', $permalink)))
 
    );
 
    return $messages;
}
add_filter('post_updated_messages', 'frl_change_post_updated_labels');*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change “Enter Title Here” Prompt for Custom Post Types*/
/*function dj_filter_title_text($title)
{
    $scr = get_current_screen();
    if ('team_members' == $scr->post_type)
        $title = 'Team Member Name';
    return ($title);
}
add_filter('enter_title_here', 'dj_filter_title_text');*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change “Featured Image” and “Excerpt” Title for a spacific post type */
/*function custom_post_type_boxes(){
    remove_meta_box( 'postimagediv', 'team_members', 'side' );
    add_meta_box( 'postimagediv', __( 'Team Member Photo' ), 'post_thumbnail_meta_box', 'team_members', 'normal', 'high' );
    remove_meta_box( 'postexcerpt', 'team_members', 'normal' );
    add_meta_box( 'postexcerpt', __( 'Team Member Title' ), 'post_excerpt_meta_box', 'team_members', 'normal', 'high' );
}
add_action('do_meta_boxes', 'custom_post_type_boxes');*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Change post type icon (Posts)*/
/*add_action( 'admin_head', 'wpt_post_icons' );
function wpt_post_icons() {
echo '<style type="text/css" media="screen">
.icon16.icon-post, #adminmenu .menu-icon-post div.wp-menu-image {background: transparent url('.plugins_url( "images/blog-blue.png" , __FILE__ ).') no-repeat scroll 6px -18px !important;}
#adminmenu .menu-icon-post:hover div.wp-menu-image, #adminmenu .menu-icon-post.wp-has-current-submenu div.wp-menu-image {background: transparent url('.plugins_url( "images/blog-blue.png" , __FILE__ ).') no-repeat scroll 6px 6px !important;}
</style>';
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add Post Type (Team Members)
add_action( 'init', 'team_members_init' );
function team_members_init() {
  $labels = array(
    'name' => _x('Team Members', 'post type general name'),
    'singular_name' => _x('Team Member', 'post type singular name'),
    'add_new' => _x('Add New', 'Team Member'),
    'add_new_item' => __('Add New Team Member'),
    'edit_item' => __('Edit Team Member'),
    'new_item' => __('New Team Member'),
    'all_items' => __('All Team Members'),
    'view_item' => __('View Team Member'),
    'search_items' => __('Search Team Members'),
    'not_found' =>  __('No Team Members found'),
    'not_found_in_trash' => __('No Team Members found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Team Members'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 4,
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' )
  ); 
  register_post_type('team_members',$args);
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Add taxonomies to post type*/
/*add_action( 'init', 'create_publication_taxonomies', 0 );
function create_publication_taxonomies() {
  $labels = array(
    'name'              => _x( 'Publications', 'taxonomy general name' ),
    'singular_name'     => _x( 'Publication', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Publications' ),
    'all_items'         => __( 'All Publications' ),
    'parent_item'       => __( 'Parent Publication' ),
    'parent_item_colon' => __( 'Parent Publication:' ),
    'edit_item'         => __( 'Edit Publication' ),
    'update_item'       => __( 'Update Publication' ),
    'add_new_item'      => __( 'Add New Publication' ),
    'new_item_name'     => __( 'New Publication Name' ),
    'menu_name'         => __( 'Publications' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'publication' ),
  );

  register_taxonomy( 'publication', array( 'in_the_news' ), $args );
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Add featured thumbnail to admin post columns */
/*add_filter( 'manage_edit-team_members_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);
function posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __('Photo');
    return $defaults;
}
function posts_custom_columns($column_name, $id){
        if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( array(125,80));
    }
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/* Add Metabox to a spacific page */
/*add_action('admin_init','my_meta_init');
function cd_meta_box_cb(){
  echo '<p style="text-align:center;padding:30px 0;display:block">Team members list is accessed from the left menu.</p>'; 
}
function my_meta_init(){
  $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
  // checks for post/page ID
  if ($post_id == '6')
  {
    add_meta_box('my_all_meta_1', 'Team Members List', 'cd_meta_box_cb', 'page', 'normal', 'high');
  }
  add_action('save_post','my_meta_init');
}*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*Adding custom html to a page*/
  /*$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
  // checks for post/page ID
  if ($post_id == '6')
  {
    //add_action( 'edit_form_after_title', 'myprefix_edit_form_after_title' );
    //function myprefix_edit_form_after_title() {
    //    echo '<h2>This is edit_form_after_title!</h2>';
    //}
    add_action( 'edit_form_after_editor', 'myprefix_edit_form_after_editor' );
    function myprefix_edit_form_after_editor() {
        echo '<p style="text-align:center;padding:30px 0;display:block;color:#D74E21">Team members list is accessed <a href="'; echo get_site_url(); echo '/wp-admin/edit.php?post_type=team_members">from the left menu.</a></p>';
    } 
    //add_action( 'edit_form_advanced', 'myprefix_edit_form_advanced' );
    //function myprefix_edit_form_advanced() {
    //    echo '<h2>This is ye olde edit_form_advanced!</h2>';
    //}
  }
  add_action('save_post','my_meta_init2');*/
/*END*/
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/*DISPLAY ADDITIONAL WARNING MESSAGE AFTER POST SAVING*/
/*function frl_on_save_post($post_id, $post) {// add warning filter when saving post 
 
    if($post->post_type == 'post') //test for something real here       
        add_filter('redirect_post_location', 'frl_custom_warning_filter');
 
}
add_action('save_post', 'frl_on_save_post', 2, 2);
 
function frl_custom_warning_filter($location) { // filter redirect location to add warning parameter
 
    $location = add_query_arg(array('warning'=>'my_warning'), $location);
    return $location;
}
 
function frl_warning_in_notice() { // print warning message 
         
    if(!isset($_REQUEST['warning']) || empty($_REQUEST['warning']))
        return;
         
    $warnum = trim($_REQUEST['warning']);
 
    // possible warnings codes and messages                
    $warnings = array(
        'my_warning' => __('This is my truly custom warning!', 'frl')
    );
         
    if(!isset($warnings[$warnum]))
        return; 
     
    echo '<div class="error message"><p><strong>';
    echo $warnings[$warnum];
    echo '</strong></p></div>';
}       
add_action('admin_notices', 'frl_warning_in_notice');*/
/*END*/
?>
