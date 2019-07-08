<?php if( current_user_can( 'activate_plugins' ) ) { ?>
	<section id="section_legal" class="sv_admin_section">
		<div class="section_head section_legal">
			<div class="textbox">
				<h1 class="section_title"><?php _e('Legal Information', 'sv_core'); ?></h1>
			</div>
		</div>
		<div class="section_content">
			<h3 class="divider"><?php _e('General Information', 'sv_core'); ?></h3>
			<ul class="info_list">
				<li><?php _e('Project Website:', 'sv_core'); ?><span> <a href="https://straightvisions.com/" target="_blank"><?php _e('straightvisions GmbH', 'sv_core'); ?></a></span></li>
			</ul>
			<h3 class="divider"><?php _e('License: GPL3 or later', 'sv_core'); ?></h3>
			<p>
				This program is free software:<br>
				You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.<br><br>

				This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.<br>
				See the GNU General Public License for more details.<br><br>

				You should have received a copy of the GNU General Public License along with this program.<br>
				If not, See <a href="http://www.gnu.org/licenses/" target="_blank">http://www.gnu.org/licenses/</a>.
			</p>

			<h3 class="divider"><?php _e('Privacy Statement', 'sv_core'); ?></h3>
			<?php echo $this->get_section_privacy(); ?>
		</div>
	</section>
<?php }