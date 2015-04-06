<h1><?php echo $heading; ?></h1>
<?php echo Form::open(array('action' => 'save', 'method' => 'post')); ?>
<p>First Name: <?php echo Form::input(array('type' => 'text', 'name' => 'fname')); ?></p>
<p>Last Name: <?php echo Form::input(array('type' => 'text', 'name' => 'lname')); ?></p>
<p>Email: <?php echo Form::input(array('type' => 'text', 'name' => 'email')); ?></p>
<p><?php echo Form::input(array('type' => 'submit', 'name' => 'send', 'value' => 'Create user')); ?></p>
<?php echo Form::close(); ?>
