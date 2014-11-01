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

	public function url($url, $params)
	{
		$realUrl = __HOST_URL__ . '/' . $url['controller'] . '/' . $url['action'];
		foreach ($parmas as $value)
			$realUrl .= ('/' . $value);
		return $realUrl;
	}
}