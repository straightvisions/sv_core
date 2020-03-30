<?php 
	$border_left_width_number 	= intval( esc_attr( preg_replace('/[^0-9]/', '', $value['left_width'] ) ) );
    $border_left_width_unit     = esc_attr( preg_replace('/[0-9]+/', '', $value['left_width'] ) );
    $border_top_width_number 	= intval( esc_attr( preg_replace('/[^0-9]/', '', $value['right_width'] ) ) );
    $border_top_width_unit      = esc_attr( preg_replace('/[0-9]+/', '', $value['right_width'] ) );
    $border_right_width_number 	= intval( esc_attr( preg_replace('/[^0-9]/', '', $value['top_width'] ) ) );
    $border_right_width_unit    = esc_attr( preg_replace('/[0-9]+/', '', $value['top_width'] ) );
    $border_bottom_width_number = intval( esc_attr( preg_replace('/[^0-9]/', '', $value['bottom_width'] ) ) );
	$border_bottom_width_unit   = esc_attr( preg_replace('/[0-9]+/', '', $value['bottom_width'] ) );
?>

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
	.sv_setting_border td input {
		text-align:center;
	}
</style>
<div id="<?php echo $ID . '_width'; ?>" class="sv_setting">
    <h4><?php _e( 'Border Width', 'sv100' ); ?></h4>
    <table class="sv_setting_border">
        <tr>
            <td colspan="3">
                <label for="<?php echo $ID . '_left_width'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_left_width_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            if ( $unit !== '%' ) {
                                echo '<option value="' . $unit . '"';
                                echo $border_left_width_unit === $unit ? ' selected' : '';
                                echo '>' . $unit . '</option>';
                            }
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $ID . '_left_width'; ?>"
                        name="<?php echo $name . '[left_width]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $value['left_width'] ); ?>"
                    />
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label for="<?php echo $ID . '_top_width'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_top_width_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            if ( $unit !== '%' ) {
                                echo '<option value="' . $unit . '"';
                                echo $border_top_width_unit === $unit ? ' selected' : '';
                                echo '>' . $unit . '</option>';
                            }
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $ID . '_top_width'; ?>"
                        name="<?php echo $name . '[top_width]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $value['top_width'] ); ?>"
                    />
                </label>
            </td>
            <td style="width:100px;height:100px;">Content</td>
            <td>
                <label for="<?php echo $ID . '_bottom_width'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_bottom_width_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            if ( $unit !== '%' ) {
                                echo '<option value="' . $unit . '"';
                                echo $border_bottom_width_unit === $unit ? ' selected' : '';
                                echo '>' . $unit . '</option>';
                            }
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $ID . '_bottom_width'; ?>"
                        name="<?php echo $name . '[bottom_width]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $value['bottom_width'] ); ?>"
                    />
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label for="<?php echo $ID . '_right_width'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_right_width_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            if ( $unit !== '%' ) {
                                echo '<option value="' . $unit . '"';
                                echo $border_right_width_unit === $unit ? ' selected' : '';
                                echo '>' . $unit . '</option>';
                            }
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $ID . '_right_width'; ?>"
                        name="<?php echo $name . '[right_width]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $value['right_width'] ); ?>"
                    />
                </label>
            </td>
        </tr>
    </table>
</div>