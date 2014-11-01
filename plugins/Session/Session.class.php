<?php

class Session
{
	static private $inited = false;

	public function __construct()
	{
		if(!self::inited)
			session_start();
		self::inited = true;
	}

	public function read($key = null)
	{
		if(isset($key))
			return $_SESSION[$key];
		return $_SESSION;
	}

	public function write($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function delete($key)
	{
		unset($_SESSION[$key]);
	}

	public function destroy()
	{
		session_destroy();
	}
}