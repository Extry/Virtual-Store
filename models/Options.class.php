<?php 

class Options extends Model
{

	public function getName($id)
	{
		$sql = $this->pdo->prepare('SELECT name FROM options WHERE id = ?');
		$sql->execute([$id]);
		if($sql->rowCount() > 0){
			$sql = $sql->fetch();
			return $sql['name'];
		}

	}

}
