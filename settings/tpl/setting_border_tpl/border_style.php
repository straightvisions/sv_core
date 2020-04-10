<?php $border_style_options = array( 'none', 'solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset' ); ?>

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

<div id="<?php echo $props['ID'] . '_style'; ?>" class="sv_setting">
    <h4><?php _e( 'Border Style', 'sv100' ); ?></h4>
    <table class="sv_setting_border">
        <tr>
            <td colspan="3">
                <label for="<?php echo $props['ID'] . '_left_style'; ?>">
                    <select
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_left_style'; ?>"
                        name="<?php echo $props['name'] . '[left_style]'; ?>"
                    >
                    <?php  
                        foreach( $border_style_options as $style ) {
                            echo '<option value="' . $style . '"';
                            echo $props['value']['left_style'] === $style ? ' selected' : '';
                            echo '>' . $style . '</option>';
                        }
                    ?>
                    </select>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label for="<?php echo $props['ID'] . '_top_style'; ?>">
                    <select
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_top_style'; ?>"
                        name="<?php echo $props['name'] . '[top_style]'; ?>"
                    >
                    <?php  
                        foreach( $border_style_options as $style ) {
                            echo '<option value="' . $style . '"';
                            echo $props['value']['top_style'] === $style ? ' selected' : '';
                            echo '>' . $style . '</option>';
                        }
                    ?>
                    </select>
                </label>
            </td>
            <td style="width:100px;height:100px;">Content</td>
            <td>
                <label for="<?php echo $props['ID'] . '_bottom_style'; ?>">
                    <select
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_bottom_style'; ?>"
                        name="<?php echo $props['name'] . '[bottom_style]'; ?>"
                    >
                    <?php  
                        foreach( $border_style_options as $style ) {
                            echo '<option value="' . $style . '"';
                            echo $props['value']['bottom_style'] === $style ? ' selected' : '';
                            echo '>' . $style . '</option>';
                        }
                    ?>
                    </select>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label for="<?php echo $props['ID'] . '_right_style'; ?>">
                    <select
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_right_style'; ?>"
                        name="<?php echo $props['name'] . '[right_style]'; ?>"
                    >
                    <?php  
                        foreach( $border_style_options as $style ) {
                            echo '<option value="' . $style . '"';
                            echo $props['value']['right_style'] === $style ? ' selected' : '';
                            echo '>' . $style . '</option>';
                        }
                    ?>
                    </select>
                </label>
            </td>
        </tr>
    </table>
</div>