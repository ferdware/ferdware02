<?php
namespace Vvveb\Plugins\TestPlugin\Controller;

use Vvveb\Controller\Base;

class Index extends Base {
	private $sharedData;
	function index() {
		// this will try to render /plugins/test-plugin/public/index.html
		// using the /plugins/test-plugin/app/template/index.tpl template
		$this->view->title = 'Test title';
		$this->view->content = 'Test content';
	}

	function json() {
		$this->response->setType('json');
		return ['key' => 'value 123'];
		// or
		//$this->response->output(['key' => 'value 123']);
	}
}