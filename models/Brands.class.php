<?php 

class Brands extends Model
{
	
	public function getNameById($id)
	{
		$sql = $this->pdo->prepare('SELECT name FROM WHERE id = ?');
		$sql->execute([$id]);
		if($sql->rowCount() > 0){
			$data = $sql->fetch();

			return $data['name'];
		}else{
			return '';
		}
	}


}
