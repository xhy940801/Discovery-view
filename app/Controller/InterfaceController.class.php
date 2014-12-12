<?php

class InterfaceController extends AppController
{
	public function test(){
		if($this->isPost())
		{
			$revision = 0;
			$offset = 0;
			$count = 10;
			$userId = 1;

			$data = array('userId' => $userId);
			$esseInfo = $this->Transfer->get($data,"user","getEsseInfo");
			$nowRevision = $esseInfo['msg']['revision'];

			$offset += $revision - $nowRevision;

			$data = array('userId' => $userId, 'offset' => $offset, 'count' => $count);
			$list = $this->Transfer->get($data,"picture","pushPictList");

			$this->ajaxReturn($list);
		}
	}

	protected function _beforeAction()
	{
		$this->Transfer->setProtocol("http");
		$this->Transfer->setHost("127.0.0.1");
		$this->Transfer->setPort("8080");
		$this->Transfer->setRoot("Discovery");
	}

	public function login()
	{
		$email = $this->getPost('email');
		$password = $this->getPost('password');
		$data = array('email' => $email,'password' => $password);
		$secuInfo = $this->Transfer->get($data,"user","login");
		$this->ajaxReturn($secuInfo);
	}

	/**
	 * 获取版本号，需传入用户id，其实就是返回user_esse_info的内容而已（格式和java端返回的一样）
	 */
	public function getEsseInfo()
	{
		$userId = $this->getPost('userId');
		$data = array('userId' => $userId);
		$esseInfo = $this->Transfer->get($data,"user","getEsseInfo");
		$this->ajaxReturn($esseInfo);
	}

	/**
	 * 获取图片信息，方式是传入一个包含所需要id的json返回一个json，键是图片id值是java返回的信息
	 */
	public function getPictureInfos()
	{
		$picIds = $this->getPost('pic_ids');
		$data = array('pictureIdList' => $picIds);
		$result = $this->Transfer->get($data,"picture","getPictureInfoList");
		$this->ajaxReturn($result);
	}

	/**
	 * 获取推送列表
	 */
	public function getPicList()
	{
		$revision = $this->getPost('revision');
		$offset = $this->getPost('offset');
		$count = $this->getPost('count');
		$userId = $this->getPost('userId');

		$data = array('userId' => $userId);
		$esseInfo = $this->Transfer->get($data,"user","getEsseInfo");
		$nowRevision = $esseInfo['msg']['revision'];

		$offset += $revision - $nowRevision;

		$data = array('userId' => $userId, 'offset' => $offset, 'count' => $count);
		$list = $this->Transfer->get($data,"picture","pushPictList");

		$this->ajaxReturn($list);
	}
}