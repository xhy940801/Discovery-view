<?php

class HtmlHelper
{
	public function script($name)
	{
		return __HOST_URL__ . 'public/js/' . $name . '.js';
	}

	public function css($name)
	{
		return __HOST_URL__ . 'public/css/' . $name . '.css';
	}

	public function url($url, $params)
	{
		$realUrl = __HOST_URL__ . '/' . $url['controller'] . '/' . $url['action'];
		foreach ($parmas as $value)
			$realUrl .= ('/' . $value);
		return $realUrl;
	}
}