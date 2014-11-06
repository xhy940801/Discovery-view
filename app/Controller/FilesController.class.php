<?php

class FileController extends AppController
{
	public function getImg($id = null)
	{
		$this->Transfer->setProtocol('http');
		$this->Transfer->setHost('127.0.0.1');
		$this->Transfer->setPort('8080');
		$this->Transfer->setRoot('Discovery');

		if($id === null)
			return;

		$imgInfo = $this->Transfer->get(array('id' => $id), null, 'file/getBase64');
		$info = json_decode($imgInfo);
		if(empty($info['code']))
			return;
		header('Content-type: ' . $info['msg']['type'] . ';');
		echo base64_decode($info['msg']['content']);
	}
}