<div class="col-50">
	<h3 class="divider">Example</h3>
	<code>
		static::$log->create->log( $this, __FILE__ )<br>
		<span style="margin-left: 114px">->set_title( 'Success Log' )<br>
		<span style="margin-left: 114px">->set_desc( 'Public Description' )<br>
		<span style="margin-left: 114px">->set_desc( 'Admin Description', 'admin' )<br>
		<span style="margin-left: 114px">->set_state( 'success' );<br>
	</code>
</div>
<div class="col-50">
	<h3 class="divider">Init a new log</h3>
	<code>static::$log->create->log( $this, __FILE__ )</code>
</div>
<div class="col-50">
	<h3 class="divider">Set the log title</h3>
	<code>->set_title( 'Log Title' )</code>
</div>
<div class="col-50">
	<h3 class="divider">Set a public description</h3>
	<code>->set_desc( 'Public Description' )</code>
</div>
<div class="col-50">
	<h3 class="divider">Set a admin description</h3>
	<p>This description is only visible for admins.</p>
	<code>->set_desc( 'Admin Description', 'admin )</code>
</div>
<div class="col-50">
	<h3 class="divider">Set the log state</h3>
	<code>->set_state( 'success' )</code>
	<p>Available states:</p>
	<ol>
		<li>success</li>
		<li>info</li>
		<li>warning</li>
		<li>error</li>
		<li>critical</li>
	</ol>
</div>