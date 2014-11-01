<?php
class View
{
	private $_helpers;
	private $_fields;
	private $_layout;
	private $_ctpname;
	private $_controller;

	public function __construct($helpers, $fields, $layout, $ctpname, $controller)
	{
		$this->_helpers = $helpers;
		$this->_fields = $fields;
		$this->_layout = $layout;
		$this->_ctpname = $ctpname;
		$this->_controller = $controller;
	}

	public function render()
	{
		foreach ($this->_fields as $key => $value)
			$$key = $value;
		foreach ($this->_helpers as $helper)
		{
			App::import($helper . 'Helper', 'Helper');
			$helperTrueClassName = $helper . 'Helper';
			$this->$helper = new $helperTrueClassName();
		}
		require(App::uses($this->_layout, 'View' . DIRECTORY_SEPARATOR . 'Layouts', __APP_DIR__, '.ctp'));
	}

	public function printContent()
	{
		foreach ($this->_fields as $key => $value)
			$$key = $value;
		require(App::uses($this->_ctpname, 'View' . DIRECTORY_SEPARATOR . $this->_controller, __APP_DIR__, '.ctp'));
	}
}