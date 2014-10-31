<?php
/*
action="http://127.0.0.1:8080/Discovery/login"
*/
class Transfer
{
	var $url;			//完整的地址
	var $protocol;		//协议
	var $host;			//主机名
	var $port;			//端口号
	var $root;			//根目录名
	var $subsystem;		//子系统名
	var $action;		//具体操作函数名

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
		$this->url = $this->protocol."://".$this->host.":".$this->port."/".$this->root."/".$this->subsystem."/".$this->action;
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
	public function get($subsystem = $this->subsystem,$action = $this->action)
	{
		$this->subsystem = $subsystem;
		$this->action = $action;
		createUrl();

		initCurl();

		$re = json_decode(curl_exec($this->ch));

		closeCurl();

		return $re;
	}

	/**
	 *	post操作
	 */
	public function post($obj,$subsystem = $this->subsystem,$action = $this->action)
	{
		$this->subsystem = $subsystem;
		$this->action = $action;
		createUrl();

		initCurl();

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);

		$re = json_decode(curl_exec($this->ch));

		closeCurl();

		return $re;
	}
}

?>