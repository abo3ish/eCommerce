<?php
    
	session_start();
	if(isset($_SESSION['user'])){
   		include 'init.php'; ?>

	   	<div class="container">
	    		<div class="row">

	  	<?php 
	  	$allItems = getAllFrom('*','items',null,'','itemid');

	  	foreach($allItems as $item){
	  		
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

	<?php } else{
		header("location:login.php");
	}


    include $temp . 'footer.php';
?>