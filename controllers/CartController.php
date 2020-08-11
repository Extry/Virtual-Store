<?php 

class CartController extends Controller
{

	public function index()
	{
		$store  = new Store();
		$products = new Products();
		$cart = new Cart();
		$shipping = [];
		$cep = '';

		if(!empty($_POST['cep'])){
			$cep = intval($_POST['cep']);
			$shipping = $cart->shippingCalculate($cep);
			$_SESSION['shipping'] = $shipping;
		}
		if(!empty($_SESSION['shipping'])){
			$shipping = $_SESSION['shipping'];
		}

		if(!isset($_SESSION['cart']) || (isset($_SESSION['cart']) && count($_SESSION['cart']) == 0)){
			header("Location: ".BASE_URL);
			exit;

		}

		$data = $store->getTemplateData();
		$data['list'] = $cart->getList();
		$data['shipping'] = $shipping;
		

		$this->loadTemplate('cart', $data);
	}


	public function add()
	{
		if(!empty($_POST['id_product'])){
			$id =  intval($_POST['id_product']);
			$qt = intval($_POST['qt_product']);

			if(!isset($_SESSION['cart'])){
				$_SESSION['cart'] = [];
			}
			if(isset($_SESSION['cart'][$id])) {
				$_SESSION['cart'][$id] += $qt;

			}else{
				$_SESSION['cart'][$id] = $qt;

			}

		}
		header("Location: ".BASE_URL."cart");
		exit;

	}

	public function del($id)
	{
		if(!empty($id)){
			unset($_SESSION['cart'][$id]);
		}
		header("Location: ".BASE_URL."cart");
		exit;
	}
	public function att($id)
	{
		if(!empty($id)){
			$_SESSION['cart'][$id]--;

		}
		if($_SESSION['cart'][$id] == 0){
			unset($_SESSION['cart'][$id]);

		}
		header("Location: ".BASE_URL."cart");
		exit;
	}
	public function att2($id)
	{
		if(!empty($id)){
			$_SESSION['cart'][$id]++;

		}
		
		header("Location: ".BASE_URL."cart");
		exit;
	}
	
}


