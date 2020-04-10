<?php 
	$border_top_left_radius_number 	    = intval( esc_attr( preg_replace('/[^0-9]/', '', $props['value']['top_left_radius'] ) ) );
    $border_top_left_radius_unit        = esc_attr( preg_replace('/[0-9]+/', '', $props['value']['top_left_radius'] ) );
    $border_top_right_radius_number 	= intval( esc_attr( preg_replace('/[^0-9]/', '', $props['value']['top_right_radius'] ) ) );
    $border_top_right_radius_unit       = esc_attr( preg_replace('/[0-9]+/', '', $props['value']['top_right_radius'] ) );
    $border_bottom_left_radius_number   = intval( esc_attr( preg_replace('/[^0-9]/', '', $props['value']['bottom_left_radius'] ) ) );
	$border_bottom_left_radius_unit     = esc_attr( preg_replace('/[0-9]+/', '', $props['value']['bottom_left_radius'] ) );
    $border_bottom_right_radius_number 	= intval( esc_attr( preg_replace('/[^0-9]/', '', $props['value']['bottom_right_radius'] ) ) );
    $border_bottom_right_radius_unit    = esc_attr( preg_replace('/[0-9]+/', '', $props['value']['bottom_right_radius'] ) );
?>

<style>
	.sv_setting_border {
		width: 100%;
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
		text-align: center;
	}
</style>
<div id="<?php echo $props['ID'] . '_radius'; ?>" class="sv_setting">
    <h4><?php _e( 'Border Radius', 'sv100' ); ?></h4>
    <table class="sv_setting_border">
        <tr>
            <td>
                <label for="<?php echo $props['ID'] . '_top_left_radius'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_top_left_radius_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            echo '<option value="' . $unit . '"';
                            echo $border_top_left_radius_unit === $unit ? ' selected' : '';
                            echo '>' . $unit . '</option>';
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_top_left_radius'; ?>"
                        name="<?php echo $props['name'] . '[top_left_radius]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $props['value']['top_left_radius'] ); ?>"
                    />
                </label>
            </td>
            <td style="border:none"></td>
            <td>
                <label for="<?php echo $props['ID'] . '_top_right_radius'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_top_right_radius_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            echo '<option value="' . $unit . '"';
                            echo $border_top_right_radius_unit === $unit ? ' selected' : '';
                            echo '>' . $unit . '</option>';
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_top_right_radius'; ?>"
                        name="<?php echo $props['name'] . '[top_right_radius]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $props['value']['top_right_radius'] ); ?>"
                    />
                </label>
            </td>
        </tr>
        <tr>
            <td style="border:none"></td>
            <td style="width:100px;height:100px;">Content</td>
            <td style="border:none"></td>
        </tr>
        <tr>
            <td>
                <label for="<?php echo $props['ID'] . '_bottom_left_radius'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_bottom_left_radius_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            echo '<option value="' . $unit . '"';
                            echo $border_bottom_left_radius_unit === $unit ? ' selected' : '';
                            echo '>' . $unit . '</option>';
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_bottom_left_radius'; ?>"
                        name="<?php echo $props['name'] . '[bottom_left_radius]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $props['value']['bottom_left_radius'] ); ?>"
                    />
                </label>
            </td>
            <td style="border:none"></td>
            <td>
                <label for="<?php echo $props['ID'] . '_bottom_right_radius'; ?>">
                    <input
                        class="sv_input"
                        type="number"
                        value="<?php echo $border_bottom_right_radius_number; ?>"
                        min="0"
                    />
                    <select class="sv_input_units">
                    <?php 
                        foreach( $this->get_units() as $unit ) {
                            echo '<option value="' . $unit . '"';
                            echo $border_bottom_right_radius_unit === $unit ? ' selected' : '';
                            echo '>' . $unit . '</option>';
                        }
                    ?>
                    </select>
                    <input
                        data-sv_type="sv_form_field"
                        class="sv_input"
                        id="<?php echo $props['ID'] . '_bottom_right_radius'; ?>"
                        name="<?php echo $props['name'] . '[bottom_right_radius]'; ?>"
                        type="hidden"
                        value="<?php echo esc_attr( $props['value']['bottom_right_radius'] ); ?>"
                    />
                </label>
            </td>
        </tr>
    </table>
</div>