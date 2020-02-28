<h4><?php echo $title; ?></h4>
<?php
if ( is_string( $value ) && ! empty( $value ) ) {
	$attachment = wp_get_attachment_link( $value, 'full', false, true )
	? wp_get_attachment_link( $value, 'full', false, true ) : false;

	if ( $attachment ) {
		?>
		<div><?php echo $attachment; ?></div>
		<div>
			<a href="/wp-admin/post.php?post=<?php echo $value; ?>&action=edit" target="_blank"><?php echo get_the_title( $value ); ?></a>
		</div>
<?php
	}
}
?>
<label for="<?php echo $ID; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_file"
		id="<?php echo $ID; ?>[file]"
		name="<?php echo ($name ? $name.'[file]' : ''); ?>"
		type="file"
		<?php echo ((count($this->get_allowed_filetypes()) > 0) ? 'accept="'.implode(',',$this->get_allowed_filetypes()).'"' : ''); ?>
		placeholder="<?php echo $placeholder; ?>"
		<?php echo $disabled; ?>
	/>
</label>
<div class="description"><?php echo $description; ?></div>
<?php
if( is_string( $value ) && ! empty( $value ) ) {
?>
<label for="<?php echo $ID; ?>[delete]" style="justify-content: flex-end;">
	<input
		data-sv_type="sv_form_field"
		id="<?php echo $ID; ?>[delete]"
		name="<?php echo ($name ? $name.'[delete]' : ''); ?>"
		value="1"
		type="checkbox"
		<?php echo $disabled; ?>
	style="margin-right:16px;"
	/>
	<?php echo __('Delete File', 'sv_core'); ?>
</label>
<?php
}
?>