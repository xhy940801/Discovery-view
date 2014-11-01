<?php
include "E:\wamp\www\Discovery-view\plugins\Transfer\Transfer.class.php";
class PagesController extends AppController
{

	public function index()
	{
		if($this->isPost())
		{
			$post = $this->getPost();
			$transfer = new Transfer();
			$transfer->setProtocol("http");
			$transfer->setHost("127.0.0.1");
			$transfer->setPort("8080");
			$transfer->setRoot("Discovery");
			$userInfo = $transfer->post($post,null,"login");
			if($userInfo['msg'] == null)
			{
				$this->set("error",true);
			}
			else
			{
				$this->redirect("Pages/loginTest");
			}
		}
	}

	public function loginTest()
	{
	}
}