<?php 


class Cart extends Model
{

	public function getList()
	{
		$array = [];
		$products = new Products();
		$cart = [];

		if(isset($_SESSION['cart'])){
			$cart = $_SESSION['cart'];
		}

		foreach($cart as $id => $qt) {

			$info = $products->getInfo($id);

			$array[] = [
				'id' => $id,
				'qt' => $qt,
				'price' => $info['price'],
				'name' => $info['name'],
				'image' => $info['image'],
				'weight' => $info['weight'],
				'width' => $info['width'],
				'height' => $info['height'],
				'length' => $info['length'],
				'diameter' => $info['diameter']];

			}
			return $array;
		}

		public function getSubTotal()
		{
			$list = $this->getList();
			$subtotal = 0;


			foreach ($list as $item ) {
				$subtotal += (floatval($item['price']) * intval($item['qt']));
			}

			return $subtotal;
		}

		public function shippingCalculate($cepDestination)
		{
			$array = ['price' => 0, 'date' => ''];


			$list = $this->getList();

			$nVlPeso = 0;
			$nVlComprimento = 0;
			$nVlAltura = 0;
			$nVlLargura = 0;
			$nVlDiametro =  0;
			$nVlValorDeclarado = 0;

			foreach($list as $item){

				$nVlPeso += floatval($item['weight']);
				$nVlComprimento += floatval($item['length']);
				$nVlAltura += floatval($item['height']);
				$nVlLargura += floatval($item['width']);
				$nVlDiametro += floatval($item['diameter']);
				$nVlValorDeclarado += floatval($item['price'] * $item['qt']);

			}

			$soma =  $nVlComprimento + $nVlAltura + $nVlLargura;
			if ($soma > 200){
				$nVlComprimento = 66;
				$nVlAltura = 66;
				$nVlLargura = 66;

			}
			if($nVlDiametro > 90){
				$nVlDiametro = 90;
			}
			if($nVlPeso > 40){
				$nVlPeso = 40;
			}

 
				$data = [
					'nCdServico' => '40010',
					'sCepOrigem' => config['cep_origin'],
					'sCepDestino' => $cepDestination,
					'nVlPeso' => $nVlPeso,
					'nCdFormato' => '1',
					'nVlComprimento' => $nVlComprimento,
					'nVlAltura' => $nVlAltura,
					'nVlLargura' => $nVlLargura,
					'nVlDiametro' => $nVlDiametro,
					'sCdMaoPropria' => 'N',
					'nVlValorDeclarado' => $nVlValorDeclarado,
					'sCdAvisoRecebimento' => 'N',
					'StrRetorno' => 'xml'
				];


				$url = 'http://ws.correios.com.br/calculador/CalcPrecoprazo.aspx';
				$data = http_build_query($data);

				$ch = curl_init($url.'?'.$data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$r = curl_exec($ch);
				$r = simplexml_load_string($r);

				$array['price'] = current($r->cServico->Valor);
				$array['date'] = current($r->cServico->PrazoEntrega);

				return $array;


			}

		}
