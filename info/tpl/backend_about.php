<?php
	$this->get_root()->acp_style();
?>
<h1><?php _e('About', $this->get_module_name()); ?></h1>
<p><?php _e('Our themes and plugins share a core which provides commonly used features. The core is included and shared within each plugin or theme, so make sure if you update one product, to update all others too.', $this->get_module_name()); ?></p>
<h2><?php _e('Active Instances', $this->get_module_name()); ?></h2>
<code>$this->get_instances();</code>
<blockquote>
	<table>
		<tr>
			<th><?php _e('Name', $this->get_module_name()); ?></th>
			<th><?php _e('Version', $this->get_module_name()); ?></th>
			<th><?php _e('Core', $this->get_module_name()); ?></th>
			<th><?php _e('Path', $this->get_module_name()); ?></th>
		</tr>
	<?php
		foreach($this->get_instances() as $name => $instance){
			echo '<tr>';
			echo '<td>'.$name.'</td>';
			echo '<td>'.$instance->get_version(true).'</td>';
			echo '<td>'.$instance->get_version_core_match(true).'</td>';
			echo '<td>'.$instance->get_path().'</td>';
			echo '</tr>';
		}
	?>
	</table>
</blockquote>
<h2><?php _e('Active Core', $this->get_module_name()); ?></h2>
<p><?php _e('While the core is included in each package, it will be loaded from one of these packages only.', $this->get_module_name()); ?></p>
<h3><?php _e('Path to active core', $this->get_module_name()); ?></h3>
<blockquote><?php echo $this->get_path_lib_core(); ?></blockquote>
<h4><?php _e('Version of active core', $this->get_module_name()); ?></h4>
<blockquote><?php echo number_format(self::version_core,0,',','.'); ?></blockquote>

<h2><?php _e('Core Methods', $this->get_module_name()); ?></h2>
<h3><?php _e('Hierarchy', $this->get_module_name()); ?></h3>
<h4><?php _e('get root', $this->get_module_name()); ?></h4>
<p><?php _e('All following core methods are set into the root class. You can access the root class via the following code:', $this->get_module_name()); ?></p>
<code>$this->get_root();</code>
<h4><?php _e('get parent', $this->get_module_name()); ?></h4>
<p><?php _e('If you just want to go a level above to get methods from parent class, just use the following code:', $this->get_module_name()); ?></p>
<code>$this->get_parent();</code>
<p><?php _e('If you reach root class with a get_parent-chain, the root class will always be returned. e.g. if there is a three-level-hierarchy and you call get_parent four times, root class will be returned.', $this->get_module_name()); ?></p>
<code>
	// <?php _e('we are in the third hierarchy position and all of these calls would return the root class', $this->get_module_name()); ?><br />
	$this->get_root() === $this->get_parent()->get_parent(); // true<br />
	$this->get_root() === $this->get_parent()->get_parent()->get_parent; // true<br />
	$this->get_root() === $this->get_parent()->get_parent()->get_parent->get_parent(); // true<br />
	$this->get_root() === $this->get_parent()->get_parent()->get_parent->get_parent()->get_parent(); // true
</code>
<h4><?php _e('find parent', $this->get_module_name()); ?></h4>
<p><?php _e('You can search for a parent class. With $qualified set to true, you need to prepend namespace of target class. Returns false if such a parent class does not exist.', $this->get_module_name()); ?></p>
<code>$this->find_parent($class_name,$qualified);</code>