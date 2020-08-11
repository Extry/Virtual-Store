<h1>Carrinho de Compras</h1><br>

<table border="0" width="100%">
	<tr>
		<th width="100">Imagem</th>
		<th>Nome</th>
		<th width="50">Qtd.</th>
		<th width="120">Preço</th>
		<th width="20"></th>
	</tr>
	<?php
	$subtotal = 0;
	?>
	<?php foreach($list as $item): ?>
		<?php
		$subtotal += (floatval($item['price']) * intval($item['qt']));
		?>
		<tr>
			<td><img src="<?php echo BASE_URL; ?>media/products/<?php echo $item['image']; ?>" width="80" /></td>
			<td><?php echo $item['name']; ?></td>
			<td><?php echo $item['qt']; ?></td>
			<td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
			<td><a href="<?php echo BASE_URL; ?>cart/del/<?php echo $item['id']; ?>"><img src="<?php echo BASE_URL; ?>assets/images/delete.png" width="17"></a></td>
			<td>
				<?php if($item['qt'] -= 1): ?>
					<strong><button><a href="<?php echo BASE_URL; ?>cart/att/<?php echo $item['id']; ?>">-</a></button></strong>
				<?php endif; ?>
				<strong><button><a href="<?php echo BASE_URL; ?>cart/att2/<?php echo $item['id']; ?>">+</a></button></strong>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="3" align="right">Sub-total: </td>
		<td><strong>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></strong></td>
	</tr>
	<tr>
		<td colspan="3" align="right">Frete: </td>
		<td>

            <?php if(isset($shipping['price'])): ?>

            <strong>R$ <?php echo $shipping['price']; ?></strong> (<?php echo $shipping['date']; ?> dia<?php echo ($shipping['date'] == '1')?'':'s';?>);


            <?php else: ?>
			Digite seu cep <br>
			<form method="POST">
				<input type="number" name="cep"><br>
				<input type="submit" value="Calcular">
			</form>
		<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">Total: </td>
		<td><strong>R$ <?php 

          $frete = floatval(str_replace(',', '.', $shipping['price']));
          $total = $subtotal + $frete ;

		echo number_format($total, 2, ',', '.'); ?>
			
		</strong></td>
	</tr>
</table>

<hr>


