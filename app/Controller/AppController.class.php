<?php

class AppController extends Controller
{
	protected $_plugins = array('Transfer', 'Session','Cookie');
	protected $_helpers = array('Html');

	protected function ajaxReturn($data)
	{
		$this->set('ajaxData', $data);
		$this->layout = 'ajax';
	}
}