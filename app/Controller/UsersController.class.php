<?php

class UsersController extends AppController
{
	public function push()
	{
		if(!$this->Session->has('User'))
		{
			$this->redirect(array('controller' => 'Pages','action' => 'index'));
			exit();
		}
		else
		{
			$this->Transfer->setProtocol("http");
			$this->Transfer->setHost("127.0.0.1");
			$this->Transfer->setPort("8080");
			$this->Transfer->setRoot("Discovery");

			$get = array('userId' => $this->Session->read('User')['id']);

			$esseInfo = $this->Transfer->get($get,"user","getEsseInfo");

			//print_r($esseInfo);
			//echo "hehehe<br>";

			$pushList = $this->Transfer->get($get,"picture","pushPictList");

			//print_r($pushList);
		}
	}
}