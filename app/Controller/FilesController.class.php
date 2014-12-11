<?php

class FilesController extends AppController
{
	public function getImg($id = null)
	{

		/*$fp = fopen("test.txt", "a");//文件被清空后再写入 
		if($fp) 
		{ 
			$flag=fwrite($fp,"行".$id." : "."Hello World!\r\n"); 
		}
		else
		{ 
			echo "打开文件失败"; 
		}
		fclose($fp); */

		$this->Transfer->setProtocol('http');
		$this->Transfer->setHost('127.0.0.1');
		$this->Transfer->setPort('8080');
		$this->Transfer->setRoot('Discovery');

		if($id === null)
			return;

		$info = $this->Transfer->get(array('id' => $id), 'files', 'getBase64');
		$this->ajaxReturn($info);

	}

	public function forCheck($id = null){
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

	public function upload(){
		if($this->isPost())
		{
			$post = $this->getPost();

			$this->Transfer->setProtocol("http");
			$this->Transfer->setHost("127.0.0.1");
			$this->Transfer->setPort("8080");
			$this->Transfer->setRoot("Discovery");

			$pictFile = array();
			$fileRe = $this->Transfer->post($pictFile,"","");

			if($fileRe['code'] == 0)
			{
				$pictInfo = array();
				$pictRe = $this->Transfer->post($pictInfo,"","");

				$pushInfo = array();
				$pushRe = $this->Transfer->post($pushInfo,"","");
			}
			else
			{

			}
		}
	}
}