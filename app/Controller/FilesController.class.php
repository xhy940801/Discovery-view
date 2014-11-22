<?php

class FilesController extends AppController
{
	public function getImg($id = null)
	{
		$this->layout = 'blank';

		$this->Transfer->setProtocol('http');
		$this->Transfer->setHost('127.0.0.1');
		$this->Transfer->setPort('8080');
		$this->Transfer->setRoot('Discovery');

		if($id === null)
			return;

		$info = $this->Transfer->get(array('id' => $id), 'files', 'getBase64');
		if($info['code'])
			return;
		header('Content-type: ' . $info['msg']['type'] . ';');
		$this->set('img', base64_decode($info['msg']['content']));
	}
}