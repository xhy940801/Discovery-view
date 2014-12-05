<?php

class InterfaceController extends AppController
{
	protected function _beforeAction()
	{
		$this->Transfer->setProtocol("http");
		$this->Transfer->setHost("127.0.0.1");
		$this->Transfer->setPort("8080");
		$this->Transfer->setRoot("Discovery");
	}

	/**
	 * 获取版本号，需传入用户id，其实就是返回user_esse_info的内容而已（格式和java端返回的一样）
	 */
	public function getRevision()
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
		$picIds = json_decode($this->getPost('pic_ids'));
		$result = array();
		foreach ($picIds as $picid)
		{
			$data = array('pictureId' => $picid);
			$result[$picid] = $this->Transfer->get($data,"picture","getPictureInfo");
		}
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