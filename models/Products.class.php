<?php 

class Products extends Model
{

	public function getInfo($id)
	{

		$array = [];

		$sql = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
		$sql->execute([$id]);


		if($sql->rowCount() >0){
			$array = $sql->fetch();
			$images = current($this->getImagesByProductId($id));
			$array['image'] = $images['url'];
		}

		return $array;

	}

	public function getAvaibleOptions($filters = [])
	{
		$groups = array();
		$ids = array();

		$where = $this->buildWhere($filters);

		$sql = "SELECT
		id, options
		FROM products
		WHERE ".implode(' AND ', $where);
		$sql = $this->pdo->prepare($sql);

		$this->bindWhere($filters, $sql);
		$sql->execute();

		if($sql->rowCount() > 0) {
			foreach($sql->fetchAll() as $product) {
				$ops = explode(",", $product['options']);
				$ids[] = $product['id'];
				foreach($ops as $op) {
					if(!in_array($op, $groups)) {
						$groups[] = $op;
					}
				}
			}
		}

		$options = $this->getAvailableValuesFromOptions($groups, $ids);
		
		return $options;
	}
	public function getAvailableValuesFromOptions($groups, $ids)
	{
		$array = [];
		$options = new Options();
		foreach ($groups as $op) {

			$array[$op] = [
				'name' => $options->getName($op),
				'options' => []
			];
		}
		$sql = "SELECT
		p_value,
		id_option,
		COUNT(id_option) as c
		FROM products_options
		WHERE
		id_option IN ('".implode("','", $groups)."') AND
		id_product IN ('".implode("','", $ids)."')
		GROUP BY p_value ORDER BY id_option";


		$sql  = $this->pdo->query($sql);
		if($sql->rowCount() >0){
			foreach ($sql->fetchAll() as $ops) {
				$array[$ops['id_option']]['options'][] = ['id' => $ops['id_option'], 'value' => $ops['p_value'], 'count' => $ops['c']];

			}

		}
		return $array;
	}

