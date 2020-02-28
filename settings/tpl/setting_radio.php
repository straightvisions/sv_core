<h4><?php echo $title; ?></h4>

<?php
echo ( $radio_style == 'switch'
? '<div class="sv_radio_switch_wrapper"><label for="' . $ID . '"><div class="switch_field">'
			: '' );

			foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
			echo ( $radio_style == 'switch' ? '' : '<label for="' . $ID . '">' );
				echo
				'<input
					name="' . $name . '"
					id="' . $ID . '"
					type="radio"
					data-sv_type="sv_form_field"
					class="' . ( ( $o_value < 1 ) ? 'off' : 'on' ) . '"
					value="' . $o_value . '"
				' . $disabled . '
				' . ( ( $o_value == $value ) ? ' checked="checked" ' : '' ) . '
				/>
				<span class="name">' . $o_name . '</span>';
				echo ( $radio_style == 'switch' ? '' : '</label>' );
			}

echo ( $radio_style == 'switch' ? '</div></label></div>' : '' );
			?>
<div class="description"><?php echo $description; ?></div>