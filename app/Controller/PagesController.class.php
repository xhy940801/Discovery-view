<?php

class PagesController extends AppController
{

	public function index()
	{

		if($this->Cookie->has("autoLogin"))
		{
			$this->Session->write('User',json_decode($this->Cookie->read("autoLogin")));
			$this->redirect("Pages/loginTest");
			exit();
		}

		if($this->isPost())
		{
			$post = $this->getPost();
			$this->Transfer->setProtocol("http");
			$this->Transfer->setHost("127.0.0.1");
			$this->Transfer->setPort("8080");
			$this->Transfer->setRoot("Discovery");

			$isAuto = false;
			if($post['autoLogin'] = 'on')
			{
				$isAuto = true;
			}

			unset($post['autoLogin']);

			$userInfo = $this->Transfer->get($post,"user","login");

			if($userInfo['msg'] == null)
			{
				$this->set("error",true);
			}
			else
			{

				if($isAuto)
				{
					$this->Cookie->write('autoLogin',json_encode($userInfo['msg']),array('expire' => (time()+10)));
				}
				$this->Session->write('User',$userInfo['msg']);
				$this->redirect(array('controller' => 'Users','action' => 'push'));
				exit();
			}
		}
	}

	public function loginTest()
	{
		if(!$this->Session->has('User'))
		{
			$this->redirect("Pages/index");
			exit();
		}
		else
		{
			print_r($this->Session->read('User'));
		}
	}
}