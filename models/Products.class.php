<?php 

class Products extends Model
{

	public function getList($offset = 0, $limit = 3)
	{
		$sql = $this->pdo->query("SELECT *,
			( select brands.name from brands where brands.id = products.id_brand ) as brand_name,
			( select categories.name from categories where categories.id = products.id_category ) as category_name
			FROM products LIMIT $offset, $limit");
		if($sql->rowCount() > 0){
			$array = $sql->fetchAll();
			foreach ($array as $key => $item) {

				$array[$key]['images'] = $this->getImagesByProductId($item['id']);
			}


		}
		return $array;
	}
	public function getTotal()
	{
		$sql = $this->pdo->query('SELECT COUNT(*) as c FROM products');
		$sql = $sql->fetch();
		return $sql['c'];
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
