<?php
	ob_start();
	session_start();
	include 'init.php';

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	$stmt = $con->prepare("SELECT 
								items.*,categories.*,users.* 
							FROM
							 	items
							JOIN
							 	categories
							on 
								items.cat_id = categories.catid
		 					JOIN 
		 						users
		 					on 
		 						items.member_id = users.userid
		 					WHERE 
		 						itemid=? 
		 					AND approve = 1");
	$stmt->execute(array($itemid));
	$count = $stmt->rowCount();
	
	$row = $stmt->fetch();
	
	if($count > 0){ ?>
		<h1 class="text-center"><?php echo $row['item_name'] . " Description"?></h1>
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-10">
					<img src="layout/imgs/avatar.png" class="img-responsive img-thumbnail center-block">	
				</div>	
				<div class="item-info col-md-9 col-sm-10">
					<h2><?php echo $row['item_name'] ?></h2>	
					<p><?php echo $row['description'] ?></p>
					<ul class="list-unstyled">	
						<li>
							<i class="fa fa-calendar"></i>
							<span>Added Date : 	</span><?php echo $row['add_date'] ?>
						</li>
						<li>
							<i class="fa fa-money"></i>
							<span>Price : 	  	</span><?php echo $row['price'] ?>
						</li>
						<li>
							<i class="fa fa-tags"></i>
							<span>Category : 	</span>
							<a href='categories.php?pageid=<?php echo $row["catid"]?>'><?php echo $row['name'] ?></a>
						</li>
						<li>
							<i class="fa fa-building"></i>
							<span>Made In : 		</span><?php echo $row['country'] ?>
						</li>
						<li>
							<i class="fa fa-user"></i>
							<span>Added By : 	</span><?php echo $row['username'] ?>
						</li>
						<li>
							<i class="fa fa-tags"></i>
							<span>Tags : 	</span>
							<?php 
								if(!empty($row['tags'])){
									$tags = explode(",", strtolower($row['tags'])); 							
									foreach($tags as $tag){		
										echo "<a href='tags.php?name={$tag}'>"  .$tag . "</a>". " | ";
									}
								} else{
									echo "No tags";
								}
							?>
						</li>	
					</ul>
				</div>
			</div>
			<hr class="item-hr">
		<?php
			if(isset($_SESSION['user'])){?>
		
				<div class="row">
					<div class="add-comment col-md-offset-3">
						<h3>Add Comment</h3>
						<form action="<?php $_SERVER['PHP_SELF'] . '?itemid=' . $itemid ?>" method="POST">
							<textarea name="comment" class="form-control"></textarea>	
							<input type="submit" class="btn btn-primary" value="submit">
						</form>
					</div>	
				<?php 
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					$comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
					$userid = $_SESSION['userid'];


					$stmt = $con->prepare("INSERT INTO 
													comments(comment_text,comment_date,item_id,user_id)
												VALUES(?,now(),?,?) ");
					$stmt->execute(array($comment,$itemid,$userid));
					if($stmt){
						echo "<div class='alert alert-success'>Thank You for Your Comment</div>";
					}
				}	
			echo "</div>";	
		} else{
				echo "<div class='alert alert-warning'><a href='login.php'>Login</a> or<a href='login.php'> Signup</a> to commnet</div>";	
			} ?>	
			<hr class="custom-hr">
			<?php 
				$stmt = $con->prepare("SELECT
											comments.*,users.*
										FROM 
											comments
										JOIN 
											users
										ON 
											comments.user_id = users.userid
										WHERE
											item_id=?
										AND 
											comments.status =1
										ORDER By 
											comments.comment_date DESC");
				$stmt->execute(array($itemid));
				$comments = $stmt->fetchAll();
				if(!empty($comments)){
					echo "<div class='comment-box'>";
					foreach($comments as $comment){							
					echo "<div class='row'>";					
						echo "<div class='col-md-3'>";
							echo "<img src='layout/imgs/avatar.png' class='img-responsive img-circle'>";
							echo "<h4 class='center-block'>" . $comment['username'] . "</h4>";
						echo "</div>";
						echo "<div class='col-md-9'>";
							echo "<p>" . $comment['comment_text'] . "</p>";
						echo "</div>";
					echo "</div>";	
					echo "<hr class='custom-hr'>";
					}
					echo "</div>";
				} else{
					echo "<div class='alert alert-info'>no comment for this item</div>";
				}
			?>		
		</div>		
			

<?php	 } else {
				echo "<div class='alert alert-danger'>Item is NOT found or NOT approved </div>";
			}

	include $temp . 'footer.php';
?>