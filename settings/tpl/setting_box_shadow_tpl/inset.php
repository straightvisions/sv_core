<div id="<?php echo $ID . '_inset'; ?>" class="sv_setting">
    <h4><?php _e( 'Shadow Style', 'sv100' ); ?></h4>
	<label for="<?php echo $ID . '_deco'; ?>" class="sv_setting_box_shadow_inset">
		<select class="sv_input">
		<?php 
			$shadow_style = array( 'outline', 'inset' );
			$shadow_style_val = $values[0] === 'inset' ? 'inset' : 'outline';
			
			foreach( $shadow_style as $style ) {
				echo '<option value="' . $style . '"';
				echo $shadow_style_val === $style ? ' selected' : '';
				echo '>' . $style . '</option>';
			}
		?>
		</select>
	</label>
</div>