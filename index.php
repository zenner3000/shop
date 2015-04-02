<?php


//访问控制统一用 index.php 来协调
/*
Route 就是起到一个中转器的作用，它会根据你的Route目录去找到它要执行的方法，比如：
　　index.php?route=account/login
　　根据这个route，op的框架会找到Controller下的Account里的Login.php, 注意login.php的类名一定是这种格式的ControllerAccoutLogin{...}
　　否则Op就识别不了目录，route=account/login 会执行类的默认方法：index
　　如果要指明执行那个方法，则在login后面再加上
　　route=account/login/你定义的方法名
并且在login.php 里定义你要执行的方法。

（如在common目录下的home.php 定义个函数
public function aa(){
echo "袁盛武";
}访问地址如下：
http://127.0.0.1/opencart/index.php?route=common/home/aa就会只输出袁盛武）
 
　　同理，Op里 load 语言包和model等都是以这种机制为基础的。请结合程序，应该是比较好理解的。
至于View层，Op都是把要显示的数据加载到 this->data里去的，这样就可以用 $变量名 在页面上显示变量了。
1.2. Op系统配置文件 （这段是引用别人的。。。找不到出处了 - - 作者看到请联系我）
　　在Op的目录下，可以发现一个 Config.php文件，这里是配置一些OP要使用到的配置路径，并且是每个单独项目里都有一个单独的配置（这点要注意），比如admin和catalog下就分别有这个文件。
　　OpenCart是使用面向对象编程的，同时又使用了MVC的设计思想，因此在解读其源代码时是看不到过程式的代码的。同时它把MVC框架部分单独放在library目录中，这个部分的内容一般是不作修改的。就象你显式地使用其它框架编程一样。
　　Engine下有一个装配器文件：loader.php，这个文件中只有一个类：loader,实际上是一个调度程序，框架中的其它组件，如controller,module，session,cache,language统统由它装载调度。
　　为了让系统运行起来， 象所有的PHP程序一样，OpenCart需要把系统中的一些重要的参数从config.php中，对于这个文件，按惯例，使用一句：
　　require('config.php');
　　连接数据库的DSN参数和相关目录设置就可用了,下面有必要将config.php文件列表在下，这有利于我们理解OpenCart的设计思想：
　　// HTTP
　　define('HTTP_SERVER', 'http://localhost/cnopencart/');
　　define('HTTP_IMAGE', 'http://localhost/cnopencart/image/');
　　// HTTPS
　　define('HTTPS_SERVER', '');
　　define('HTTPS_IMAGE', '');
　　// DIR
　　define('DIR_CACHE', 'C:\wamp\www\cnopencart/cache/');
　　define('DIR_DOWNLOAD', 'C:\wamp\www\cnopencart/download/');
　　define('DIR_IMAGE', 'C:\wamp\www\cnopencart/image/');
　　define('DIR_LIBRARY', 'C:\wamp\www\cnopencart/library/');
　　define('DIR_MODEL', 'C:\wamp\www\cnopencart\catalog/model/');
　　define('DIR_CONTROLLER', 'C:\wamp\www\cnopencart\catalog/controller/');
　　define('DIR_LANGUAGE', 'C:\wamp\www\cnopencart\catalog/language/');
　　define('DIR_EXTENSION', 'C:\wamp\www\cnopencart\catalog/extension/');
　　define('DIR_TEMPLATE', 'C:\wamp\www\cnopencart\catalog/template/');
　　// DB
　　define('DB_HOST', 'localhost');
　　define('DB_USER', 'root');
　　define('DB_PASSWORD', '111111');
　　define('DB_NAME', 'opencart');
　　?>
　　与别的系统可能有些不同是的，OpenCart有一个config类用来从别的设置文件或数据库里存取数据，这些数据也是在程序一开始运行就需要的，因此第一个由loader装载的就是config类，装载config类就只简单地用了一句：
　　// Config
　　$config =$this->load->....
　　其它所有的对象这是以这种方法来进行管理。
　　2. Op的加载器
　　2.1. 系统加载
　　在System下有一些公共类，所以的基础类和公共类都是通过index.php 去加载的，这样你就可以去加载你需要的类和文件了。比如：
　　$loader = new Loader();
　　Registry::set('load', $loader);
　　然后你就可以在系统里调用Load方法去加载需要的Model和language 文件了。调用方式如下：
　　$this->load->....
Op里就是通过这个方法来注册他所需要的资源的。
 
=================================================
 OpenCart: 架构概览

OpenCart是一个代码设计精致小巧的电子商务系统。
1、MVC架构：
OpenCart是基于MVC范式的。
model层负责获取数据。和其他一些框架如CakePHP相比，model的功能实现有限但简洁，直接调用DB类实现数据CRUD操作。
（CakePHP的模型层支持基础数据验证，复杂业务逻辑由Controller层处理）。
controller层负责处理请求，从model获取数据，提交给view层模板。
view负责组织展示。

2、“Registry”设计模式
在OP中，Registry是整个系统的信息中枢。
Registry是一个单例（Singleton），在index.php起始页面中，
首先作为构造函数参数传递给所要用到的类创建类实例，并随之将这个类实例设置到这个“注册表”中，
这个注册表就像是一个共享的数据总线一样，把各个模块/数据串联在一起。

// Registry
$registry = new Registry();
// Front Controller 
$controller = new Front($registry);
3、整体流程
（1）创建Registry对象
（2）注册所有公共类
（3）创建Front类对象，作为请求分发器（Dispatcher）
（4）根据用户请求（url）创建控制器对象及其动作。
            在Front类私有函数execute($action)中如下语句
            $controller = new $class($this->registry); //创建控制器
（5）控制器加载相应的模型，如
        $this->load->model('design/layout');(注意前后的模型，/ 线前面是模型下的文件目录名后面是目录下的文件名，也是模型对象)
        该语句将创建相应的model对象。(相当NEW对像，加载进模型后就可以使用了,一般处理复杂程序或需要重用时就会建模型，每个模型是一个类)
如：
$this->load->model('user/user');//加载后模型类名$this->文件目录->文件名(文件目录是指model下的目录名)
$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])
 
（6）控制器获取模板，绘制（提取数据并启用output buffer）到页面输出区output中
                $this->render();
 
（7）最后Response对象把输出区的数据（页面）echo返回给用户
 
如：if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
} else {
$this->template = 'default/template/product/product.tpl';
}
$this->children = array(
'common/column_left',
'common/column_right',
'common/content_top',
'common/content_bottom',
'common/footer',
'common/header'
);
$this->response->setOutput($this->render());

4、魔术函数（Magic method）
在Controller中调用$this->load->...时，熟悉面向对象语言的开发人员会觉得有点奇怪，因为Controller基类中并没有$load成员变量。
实际上这是由PHP5魔术函数来实现的。__get(), __set()函数在获取/设置非类定义成员变量的时候，会由PHP自动调用。
那么OP中在执行$this->load->...时实际调用的是：
    public function __get($key) {
        return $this->registry->get($key);
    }
现在体会到Registry‘共享总线’的作用了吧。
 
 
MVC本来是存在于Desktop程序中的，M是指数据模型，V是指用户界面，C则是控制器。使用MVC的目的是将M和V的实现代码分离
 
数据模型包括数据库数据的结构部分、数据库数据的操作部分和数据库数据的约束条件。

*/


