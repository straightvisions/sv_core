<?php
	if( isset( $_POST[ 'logs_delete' ] ) && isset( $_POST[ 'logs' ] )) {
		if( !empty( $_POST[ 'logs' ] ) ) {
			$this->delete_logs( $_POST[ 'logs' ] );
		}
	}
	
	if( isset( $_POST[ 'test_logs' ] ) ) {
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Success Log 1' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'success' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Success Log 2' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'success' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Success Log 3' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'success' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Success Log 4' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'success' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Success Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'success' );

		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Info Log 1' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'info' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Info Log 2' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'info' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Info Log 4' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'info' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Info Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'info' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Info Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'info' );

		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Warning Log 1' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'warning' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Warning Log 2' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'warning' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Warning Log 3' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'warning' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Warning Log 4' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'warning' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Warning Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'warning' );

		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Error Log 1' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'error' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Error Log 2' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'error' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Error Log 3' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'error' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Error Log 4' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'error' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Error Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'error' );

		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Critical Log 1' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'critical' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
							->set_title( 'Critical Log 2' )
							->set_desc( 'Public Description' )
							->set_desc( 'Admin Description', 'admin' )
							->set_state( 'critical' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Critical Log 3' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'critical' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Critical Log 4' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'critical' );
		$this->get_root()::$log->create->log( $this, __FILE__ )
		                    ->set_title( 'Critical Log 5' )
		                    ->set_desc( 'Public Description' )
		                    ->set_desc( 'Admin Description', 'admin' )
		                    ->set_state( 'critical' );
	}
?>
<form method="POST">
	<div class="sv_setting">
		<button type="submit" name="test_logs" class="sv_btn">Create Test Logs</button>
		<p><i>Will be removed on staging/master.</i></p>
		<br>
		<br>
	</div>
</form>
<div class="sv_log">
	<div class="log_summary">
		<table>
			<tr>
				<td><button type="button" id="logs_filter"></button></td>
			</tr>
			<tr>
				<td><i class="fas fa-box-open"></i></i></td>
				<td><?php echo count( $this->get_logs() ); ?></td>
			</tr>
			<tr>
				<td><i class="fas fa-check"></i></td>
				<td><?php echo count( $this->filter->states( 1 )->output() ); ?></td>
			</tr>
			<tr>
				<td><i class="fas fa-info"></i></td>
				<td><?php echo count( $this->filter->states( 2 )->output() ); ?></td>
			</tr>
			<tr>
				<td><i class="fas fa-exclamation-triangle"></i></td>
				<td><?php echo count( $this->filter->states( 3 )->output() ); ?></td>
			</tr>
			<tr>
				<td><i class="fas fa-times"></i></td>
				<td><?php echo count( $this->filter->states( 4 )->output() ); ?></td>
			</tr>
			<tr>
				<td><i class="fas fa-skull-crossbones"></i></td>
				<td><?php echo count( $this->filter->states( 5 )->output() ); ?></td>
			</tr>
		</table>
	</div>
	<div class="log_list">
		<table>
			<form method="post">
				<tr>
					<th><button type="submit" id="logs_delete" name="logs_delete"><i class="far fa-trash-alt"></i></button></th>
					<th class="select"><input type="checkbox" id="logs_select"></th>
					<th class="title">Title</th>
					<th class="group">Group</th>
					<th class="state">State</th>
					<th class="last_call">Last Call</th>
					<th class="calls">Calls</th>
				</tr>
				<?php
					foreach ( $this->get_logs() as $log ) {
						$last_call					= new DateTime( $log->post_date_gmt );
				?>
				<tr class="log" id="<?php echo $log->ID; ?>">
					<td class="icon <?php echo strtolower( $this->get_state( $log->ID ) ); ?>"></td>
					<td class="select"><input type="checkbox" name="logs[]" value="<?php echo $log->ID; ?>"></td>
					<td class="title"><?php echo $log->post_title; ?></td>
					<td class="group"><?php echo $this->get_group( $log->ID ); ?></td>
					<td class="state"><?php echo $this->get_state( $log->ID ); ?></td>
					<td class="last_call"><?php echo $last_call->format('H:i:s - d.m.Y'); ?></td>
					<td class="calls"><?php echo $this->get_meta( $log->ID, 'calls', true ); ?></td>
				</tr>
				<?php
					}
				?>
			</form>
		</table>
	</div>
	<div class="log_details">
		<h3>Log Details</h3>
		<?php
			foreach ( $this->get_logs() as $log ) {
				$first_call							= new DateTime( $log->post_date_gmt );
				$last_call							= new DateTime( $log->post_modified_gmt );
		?>
		<table id="log_<?php echo $log->ID; ?>">
			<?php  if( is_admin() ) { ?>
			<tr>
				<th>Log ID:</th>
				<td><?php echo $log->ID; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<th>Title:</th>
				<td><?php echo $log->post_title; ?></td>
			</tr>
			<tr>
				<th>Group:</th>
				<td><?php echo $this->get_group( $log->ID ); ?></td>
			</tr>
			<tr>
				<th>State:</th>
				<td><?php echo $this->get_state( $log->ID ); ?></td>
			</tr>
			<tr>
				<th>Calls:</th>
				<td><?php echo $this->get_meta( $log->ID, 'calls', true ); ?></td>
			</tr>
			<tr>
				<th>First Call:</th>
				<td><?php echo $last_call->format('H:i:s - d.m.Y'); ?></td>
			</tr>
			<tr>
				<th>Last Call:</th>
				<td><?php echo $first_call->format('H:i:s - d.m.Y'); ?></td>
			</tr>
			<?php  if( is_admin() ) { ?>
			<tr>
				<th>File Path:</th>
				<td><?php echo $this->get_meta( $log->ID, 'file_path', true ); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<th>Description:</th>
				<td><?php echo $this->get_meta( $log->ID, 'desc_public', true ); ?></td>
			</tr>
			<?php  if( is_admin() ) { ?>
			<tr>
				<th>Admin Description:</th>
				<td><?php echo $this->get_meta( $log->ID, 'desc_admin', true ); ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php } ?>
	</div>
	<div class="log_filter">
		<h3>Log Filter</h3>
		<div class="wrapper">
			<label class="title">
				<b>Filter by title:</b>
				<input type="text" id="filter_title" class="sv_input" placeholder="Log Title">
				<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">Filter all logs by their title.</div>
			</label>
		</div>
	</div>
</div>
