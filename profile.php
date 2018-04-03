<?php
    
	session_start();
	include 'init.php';
	if(isset($_SESSION['user'])){

		$stmt = $con->prepare("SELECT * FROM users WHERE username=?");
		$stmt ->execute(array($sessionUser));
		$userInfo = $stmt->fetch();

	?>

	<div class="container">
		<div class="info block">
			<div class="panel panel-primary">
				<div class="panel-heading">Information
					<a href="#" class="edit-profile pull-right">Edit your profile</a>
				</div>
				<div class="panel-body">
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-unlock"></i>
							<span>username</span> : <?php echo $userInfo['username'] ?>
						</li>
						<li>
							<i class="fa fa-envelope"></i>
							<span>Email </span> : <?php echo $userInfo['email'] ?>
						</li> 
						<li>
							<i class="fa fa-user"></i>
							<span>Full Name</span> : <?php echo $userInfo['fullname'] ?>
						</li>
						<li>
							<i class="fa fa-calendar"></i>
							<span>Register Date</span> : <?php echo $userInfo['date'] ?>
						</li>
					</ul> 
				</div>
			</div>	
		</div>

		<div class="info block">
			<div class="panel panel-primary">
				<div class="panel-heading">Ads<a href="newAd.php" class='pull-right'>Create New Ad</a></div>
				<div class="panel-body">
					<?php 
						$items = getItems('member_id',$userInfo['userid']);
						if(!empty($items)){
							foreach($items as $item){
								echo "<div class='col-sm-6 col-md-3'>";
									echo " <div class='thumbnail item-box'>";
										echo "<img class='img-responsive' src='layout/imgs/avatar.png' alt='...'>";
					        			 echo "<span class='price'>" . $item['price'] . "</span>";
					        			 if($item['approve'] == 0){
								              echo "<span class='approve-status'>Not Approved</span>";
								          }
										echo "<div class='caption'>";
											echo "<h3><a href='items.php?itemid=" . $item['itemid'] . "'>" . $item['item_name'] . "</a></h3>";
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
						} else{
							echo "There is no Ads to show <a href='newAd.php'>Create Ad</a>";
						}

					?>
				</div>
			</div>	
		</div>

		<div class="comment-box block">
			<div class="panel panel-primary">
				<div class="panel-heading">My least Comments</div>
				<div class="panel-body">
					<?php
						/*$stmt = $con->prepare("SELECT comment_text FROM comments WHERE user_id=?");
						$stmt->execute(array($userInfo['userid']));
						$comments = $stmt->fetchAll();*/
						$comments = getAllFrom("comment_text","comments","WHERE user_id={$userInfo['userid']}","","commentid");
						if(!empty($comments)){

							foreach($comments as $comment){
								echo "<p>" . $comment['comment_text'] . "</p>";
							}
						} else{
							echo "There is no Comments to show";
						}
					?>
				</div>
			</div>	
		</div>


	</div>

<?php
	} else{
		header('location:login.php');
	}
    include $temp . 'footer.php';
?>