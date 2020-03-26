<div id="<?php echo $ID . '_weight'; ?>" class="sv_setting">
    <h4><?php _e( 'Font Weight', 'sv100' ); ?></h4>
	<label for="<?php echo $ID . '_weight'; ?>">
		<select
			data-sv_type="sv_form_field"
			class="sv_input"
			id="<?php echo $ID . '_weight'; ?>"
			name="<?php echo $name . '[weight]'; ?>"
		>
		<?php 
			for( $x = 1; $x < 10; $x++ ) {
				echo '<option value="' . $x . '00"';
				echo $value['weight'] === $x . '00' ? ' selected' : '';
				echo '>' . $x . '00</option>';
			}
		?>
		</select>
	</label>
</div>