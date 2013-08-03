<?php

class CategoryModule extends KWebModule
{
	public function init()
	{
		parent::init();
		$this->setImport(array(
			'category.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
			return true;
		else
			return false;
	}
}
