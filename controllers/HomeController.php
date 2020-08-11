<?php 

class HomeController extends Controller
{

	public function index()
	{
		$store  = new Store();
		$products = new Products();
		$categories = new Categories();
		$f = new Filters();

		$data = $store->getTemplateData();
		

		$filters = [];

		if(!empty($_GET['filter']) && is_array($_GET['filter'])){
			$filters = $_GET['filter'];

		}
		$currentPage = 1;
		$offset = 0;
		$limit = 3;
		if(!empty($_GET['p'])){
			$currentPage = $_GET['p'];
		}
		if(!is_numeric($currentPage)){
			header("Location: ".BASE_URL."?p=1");
		}
		$offset = ($currentPage * $limit) - $limit;
		$data['list'] = $products->getList($offset, $limit,$filters);
		$data['totalitens'] = $products->getTotal($filters);
		$data['number'] = ceil($data['totalitens'] / $limit);
		$data['currentPage'] = $currentPage;
		$data['filters'] = $f->getFilters($filters);
		$data['filters_selected'] = $filters;
		$data['searchTerm'] = '';
		$data['category'] = '';
		$data['sidebar'] = true;
		

		$this->loadTemplate('home', $data);
	}
	
}


