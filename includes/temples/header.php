<!DOCTYPE html>
<html lang="eng">
	<head>
			<meta charset="utf-8">
			<title><?php getTitle() ?></title>

			<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
			<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
			<link rel="stylesheet" href="<?php echo $css; ?>front.css" />

	</head>
	<body>
		<div class="login-bar">
			<div class="container">
				<?php if(isset($_SESSION['user'])){?>
						<img src="layout/imgs/avatar.png" class="img-circle" />	
						<div class="btn-group">
							<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<?php echo $_SESSION['user'] ?>
								<span class="caret"></span>	
							</span>	
							<ul class="dropdown-menu">
								<li><a href="profile.php">Profile</a></li>
								<li><a href="newAd.php">New Items</a></li>
								<li><a href="profile.php">Profile</a></li>
								<li><a href="logout.php">Logout</a></li>	
							</ul>	
						</div>

				<?php } else{
				 ?>

				<a href="login.php">
					<span class="pull-right">Login/Signup</span>	
				</a>
				<?php } ?>
			</div>	
		</div>
		<nav class="navbar navbar-inverse">
	  		<div class="container">
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="index.php">HomePage</a>
		    </div>

		    <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		          		<?php 
		          			foreach(getAllFrom('*','categories','WHERE parent = 0','','catid') as $cat){
		          				echo "<li><a href='categories.php?pageid=" . $cat['catid'] . "'>" . $cat['name'] . "</a></li>";
		          			} 
		          		?>
		      </ul>
		    </div>
		</div>
	</nav>