<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.homanchan.com
 * @since      1.0.0
 *
 * @package    Super_Lasso
 * @subpackage Super_Lasso/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="wrap">
	
	<h1 class="title"><?php echo esc_html(get_admin_page_title()) . ' Settings'; ?></h1>
    
    <form method="post" name="super_lasso_options" action="options.php">
    
		<?php
			$options = get_option($this->plugin_name);
			settings_fields($this->plugin_name);
			do_settings_sections($this->plugin_name);
		?>
	
    	<p>
    		In order for the plugin to obtain facebook data, it requires an app id, a secret key, and an access token.
    		<br/>
			For more information, consult the following link:
			<a href="https://developers.facebook.com/docs/apps/register#app-id" target="_blank">app id</a>
    	</p>
    
    	<h2 class="title">Facebook Details</h2>
    	<table class="form-table">
    		<tr>
    			<th>App ID</th>
    			<td><input type="text" name="<?php echo $this->plugin_name; ?>[sl_fb_appid]" value="<?php echo $options[ 'sl_fb_appid' ] ?>" size="40" /></td>
			</tr>
			<tr>
    			<th>Secret</th>
    			<td><input type="text" name="<?php echo $this->plugin_name; ?>[sl_fb_secret]" value="<?php echo $options[ 'sl_fb_secret' ] ?>" size="40" /></td>
			</tr>
			<tr>
    			<th>Access Token</th>
    			<td><input type="text" name="<?php echo $this->plugin_name; ?>[sl_fb_access_token]" value="<?php echo $options[ 'sl_fb_access_token' ] ?>" size="40" /></td>
			</tr>
			<tr>
    			<th>Page ID</th>
    			<td><input type="text" name="<?php echo $this->plugin_name; ?>[sl_fb_pageid]" value="<?php echo $options[ 'sl_fb_pageid' ] ?>" size="40" /></td>
			</tr>
		</table>
    <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
	
	</form>
</div>