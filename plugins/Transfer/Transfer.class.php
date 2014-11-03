<?php
/*
action="http://127.0.0.1:8080/Discovery/login"
*/
class Transfer
{
	var $url;				//完整的地址
	var $protocol;			//协议
	var $host;				//主机名
	var $port;				//端口号
	var $root = null;		//根目录名
	var $subsystem = null;	//子系统名
	var $action = null;		//具体操作函数名

	var $ch;			//curl连接

	function __construct()
	{
		
	}

	public function setProtocol($newProtocol)
	{
		$this->protocol = $newProtocol;
	}

	public function setHost($newHost)
	{
		$this->host = $newHost;
	}

	public function setPort($newPort)
	{
		$this->port = $newPort;
	}

	public function setRoot($newRoot)
	{
		$this->root = $newRoot;
	}

	public function setSubsystem($newSubsystem)
	{
		$this->subsystem = $newSubsystem;
	}

	public function setAction($newAction)
	{
		$this->action = $newAction;
	}

	/**
	 *	将各部分整合成完整的url
	 */
	private function createUrl()
	{
		$this->url = $this->protocol."://".$this->host.":".$this->port;
		if(!empty($this->root))
		{
			$this->url = $this->url."/".$this->root;
		}
		if(!empty($this->subsystem))
		{
			$this->url = $this->url."/".$this->subsystem;
		}
		if(!empty($this->action))
		{
			$this->url = $this->url."/".$this->action;
		}
	} 

	private function addGet($obj)
	{
		$this->url = $this->url."?";
		foreach ($obj as $key => $value) {
			$this->url = $this->url.urlencode($key)."=".urlencode($value)."&";
		}
		$this->url = substr($this->url, 0,strlen($this->url)-1);
	}

	/**
	 *	初始化curl连接
	 */
	private function initCurl()
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
	}

	/**
	 *	关闭curl连接
	 */
	private function closeCurl()
	{
		curl_close($this->ch);
	}

	/**
	 *	get操作
	 */

	public function get($obj = null,$subsystem = null,$action = null)
	{
		if(!empty($subsystem))
			$this->subsystem = $subsystem;
		if(!empty($action))
			$this->action = $action;
		$this->createUrl();

		if(!empty($obj))
		{
			$this->addGet($obj);
		}

		$this->initCurl();

		$re = json_decode(curl_exec($this->ch),true);

		$this->closeCurl();

		return $re;
	}

	/**
	 *	post操作
	 */
	public function post($obj,$subsystem = null,$action = null)
	{
		if(!empty($subsystem))
			$this->subsystem = $subsystem;
		if(!empty($action))
			$this->action = $action;
		$this->createUrl();

		$this->initCurl();

		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $obj);

		$re = json_decode(curl_exec($this->ch),true);

		$this->closeCurl();

		return $re;
	}
}