	public function getSaleCount($filters = [])
	{
		$where = $this->buildWhere($filters);
		$where[] = 'sale = "1"';
		$sql = "SELECT COUNT(*) as c FROM products WHERE ".implode(' AND ', $where);
		$sql = $this->pdo->prepare($sql);
		$this->bindWhere($filters, $sql);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch();
			return $array['c'];
		}else{
			return '0';
		}
	}

	public function getMaxPrice($filters = [])
	{

		
		$sql = "SELECT price FROM products ORDER BY price DESC LIMIT 1";
		$sql = $this->pdo->prepare($sql);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch();

			return $array['price'];
		}else{
			return '0';
		}
	}

	public function getListOfStars($filters = [])
	{
		$array = [];
		$where = $this->buildWhere($filters);
		$sql = "SELECT rating, COUNT(id) as c FROM products WHERE ".implode(' AND ', $where)." GROUP BY rating";
		$sql = $this->pdo->prepare($sql);
		$this->bindWhere($filters, $sql);
		$sql->execute();
		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}

	public function getListOfBrands($filters = []) {
		$array = [];
		$where = $this->buildWhere($filters);
		$sql = "SELECT id_brand, COUNT(id) as c FROM products WHERE ".implode(' AND ', $where)." GROUP BY id_brand";
		$sql = $this->pdo->prepare($sql);
		$this->bindWhere($filters, $sql);
		$sql->execute();
		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}

	public function getList($offset = 0, $limit = 3, $filters = [], $random = false)
	{
		$array = [];
		$orderBySql = '';
		if($random == true){
			$orderBySql = "ORDER BY RAND()";
		}
		if(!empty($filters['toprated'])){
			$orderBySql = "ORDER BY rating DESC";

		}
		$where = $this->buildWhere($filters);
		$sql = $this->pdo->prepare("SELECT *,
			( select brands.name from brands where brands.id = products.id_brand ) as brand_name,
			( select categories.name from categories where categories.id = products.id_category ) as category_name
			FROM products 
			WHERE ".implode(' AND ', $where)."
			".$orderBySql."
			LIMIT $offset, $limit");
		$this->bindWhere($filters, $sql);
		$sql->execute();

		if($sql->rowCount() > 0){
			$array = $sql->fetchAll();
			foreach ($array as $key => $item) {
				$array[$key]['images'] = $this->getImagesByProductId($item['id']);
			}


		}
		return $array;
	}
	public function getTotal($filters = [])
	{

		$where = $this->buildWhere($filters);
		$sql = "SELECT COUNT(*) as c FROM products WHERE ".implode(' AND ', $where);
		$sql = $this->pdo->prepare($sql);
		$this->bindWhere($filters, $sql);
		$sql->execute();
		$sql = $sql->fetch();
		return $sql['c'];
	}

	public function getImagesByProductId($id)
	{
		$array = [];

		$sql = $this->pdo->prepare('SELECT url FROM products_images WHERE id_product = ?');
		$sql->execute([$id]);
		if ($sql->rowCount() > 0){
			$array = $sql->fetchAll();
		}
		return $array;
	}
	private function buildWhere($filters)
	{

		$where = ['1=1'];
		if(!empty($filters['category'])){
			$where[] = 'id_category = :id_category';
		}
		if(!empty($filters['brand'])){
			$where[] = "id_brand IN ('".implode("','", $filters['brand'])."')"; 

		}
		if(!empty($filters['star'])){
			$where[] = "rating IN ('".implode("','", $filters['star'])."')"; 

		}
		if(!empty($filters['sale'])){
			$where[] = "sale = '1'";

		}
		if(!empty($filters['featured'])){
			$where[] = "featured = '1'";

		}
		
		if(!empty($filters['options'])){
			$where[] = "id IN (select id_product from products_options where products_options.p_value IN ('".implode("','", $filters['options'])."'))";

		}
		if(!empty($filters['slider0'])){
			$where[] = "price >= :slider0";
		}
		if(!empty($filters['slider1'])){
			$where[] = "price <= :slider1";
		}
		if (!empty($filters['searchTerm'])) {
			$where[] = "name LIKE :searchTerm";
		}

		return $where;

	}
	private function bindWhere($filters, &$sql)
	{
		if(!empty($filters['category'])){
			$sql->bindValue(":id_category", $filters['category']);
		}
		if(!empty($filters['slider0'])){
			$sql->bindValue(":slider0", $filters['slider0']);
		}
		if(!empty($filters['slider1'])){
			$sql->bindValue(":slider1", $filters['slider1']);
		}
		if (!empty($filters['searchTerm'])) {
			$sql->bindValue(":searchTerm",'%'.$filters['searchTerm'].'%');
		}
	}
	public function getProductInfo($id)
	{
		$array = [];
		if(!empty($id)){
			$sql = $this->pdo->prepare('SELECT 
				*,
				( select brands.name from brands where brands.id = products.id_brand ) as brand_name
				FROM products WHERE id = ?');
			$sql->execute([$id]);
			if ($sql->rowCount() > 0){
				$array = $sql->fetch();
			}
		}


		return $array;

	}
	public function getOptionsByProductId($id)
	{
		$options = [];

		$sql = $this->pdo->prepare('SELECT options FROM products WHERE id = ?');
		$sql->execute([$id]);


		if($sql->rowCount() > 0){
			$options = $sql->fetch();
			$options = $options['options'];

			if (!empty($options)){
				$sql = $this->pdo->query("SELECT * FROM options WHERE id IN  (".$options.")");
				$options = $sql->fetchAll();

			}
			$sql = $this->pdo->prepare('SELECT * FROM products_options WHERE id_product = ?');
			$sql->execute([$id]);
			$options_values = [];
			if($sql->rowCount() > 0){
				foreach ($sql->fetchAll() as $op) {

					$options_values[$op['id_option']] = $op['p_value'];
				}


			}

			foreach ($options as $ok => $op) {
				if(isset($options_values[$op['id']])){

					$options[$ok]['value'] = $options_values[$op['id']];
				} else {
					$options[$ok]['value'] = '';
				}
			}
		}


		return $options;
	}

	public function getRates($id, $qt)
	{
		$array = [];
		$rates = new Rates();
		$array = $rates->getRates($id,$qt);

		return $array;
	}


}

