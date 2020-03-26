<?php 
	if ( 
		'sv100' === $this->get_root()->get_name() 
		&& $this->get_root()->get_module( 'sv_webfontloader' ) 
	)  { 
?>
<div id="<?php echo $ID . '_family'; ?>" class="sv_setting">
    <h4><?php _e( 'Font Family', 'sv100' ); ?></h4>
	<label for="<?php echo $ID . '_family'; ?>">
		<select
			data-sv_type="sv_form_field"
			class="sv_input"
			id="<?php echo $ID . '_family'; ?>"
			name="<?php echo $name . '[family]'; ?>"
		>
		<?php
			foreach( 
				$this->get_root()->get_module( 'sv_webfontloader' )->get_font_options() 
				as $o_value => $o_name 
			) {
				echo '<option
				' . ( ( $value['family'] == $o_value ) ? ' selected="selected"' : '' ) . '
				value="' . $o_value . '">' . $o_name . '</option>';
			}
		?>
		</select>
	</label>
</div>
<?php } ?>