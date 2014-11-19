<?php

class UsersController extends AppController
{
	public function getEsseInfo($userId){
		$getByUserId = array('userId' => $userId);
		$esseInfo = $this->Transfer->get($getByUserId,"user","getEsseInfo");
		return $esseInfo;
	}

	public function getPushPict($offset,$count){
		$getByUserId = array('userId' => $this->Session->read('User')['id'],'offset' => $offset,'count' => $count );
		$pushList = $this->Transfer->get($getByUserId,"picture","pushPictList");

		$list = array();

		foreach ($pushList['msg'] as $key => $value) {
			$getByPictId = array('pictureId' => $value['pictureId']);
			$pictureInfo = $this->Transfer->get($getByPictId,"picture","getPictureInfo");

			$getByUserId = array('userId' => $pictureInfo['msg']['userId']);
			$esseInfo = $this->Transfer->get($getByUserId,"user","getEsseInfo");	

			$pictureInfo['msg']['userInfo'] = $esseInfo['msg'];
			array_push($list, $pictureInfo['msg']);
		}

		return $list;
	}

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

			$esseInfo = $this->getEsseInfo($this->Session->read('User')['id']);
// print_r($esseInfo);
			$pushList = $this->getPushPict(0,10);
			
			$this->set('pictureInfo',$pushList[1]);
		}
	}

}