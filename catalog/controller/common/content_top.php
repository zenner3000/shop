<?php
/*
	首页中间格子的内容，包含了橱窗 和 下面格子列表
*/
class ControllerCommonContentTop extends Controller {


	public function index() {


		$this->load->model('design/layout');

		//获取到路由地址(实际就是要访问的文件里面的方法)
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;




		//根据路由访问，获取不同的布局ID
		//如果点击的是分类
		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		//如果访问的是某个产品
		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');

			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		//如果点击的是页脚下面的information
		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('extension/module');

		$data['modules'] = array();




		//上面布局ID获取完之后，根据布局ID，获取到布局模块
		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');

		/*  调试发现，modules里面是这3个模块,实际上是指示catalog\controller\module 下面的文件
		slideshow.27  , 产品列表上面的个大的可以滑动的图片
		featured.28   , 产品列表
		carousel.29   , 就是产品列表下面的那行 品牌logo，可以转动的
		执行这几个相应文件里面的方法，这些方法返回模板内容
		*/
		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			/* //跳过后，首页就不会显示响应的那一部分
			if($part[0] == 'carousel'){ 
				continue;
			}  */

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = $this->load->controller('module/' . $part[0]);
			}

			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);

				if ($setting_info && $setting_info['status']) {
					$data['modules'][] = $this->load->controller('module/' . $part[0], $setting_info);
				}
			}
		}



		//最后执行 加载模板，把模板内容返回
		// $data 在view()里面会extract(),也就是 会变成一个modules变量 ，是数组.  content_top.tpl里面是以下内容，就是输出上面modules里模块的模板
		/*
		 * <?php foreach ($modules as $module) { ?>
		<?php echo $module; ?>
		<?php } ?>
		 * */
		// 总的来说就是  content_top.tpl 里面输出 slideshow，featured，carousel 这几个模板的内容
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/content_top.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/content_top.tpl', $data);
		} else {
			return $this->load->view('default/template/common/content_top.tpl', $data);
		}
	}

	
}