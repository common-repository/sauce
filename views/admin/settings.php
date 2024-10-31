<div id="sauce-wrap" class="wrap">
<img src="<?php echo SAUCE_PLUGIN_URL; ?>/images/profile.png" border="0" class="sauce-profile-img" />
<h2><?php echo SAUCE_PUGIN_NAME; ?> Wordpress Plugin (v<?php echo SAUCE_CURRENT_VERSION; ?>)</h2>
<br/>
<br/>
<hr>

<?php if (!empty($errors)) { 
	foreach ($errors as $error) { ?>
		<div class="sauce-notice sauce-notice-yellow"><strong>Error:</strong> <?php echo $error; ?></div>
	<?php }
} ?>

<?php if (!get_option('sauce_client_id')) { ?>
	<div class="sauce-notice sauce-notice-yellow">
		Don't have your account details yet? Sign up for an account at <a href="http://www.sauceapp.io" target="blank">sauceapp.io</a>.
	</div>
<?php } ?>



<div id="sauce-left">


	<div class="sauce-admin-box">
		<div class="sauce-inner">


		<form method="post" action="options.php">
		
		<?php settings_fields( 'sauce-settings-group' ); ?>
		
		<!-- Need to have all group option inputs in -->
		
		<table class="form-table">
		
			<tr><td class="heading"><h3>General settings</h3></td></tr>

			<tr valign="top">
			<th scope="row">Sauce Client ID</th>
			<td>
				<input type="text" name="sauce_client_id"  class="regular-text"  value="<?php echo get_option('sauce_client_id'); ?>" />
				<span class="description">e.g. 52c8825e06aedc9a3000032312</span>
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Facebook App ID</th>
			<td>
				<input type="text" name="sauce_app_id"  class="regular-text"  value="<?php echo get_option('sauce_app_id'); ?>" />
				<span class="description">e.g. 123214231289840</span>
			</td>
			</tr>

			
			<tr>
				<td colspan="2">
					<hr>
				</td>
			</tr>
			
			
			<tr><td class="heading"><h3>Custom post types</h3></td></tr>
			
			<tr><td colspan="2"><p>By default reads are enabled for "posts". If you wish to enable reads for other post types, check the appropriate boxes.</p></td></tr>
			
			<?php foreach ($custom_posts as $type) { ?>
				<tr valign="top">
				<th scope="row"><?php echo ucfirst($type); ?></th>
				<td>
					<?php if ($type == 'post') { ?>
						<input type="checkbox" name="sauce_custom_<?php echo $type; ?>" <?php if (get_option('sauce_custom_'.$type, true)) { ?>checked="checked"<?php } ?> />
					<?php } else { ?>
						<input type="checkbox" name="sauce_custom_<?php echo $type; ?>" <?php if (get_option('sauce_custom_'.$type, false)) { ?>checked="checked"<?php } ?> />
					<?php } ?>
				</td>
				</tr>
			<?php } ?>

	

			
		</table>
		<p class="submit"> <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>
		
		
			
		</div>
	</div>



</div>



</div>