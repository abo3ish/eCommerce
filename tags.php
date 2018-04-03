<?php
    session_start();  
    $pageTitle = 'Tags';
    include 'init.php';

    if(isset($_GET['name'])){
     $tags = $_GET['name'];
?>

    	<h1 class="text-center">
          <?php  echo ucfirst($tags) ?> 
    	</h1>
    	<div class="container">
    		<div class="row">

  	<?php 
    $tagitems = getAllFrom("*","items","WHERE tags LIKE '%$tags%'","AND approve = 1","cat_id");
  	foreach($tagitems as $item){
  		
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

   include $temp . 'footer.php';
?>