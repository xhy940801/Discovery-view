<?php

class App
{
	static private $_includedFileMap = array(__FILE__ => true);

	static public function import($className, $module, $basePath = __APP_DIR__, $prefix = '.class.php')
	{
		$path = __ROOT__ . DIRECTORY_SEPARATOR . $basePath . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . $prefix;
		if(isset(self::$_includedFileMap[$path]))
			return;
		require($path);
		self::$_includedFileMap[$path] = true;
	}

	static public function uses($className, $module, $basePath = __APP_DIR__, $prefix = '.class.php')
	{
		$path = __ROOT__ . DIRECTORY_SEPARATOR . $basePath . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . $prefix;
		self::$_includedFileMap[$path] = true;
		return $path;
	}

	static public function getConf()
	{
		return require(__ROOT__ . DIRECTORY_SEPARATOR . __APP_DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'conf.php');
	}

	static public function dispacher($url, $conf)
	{
		$urlArr = explode('/', $url);

		$dispacherArr = array('controller' => $conf['defaultController'], 'action' => $conf['defaultAction']);

		if(!empty($urlArr[1]))
			$dispacherArr['controller'] = $urlArr[1];
		if(!empty($urlArr[2]))
			$dispacherArr['action'] = $urlArr[2];

		$controllerName = $dispacherArr['controller'] . 'Controller';
		$controller = new $controllerName();
		$controller->_initController($dispacherArr['controller'], $dispacherArr['action'], $conf);
		$controller->_doAction(array_slice($urlArr, 3));
	}
}