<?php 

class Store extends Model
{


	public function getTemplateData()
	{
		$data = [];

		$products = new Products();
		$categories = new Categories();
		$cart = new Cart();
		$data['categories'] = $categories->getList();
		$data['widget_featured1'] = $products->getList(0,5, ['featured' => '1'], true);
		$data['widget_featured2'] = $products->getList(0,3, ['featured' => '1'], true);
		$data['widget_sale'] = $products->getList(0,3, ['sale' => '1'], true);
		$data['widget_toprated'] = $products->getList(0,3, ['toprated' => '1']);

		if(isset($_SESSION['cart'])){
			$qt = 0;
			foreach($_SESSION['cart'] as $qtd){
				$qt += $qtd;
			}
			$data['cart_qt'] = $qt;
		}else{
			$data['cart_qt'] = 0;
		}

		$data['cart_subtotal'] = $cart->getSubTotal();

		return $data;
	} 

}
