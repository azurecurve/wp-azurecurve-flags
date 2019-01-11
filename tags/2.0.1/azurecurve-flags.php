<?php
/*
Plugin Name: azurecurve Flags
Plugin URI: http://development.azurecurve.co.uk/plugins/flags

Description: Allows a 16x16 flag to be displayed in a post of page using a shortcode.
Version: 2.0.0

Author: azurecurve
Author URI: http://development.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

function azc_f_flag($atts, $content = null) {
	if (empty($atts)){
		$flag = 'none';
	}else{
		$attribs = implode('',$atts);
		$flag = str_replace("'", '', str_replace('"', '', substr ( $attribs, 1)));
	}
	return "<img class='azc_flags' src='".plugin_dir_url(__FILE__)."images/$flag.png' />";
}
add_shortcode( 'flag', 'azc_f_flag' );
add_shortcode( 'flags', 'azc_f_flag' );
add_shortcode( 'FLAG', 'azc_f_flag' );
add_shortcode( 'FLAGS', 'azc_f_flag' );

function azc_f_load_css(){
	wp_enqueue_style( 'azurecurve-flags', plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'azc_f_load_css');


// azurecurve menu
if (!function_exists(azc_create_plugin_menu)){
	function azc_create_plugin_menu() {
		global $admin_page_hooks;
		
		if ( empty ( $admin_page_hooks['azc-menu-test'] ) ){
			add_menu_page( "azurecurve Plugins"
							,"azurecurve"
							,'manage_options'
							,"azc-plugin-menus"
							,"azc_plugin_menus"
							,plugins_url( '/images/Favicon-16x16.png', __FILE__ ) );
			add_submenu_page( "azc-plugin-menus"
								,"Plugins"
								,"Plugins"
								,'manage_options'
								,"azc-plugin-menus"
								,"azc_plugin_menus" );
		}
	}
	add_action("admin_menu", "azc_create_plugin_menu");
}

function azc_create_f_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"Flags"
						,"Flags"
						,'manage_options'
						,"azc-f"
						,"azc_f_settings" );
}
add_action("admin_menu", "azc_create_f_plugin_menu");

function azc_f_settings() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error('not_found', __('You do not have sufficient permissions to access this page.' , 'azc_md'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	?>
	<div id="azc-t-general" class="wrap">
			<h2>azurecurve Flags</h2>

			<label for="explanation">
				<p>azurecurve Flags <?php _e('allows a 16x16 flag to be displayed in a post of page using a [flag] shortcode.', 'azc_md'); ?></p>
				<p><?php _e('Format of shortcode is [flag=gb] to display the flag of the United Kingdom of Great Britain and Northern Ireland; 247 flags are included.', 'azc_md'); ?></p>
				<p><?php _e('Defintion of flags can be found at Wikipedia page ISO 3166-1 alpha-2: ', 'azc_md'); ?><a href='https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2'>https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2</a></p>
			</label>
			<label for="additional-plugins">
				azurecurve <?php _e('has the following plugins which allow shortcodes to be used in comments and widgets:', 'azc_md'); ?>
			</label>
			<ul class='azc_plugin_index'>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
						echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
						echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
	</div>
<?php }
if (!function_exists(azc_plugin_index_load_css)){
	function azc_plugin_index_load_css(){
		wp_enqueue_style( 'azurecurve_plugin_index', plugins_url( 'pluginstyle.css', __FILE__ ) );
	}
	add_action('admin_head', 'azc_plugin_index_load_css');
}

if (!function_exists(azc_plugin_menus)){
	function azc_plugin_menus() {
		echo "<h3>azurecurve Plugins";
		
		echo "<div style='display: block;'><h4>Active</h4>";
		echo "<span class='azc_plugin_index'>";
		if ( is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='admin.php?page=azc-bbcode' class='azc_plugin_index'>BBCode</a>";
		}
		if ( is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='admin.php?page=azc-cv' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='admin.php?page=azc-cl' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='admin.php?page=azc-dapc' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='admin.php?page=azc-fc' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='admin.php?page=azc-f' class='azc_plugin_index'>Flags</a>";
		}
		if ( is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='admin.php?page=azc-ffi' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='admin.php?page=azc-gpi' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='admin.php?page=azc-ig' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='admin.php?page=azc-md' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='admin.php?page=azc-msf' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='admin.php?page=azc-pi' class='azc_plugin_index'>Page Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='admin.php?page=azc-pa' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='admin.php?page=azc-rssf' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='admin.php?page=azc-rsss' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='admin.php?page=azc-si' class='azc_plugin_index'>Series Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='admin.php?page=azc-tc' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='admin.php?page=azc-ti' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='admin.php?page=azc-ts' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='admin.php?page=azc-t' class='azc_plugin_index'>Timelines</a>";
		}
		if ( is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='admin.php?page=azc-tsh' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</span></div>";
		echo "<p style='clear: both' />";
		
		echo "<div style='display: block;'><h4>Other Available Plugins</h4>";
		echo "<span class='azc_plugin_index'>";
		if ( !is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-bbcode/' class='azc_plugin_index'>BBCode</a>";
		}
		if ( !is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-comment-validator/' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-conditional-links/' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( !is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-display-after-post-content/' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( !is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-filtered-categories/' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( !is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-flags/' class='azc_plugin_index'>Flags</a>";
		}
		if ( !is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-floating-featured-image/' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( !is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-get-plugin-info/' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( !is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-insult-generator/' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-mobile-detection/' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( !is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-multisite-favicon/' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( !is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-page-index/' class='azc_plugin_index'>Page Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-posts-archive/' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-feed/' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-suffix/' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( !is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-series-index/' class='azc_plugin_index'>Series Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( !is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-tag-cloud/' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( !is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-taxonomy-index/' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-theme-switcher/' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( !is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-timelines/' class='azc_plugin_index'>Timelines</a>";
		}
		if ( !is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-toggle-showhide/' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</span></div>";
	}
}

?>