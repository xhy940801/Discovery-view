<?php
function __autoload($className)
{
	if(substr($className, -10) == 'Controller')
		App::import($className, 'Controller');
	else
		throw new Exception('Class: ' . $className . ' not found!');
}