// Version
define('VERSION', '2.0.2.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

	if ($store_query->num_rows) {
	$config->set('config_store_id', $store_query->row['store_id']);
} else {
	$config->set('config_store_id', 0);
}

// Settings
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], unserialize($result['value']));
	}
}

if (!$store_query->num_rows) {
	$config->set('config_url', HTTP_SERVER);
	$config->set('config_ssl', HTTPS_SERVER);
}

// Url
$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;

	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
	$code = $request->cookie['language'];
} else {
	$detect = '';
	if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
		$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
		foreach ($browser_languages as $browser_language) {
			foreach ($languages as $key => $value) {
				if ($value['status']) {
					$locale = explode(',', $value['locale']);
					if (in_array($browser_language, $locale)) {
						$detect = $key;
						break 2;
					}
				}
			}
		}
	}
	$code = $detect ? $detect : $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['directory']);
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Customer
$customer = new Customer($registry);
$registry->set('customer', $customer);

// Customer Group
if ($customer->isLogged()) {
	$config->set('config_customer_group_id', $customer->getGroupId());
} elseif (isset($session->data['customer']) && isset($session->data['customer']['customer_group_id'])) {
	// For API calls
	$config->set('config_customer_group_id', $session->data['customer']['customer_group_id']);
} elseif (isset($session->data['guest']) && isset($session->data['guest']['customer_group_id'])) {
	$config->set('config_customer_group_id', $session->data['guest']['customer_group_id']);
}

// Tracking Code
if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');

	$db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $db->escape($request->get['tracking']) . "'");
}

// Affiliate
$registry->set('affiliate', new Affiliate($registry));

// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));

// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

//OpenBay Pro
$registry->set('openbay', new Openbay($registry));

// Event
$event = new Event($registry);
$registry->set('event', $event);

$query = $db->query("SELECT * FROM " . DB_PREFIX . "event");

foreach ($query->rows as $result) {
	$event->register($result['trigger'], $result['action']);
}

// Front Controller
$controller = new Front($registry);

// Maintenance Mode
$controller->addPreAction(new Action('common/maintenance'));

// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
