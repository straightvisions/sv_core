<?php
if ( $props['code_editor'] ) {
	if ( empty( $props['code_editor'] ) ) {
		$props['code_editor'] = 'css';
	}
}

$ID = str_replace(array('[',']'),array('_',''),$props['ID']); // brackets in ID are not supported in code editor, so replace with underscores
?>
<label for="<?php echo $ID; ?>">
	<textarea style="height:200px;"
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $props['name']; ?>"
		<?php echo $props['required']; ?>
		<?php echo $props['disabled']; ?>><?php echo esc_textarea($props['value']); ?></textarea>
</label>
<?php
echo '
<script>
	wp.codeEditor.initialize( "' . $ID . '", { mode: "' . $props['code_editor'] . '" } );
</script>';

?>
