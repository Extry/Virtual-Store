<?php 

class Controller
{
	protected $lang;

	public function __construct()
	{
		$this->lang = new Language();
	}

	public function loadView($view, $data = [])
	{
		extract($data);
		require 'views/'.$view.'.php';

	}
	public function loadTemplate($view, $data = [])
	{
		require 'views/template.php';

	}

}

