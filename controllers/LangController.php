<?php 

class LangController extends Controller
{

	public function index(){}

	public function set($lang)
	{
		$_SESSION['lang'] = $lang;
		header('Location: '.BASE_URL);

	}

}