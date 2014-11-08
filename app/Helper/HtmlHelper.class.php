<?php

class HtmlHelper
{
	public function script($name)
	{
		return '<script src="' . __HOST_URL__ . '/public/js/' . $name . '.js' . '" type="text/javascript"></script>';
	}

	public function css($name)
	{
		return '<link rel="stylesheet" href="' . __HOST_URL__ . '/public/css/' . $name . '.css' . '" />';
	}

	public function publicUrl($name)
	{
		return __HOST_URL__ . '/public/img/' . $name;
	}

	public function url($url, $params = null)
	{
		$realUrl = __HOST_URL__ . '/' . $url['controller'] . '/' . $url['action'];
		foreach ($params as $value)
			$realUrl .= ('/' . $value);
		return $realUrl;
	}
}