<?php 

class BuscaController extends Controller
{

	public function index()
	{
		$store = new Store();
		$data = [];
		$products = new Products();
		$categories = new Categories();
		$f = new Filters();


		$data = $store->getTemplateData();


		if (!empty($_GET['s'])) {
			$searchTerm = $_GET['s'];
			$category = $_GET['category'];

			$filters = [];

			if(!empty($_GET['filter']) && is_array($_GET['filter'])){
				$filters = $_GET['filter'];

			}
			
			$filters['searchTerm'] = $searchTerm;
			$filters['category'] = $category;

			$currentPage = 1;
			$offset = 0;
			$limit = 3;
			if(!empty($_GET['p'])){
				$currentPage = $_GET['p'];
			}
			
			$offset = ($currentPage * $limit) - $limit;
			$data['list'] = $products->getList($offset, $limit,$filters);
			$data['totalitens'] = $products->getTotal($filters);
			$data['number'] = ceil($data['totalitens'] / $limit);
			$data['currentPage'] = $currentPage;
			$data['categories'] = $categories->getList();
			$data['filters'] = $f->getFilters($filters);
			$data['filters_selected'] = $filters;
			$data['searchTerm'] = $searchTerm;
			$data['category'] = $category;
			$data['sidebar'] = true;
			
			$this->loadTemplate('busca', $data);

		}else{
			header("Location:".BASE_URL);
		}
	}
}
