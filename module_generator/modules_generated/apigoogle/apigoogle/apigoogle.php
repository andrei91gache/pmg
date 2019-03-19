<?php
class Apigoogle extends Module
{

	public function __construct()
	{
		$this->name = 'apigoogle';
		$this->tab = 'Administration' ;
		$this->version = 'dzazddaz' ;
		$this->author = 'zadaz' ;
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = 'daadz';
		$this->description = 'dazazd';
	}


	public function install()
	{
		return parent::install();
	}


	public function uninstall()
	{
		return parent::uninstall();
	}


	public function getContent()
	{
		$this->context->smarty->assign('module_dir', $this->_path);
		$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/infos.tpl');
		return $output;
	}

}
