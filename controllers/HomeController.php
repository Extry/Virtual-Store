<?php 

class HomeController extends Controller
{

	public function index()
	{
		$products = new Products();
		$date['list'] = $products->getList();
		$this->loadTemplate('home', $date);
	}
}


