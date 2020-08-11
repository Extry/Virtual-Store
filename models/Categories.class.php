<?php 

class Categories extends Model
{

	public function getList()
	{
		$array = [];
		$sql = $this->pdo->query('SELECT * FROM categories ORDER BY sub DESC');
		if($sql->rowCount() > 0){
			foreach ($sql->fetchAll() as $item) {
				$item['subs'] = [];
				$array[$item['id']] = $item;
			}
			while ($this->stillNeed($array)) {
				$this->organizeCategory($array);
			}
		}

		return $array;  	
	}
	public function getCategoryTree($id)
	{
		$array = [];

		$haveAChield = true;

		while ($haveAChield) {

			$sql = $this->pdo->prepare('SELECT * FROM categories WHERE id = ?');
			$sql->execute([$id]);
			if ($sql->rowCount() > 0){
				$data = $sql->fetch();
				$array[] = $data;
				if (!empty($data['sub'])){
					$id = $data['sub'];

				}else{
					$haveAChield = false;
				}
			}


		}
		$array = array_reverse($array);

		return $array;
	}
	public function getCategoryName($id)
	{
		$sql = $this->pdo->prepare('SELECT name FROM categories WHERE id = ?');
		$sql->execute([$id]);
		if($sql->rowCount() > 0){
			$sql = $sql->fetch();
			return $sql['name'];
		}
	}

	private function organizeCategory(&$array)
	{
		foreach ($array as $id => $item) {
			if(isset($array[$item['sub']])){
				$array[$item['sub']]['subs'][$item['id']] = $item;
				unset($array[$id]);
				break;
			}
		}

	} 

	private function stillNeed($array)
	{
		foreach($array as $item){
			if(!empty($item['sub'])){
				return true;
			}
		}
		return false;
	}


}
