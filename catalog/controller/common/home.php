<?php
class ControllerCommonHome extends Controller {


	//获取调用堆栈
	public function print_stack_trace()
	{
	    $array =debug_backtrace();
	  //print_r($array);//信息很齐全
	   unset($array[0]);
	   foreach($array as $row)
	    {
	       $html .=$row['file'].':'.$row['line'].'行,调用方法:'.$row['function']."<p>";
	    }
	    return $html;
	}

	public function index() {
	    
	//	print_stack_trace();
		//这里$this是Registry 类 ，是抽象类Controller的一个成员，这里继承类居然可以直接$this
		$this->document->setTitle($this->config->get('config_meta_title'));
	//	print_r($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		if(is_a($this->load,'Load')){
			echo '#this->load is Class Load';
		}

	//	print_r($this->load);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
	//	print_r($data['content_top']);
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
	//	print_r($data['content_top']);
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}

}