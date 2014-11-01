<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>WoPict | Welcome</title>
		<?php echo $this->Html->css('foundation'); ?>
		<?php echo $this->Html->script('vendor/modernizr'); ?>
		<?php echo $this->Html->script('vendor/jquery'); ?>
		<?php echo $this->Html->script('foundation.min'); ?>
	</head>
	<body>
		<?php $this->printContent();?>
		<script type="text/javascript">$(document).foundation();</script>
	</body>
</html>