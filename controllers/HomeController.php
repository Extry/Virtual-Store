<?php 

class HomeController extends Controller
{

	public function index()
	{
		$products = new Products();
		$categories = new Categories();
		$currentPage = 1;
		$offset = 0;
		$limit = 5;

		if(!empty($_GET['p'])){
			$currentPage = $_GET['p'];
		}
		if(!is_numeric($currentPage)){
			header("Location: ".BASE_URL."?p=1");
		}
		$offset = ($currentPage * $limit) - $limit;
		$date['list'] = $products->getList($offset, $limit);
		$date['totalitens'] = $products->getTotal();
		$date['number'] = ceil($date['totalitens'] / $limit);
		$date['currentPage'] = $currentPage;
		$date['categories'] = $categories->getList();

		$this->loadTemplate('home', $date);
	}
	 
}
