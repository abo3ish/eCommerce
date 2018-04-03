<?php
    session_start();  
    $pageTitle = 'Categories';
    include 'init.php';

    if(isset($_GET['pageid'])){
      $check = checkExist("catid", "categories", $_GET['pageid']);
      if($check > 0){
?>

    	<h1 class="text-center">
          <?php 
            $stmt = $con->prepare("SELECT name FROM categories WHERE catid=?");
            $stmt->execute(array($_GET['pageid']));
            $row = $stmt->fetch();

            echo $row['name'];

          
          ?>   
    	</h1>
    	<div class="container">
    		<div class="row">

  	<?php 
    $items = getAllFrom("*","items","WHERE cat_id = {$_GET['pageid']}","AND approve = 1","cat_id");
  	foreach($items as $item){
  		
  			echo "<div class='col-sm-6 col-md-3'>";
				echo " <div class='thumbnail item-box'>";
					echo "<img class='img-responsive' src='layout/imgs/avatar.png' alt='...'>";
          echo "<span class='price'>" . $item['price'] . "</span>";
					echo "<div class='caption'>";
						echo "<h3>
                    <a href='items.php?itemid=" . $item['itemid'] . "'>" . $item['item_name'] . "</a>
                  </h3>";
            if(!empty($item['description'])){
						  echo "<P>" .$item['description'] . "</P>";
            } else{
               echo "<P> No description To show </P>";
            }
            echo "<span class='date'>". $item['add_date']."</span>";
					echo "</div>";
				echo " </div>";
  			echo "</div>";
  	}


  	?>
  		</div>
  	</div>

  <?php
  }else{
  echo "<div class='alert alert-danger'>there is no such id</div>";
  }
}
   include $temp . 'footer.php';
?>