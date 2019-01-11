<?php
/*
Plugin Name: azurecurve Flags
Plugin URI: http://development.azurecurve.co.uk/plugins/flags

Description: Allows a 16x16 flag to be displayed in a post of page using a shortcode.
Version: 2.1.1

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
		$flag = trim ( trim ( trim ( trim ( trim ( $attribs , '=' ) , '"' ) , "'" ) , '&#8217;' ) , "&#8221;" );
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

// Add Action Link
function azc_f_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=azc-f">'.__('Settings' ,'azc-i').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'azc_f_plugin_action_links', 10, 2);

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
			<p>
			Available flags are:
				
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
								echo "<div style='width: 180px; display: inline-block;'><img src='";
								echo plugin_dir_url(__FILE__) . "images/$filewithoutext.png;' title='$filewithoutext' alt='$filewithoutext' />&nbsp;<em>$filewithoutext</em></div>";
							}
						}
						closedir($directory);
					}
				}
				?>
				
			</p>
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

?>