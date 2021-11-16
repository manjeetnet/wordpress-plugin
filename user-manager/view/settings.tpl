<link type='text/css' href='<?php echo FILE_CSS ?>' rel='stylesheet' />
<div class='wrap'>
<h2>User Managment API - Settings</h2>
<h3><?php echo $table_name ?></h3>
	
<?php 
	if ($status == "error") {
		echo "<div class='error'><p>$message</p></div>";
	} else if ($status == "success") {
		echo "<div class='updated'><p>$message</p></div>";
	}
?>
		
		<form method='post' name='settings' action='<?php echo $this->url['update_settings'] ?>'>
		<?php
		wp_nonce_field( 'settings','update');
		?>
		<table class='wp-list-table widefat fixed'>
		
		<tr><th class='simple-table-manager'>API URL (Endpoint)</th><td><input type='text' name='api_url' value='<?php echo $this->csv['api_url'] ?>'/></td></tr>

		<tr><th class='simple-table-manager'>Key (Token)</th><td><input
		type='text' name='api_key' value='<?php echo $this->csv['api_key'] ?>'/></td></tr>
		
		<tr><th class='simple-table-manager'>Enable API</th><td><input type='checkbox' name='api_enable' value='<?php echo $this->csv['api_enable']?>' <?php echo $this->csv['api_enable']==1 ? 'checked' :''; ?>/></td></tr>
		
		
		</table>
		<div class="tablenav bottom">
		<input type='submit' name='apply' value='Apply Changes' class='button button-primary' />&nbsp;
		<input type='submit' name='restore' value='Restore Defaults' class='button' />
		</div>
		</form>
		</div>

</div>