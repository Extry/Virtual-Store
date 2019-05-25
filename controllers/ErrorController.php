<?php

class ErrorController extends Controller
{

	public function index()
	{
		$this->loadView('404', []);
	}

}    

