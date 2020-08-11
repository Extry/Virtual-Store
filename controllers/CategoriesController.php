<?php 

class CategoriesController extends Controller
{

	public function index()
	{
		header("Location: ".BASE_URL);
	}

	public function enter($id)
	{
		$store = new Store();
		$products = new Products();
		$categories = new Categories();
		$f = new Filters();
		$data = $store->getTemplateData();

		$data['category_name'] = $categories->getCategoryName($id);		


		if(!empty($data['category_name'])){

			$categories = new Categories();
			$currentPage = 1;
			$offset = 0;
			$limit = 5;

			if(!empty($_GET['p'])){
				$currentPage = $_GET['p'];
			}
			$offset = ($currentPage * $limit) - $limit;
			$filters = ['category'=> $id];
			$data['category_filter'] = $categories->getCategoryTree($id);
			$data['list'] = $products->getList($offset, $limit,$filters);
			$data['totalitens'] = $products->getTotal($filters);
			$data['number'] = ceil($data['totalitens'] / $limit);
			$data['currentPage'] = $currentPage;
			$data['id_category'] = $id;
			$data['filters'] = $f->getFilters($filters);
			$data['filters_selected'] = $filters;
			$data['searchTerm'] = '';
			$data['category'] = '';
			$data['sidebar'] = true;
			$this->loadTemplate('categories', $data);

		}else{

			header("Location: ".BASE_URL);
		}
	}

}

