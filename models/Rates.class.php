<?php 

class Rates extends Model
{


	public function getRates($id, $qt)
	{
		$array = [];

    $sql = $this->pdo->prepare(
     'SELECT *, (select users.name from users where users.id = rates.id_user) as user_name FROM rates WHERE id_product = ? ORDER BY date_rated DESC LIMIT '.$qt);

    $sql->execute([$id]);
    if ($sql->rowCount() > 0){
     $array = $sql->fetchAll();

   }
   return $array;
 }

}
