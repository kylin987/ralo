<?php

namespace core;

class imooc {
	public $assign;
	static public function run(){
		\core\lib\log::init();
		$route = new \core\lib\route();
		
		$ctrlClass = $route->ctrl;
		$action = $route->action;
		
		$ctrlfile = APP.'/ctrl/'.$ctrlClass.'Ctrl.php';
		$ctrlClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
		if(is_file($ctrlfile)){
			include $ctrlfile;
			$ctrl = new $ctrlClass();
			$ctrl->$action();
		}else{
			throw new \Exception('找不到控制器'.$ctrlClass);
		}
		
	}
	static public function load($class){
		//自动加载类库		
		$class = str_replace('\\','/',$class);
		$file = IMOOC.'/'.$class.'.php';
		if(is_file($file)){
			include $file;
		}		
	}
	public function assign($name,$value){
		$this->assign[$name] = $value;
	}
	public function display($file){
		$file = APP.'/views/'.$file;
		if(is_file($file)){
			$loader = new \Twig_loader_Filesystem(APP.'/views');
			$twig = new \Twig_Environment($loader,array(
				'cache' => IMOOC.'cache',
				'debug' => DEBUG
			));
			$template = $twig->loadTemplate($file);
			$template->display($this->assign ? $this->assign : '');
		}
	}
}