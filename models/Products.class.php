<?php 

class Products extends Model
{

	public function getList()
	{
		$sql = $this->pdo->query('SELECT *,
			( select brands.name from brands where brands.id = products.id_brand ) as brand_name,
			( select categories.name from categories where categories.id = products.id_category ) as category_name
			FROM products');
		if($sql->rowCount() > 0){
			$array = $sql->fetchAll();
			foreach ($array as $key => $item) {

				$array[$key]['images'] = $this->getImagesByProductId($item['id']);
			}


		}
		return $array;
	}


	public function getImagesByProductId($id)
	{

		$sql = $this->pdo->prepare('SELECT url FROM products_images WHERE id_product = ?');
		$sql->execute([$id]);
		if ($sql->rowCount() > 0){
			$array = $sql->fetchAll();
		}
		return $array;
	}

}

