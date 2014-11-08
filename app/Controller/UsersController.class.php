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

			$getByUserId = array('userId' => $this->Session->read('User')['id'],'offset' => 0,'count' => 10 );

			$esseInfo = $this->Transfer->get($getByUserId,"user","getEsseInfo");
 // print_r($esseInfo);
			$pushList = $this->Transfer->get($getByUserId,"picture","pushPictList");
 // print_r($pushList);

			$getByPictId = array('pictureId' => $pushList['msg'][1]['pictureId']);
			$pictureInfo = $this->Transfer->get($getByPictId,"picture","getPictureInfo");
			$this->set('pictureId',$pictureInfo['msg']['fileId']);
		}
	}
}