<?php $tpl_path = $this->get_path_core( 'settings/tpl/' . $this->run_type()->get_module_name() . '_tpl/' ); ?>
<style>
	.sv_setting_border {
		width:100%;
	}
	.sv_setting_border td {
		border: 1px solid #000;
		min-width: 50px;
		height: 20px;
		text-align: center;
	}
	.sv_setting_border td label {
		margin: 10px !important;
	}
	.sv_setting_border td select {
		margin: 0 auto;
	}
</style>
<div class="sv_setting_flex">
	<?php 
		require( $tpl_path . 'border_width.php' ); 
		require( $tpl_path . 'border_style.php' ); 
	?>
</div>
<div class="sv_setting_flex">
	<?php
		require( $tpl_path . 'border_radius.php' );
		require( $tpl_path . 'border_color.php' );
	?>
</div>