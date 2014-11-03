<?php

class Cookie
{
	public function has($key)
	{
		return isset($_COOKIE[$key]);
	}

	public function read($key = null)
	{
		if(isset($key))
			return $_COOKIE[$key];
		return $_COOKIE;
	}

	public function write($key, $value, $options = array())
	{
		$defaultOpt = array(
			'expire' => null,
			'path' => null,
			'domain' => null,
			'secure' => null,
			'httponly' => null
			);
		$options = array_merge($defaultOpt, $options);
		setcookie($key, $value, $options['expire'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
	}

	public function delete($key)
	{
		setcookie($key, '', time() - 3600);
	}

	public function destroy()
	{
		foreach ($_COOKIE as $key => $value)
			$this->delete($key);
	}

}