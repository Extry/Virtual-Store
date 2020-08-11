<h1>Você está pesquisando por: "<?php echo $searchTerm; ?>" </h1>
<div class="row">
	<?php 
	$a = 0;

	
	foreach ($list as $product_item): ?>
		<div class="col-sm-4">
			<?php $this->loadView('product_item', $product_item); ?>
		</div>
		
		<?php 
		if($a >= 2){
			$a = 0;
			echo '</div><div class="row">';
		}else   {
			$a++;
		}
	endforeach;
	?>	
</div>
<div class="paginationArea">
	<?php for ($q=1; $q <=$number ; $q++):  ?>
		<div class="paginationitem <?php echo ($currentPage==$q)?'pag_active':''; ?>"><a href="<?php echo BASE_URL; ?>? <?php 
           $pag_array = $_GET;
           $pag_array['p'] = $q;
           echo http_build_query($pag_array);
 
		 ?>"><?php echo $q; ?></a></div>
	<?php endfor; ?>
</div>
