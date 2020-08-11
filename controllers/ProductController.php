<?php 

class ProductController extends Controller
{

	public function index()
	{
		header("Location: ".BASE_URL);
		
	}

	public function open($id)
	{
		$store = new Store();
		$products = new Products();
		$categories = new Categories();
		$data = $store->getTemplateData();

		$info = $products->getProductInfo($id);
		if(count($info) >0){

			$data['product_info'] = $info;
			$data['product_images'] = $products->getImagesByProductId($id);
			$data['product_options'] = $products->getOptionsByProductId($id);
			$data['product_rates'] =  $products->getRates($id,5);
			$this->loadTemplate('product', $data);
		} else{
			header("Location: ".BASE_URL);
		}
	}
}
