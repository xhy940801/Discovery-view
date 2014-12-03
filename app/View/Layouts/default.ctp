<!doctype html>
<html class="no-js" lang="en">
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=G6t1cqDFWQBUr5GODv2naEqS"></script>
<?php 
	echo $this->Html->css('foundation');
	echo $this->Html->css('modal');
	echo $this->Html->css('map');
	echo $this->Html->css('widget');
	echo $this->Html->css('overlay');
	echo $this->Html->script('overlay');
	echo $this->Html->script('map');
	echo $this->Html->script('vendor/modernizr');
	echo $this->Html->script('vendor/jquery');
	echo $this->Html->script('foundation.min');

	$this->printContent();
?>
</html>