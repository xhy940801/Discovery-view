<?php

class AppController extends Controller
{
	protected $_plugins = array('Transfer', 'Session','Cookie');
	protected $_helpers = array('Html');
}