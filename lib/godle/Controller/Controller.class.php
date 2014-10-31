<?php

class Controller
{
	public $context;

	protected $_helpers = array();
	protected $_plugins = array();
	protected $layout;

	protected $_config = array();

	private $_viewName;
	private $_viewFields = array();

	public $param;

	public function _initController($controller, $action, $config)
	{
		$this->initPlugins($this->_plugins);
		$this->param = array(
			'controller' => $controller,
			'action' => $action
			);
		$this->_viewName = $action;
		$this->_config = $config;
		$this->layout = $config['defaultLayout'];
	}

	public function _doAction($actionParams)
	{
		$this->_beforeAction();
		call_user_func_array(array($this, $this->param['action']), $actionParams);
		$this->_afterAction();
		$this->_render();
	}

	protected function render($ctpname)
	{
		$this->_viewName = $ctpname;
	}

	protected function initPlugins($plugins)
	{
		foreach ($plugins as $plugin)
		{
			App::importimport($plugin, $plugin, $this->_config['pluginPath']);
			$this->$plugin = new $plugins();
		}
	}

	protected function set($key, $value)
	{
		$this->_viewFields[$key] = $value;
	}

	protected function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	protected function getPost($name = null)
	{
		if(isset($name))
			return $_POST[$name];
		return $_POST;
	}

	protected function isGet()
	{
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	protected function getGet($name = null)
	{
		if(isset($name))
			return $_GET[$name];
		return $_GET;
	}

	protected function redirect($url, $parmas = array())
	{
		$realUrl;
		if(is_string($url))
			$realUrl = $url;
		else
		{
			$realUrl = __HOST_URL__ . '/' . $url['controller'] . '/' . $url['action'];
			foreach ($parmas as $value)
				$realUrl .= ('/' . $value);
		}
		header('Location: ' . $realUrl);
		exit();
	}

	protected function _beforeAction()
	{

	}

	protected function _afterAction()
	{

	}

	protected function _beforeRender()
	{

	}

	protected function _afterRender()
	{

	}

	protected function _finalize()
	{

	}

	public function _render()
	{
		$this->_beforeRender();

		App::import('View', 'View', 'lib' . DIRECTORY_SEPARATOR . 'godle');
		$view = new View($this->_helpers, $this->_viewFields, $this->layout, $this->_viewName, $this->param['controller']);
		$view->render();

		$this->_afterRender();
	}
}