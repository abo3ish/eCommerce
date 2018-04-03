<?php
	session_start();
	if(isset($_SESSION['user'])){
		header('location:index.php');
	}
	$nonavbar = '';
    $pageTitle = 'Login';
	include 'init.php';

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedpass = sha1($pass);

			$stmt = $con->prepare("SELECT 
										username,password,userid
									FROM users 
									WHERE username=? AND password=?");
			$stmt->execute(array($user,$hashedpass));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if($count > 0){
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				$_SESSION['userid'] = $row['userid'];
				

				header("location:index.php");
			}

// SIGNUP FORM
		}

		else{


			$formErrors = array();

			$username 	= $_POST['username'];
			$password	= $_POST['password'];
			$password2	= $_POST['password2'];
			$email 		= $_POST['email'];

		// check username

			if(isset($username)){
				$userFiltered = filter_var($username,FILTER_SANITIZE_STRING);
				if(strlen($userFiltered) <= 3){
					$formErrors[] = "Your Name can't be less than 4 characters";
				}
			}

		// check password	

			if(isset($password) && isset($password2)){
				if(empty($password)){
					$formErrors[] = "Your Passwords can't be empty";
				}

				if(sha1($password) !== sha1($password2)){
					$formErrors[] = "Your Passwords aren't matched";
				}
			}

		// check email

			if(isset($email)){
				$emailfilter = filter_var($email,FILTER_SANITIZE_EMAIL);
				 if(filter_var($emailfilter,FILTER_VALIDATE_EMAIL) != true){
					$formErrors[] = "Your email isn't valid";
				}
			}

			if(empty($formErrors)){

				$check = checkExist('username', 'users', $userFiltered);
				if($check == 1){
					$formErrors[] = 'Sorry this Username Is Already Exist';
				} else{
					$stmt = $con->prepare("INSERT INTO users(username,password,email,regstatus,date) 
														VALUES(?,?,?,0,now())");
					$stmt->execute(array($userFiltered,sha1($password),$emailfilter));

					$msg = "registered";
				}
			}
		}	
	}

?>
	
	<div class="sign-form">
		<div class="container">
			<h1 class="text-center">
				<span class="active" data-class="login">Login</span> | <span data-class="signup">Signup</span>
			</h1>
<!-- start login form -->			
			<form class="login" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
				<div class="group">
					<input 
					type="text" 
					class="form-control" 
					name="username" 
					required="required" 
					placeholder="Username" />
				</div>
				<div class="group">
					<input 
					type="password" 
					class="form-control" 
					name="password" 
					required="required" 
					placeholder="password" />
				</div> 	
				<input type="submit" name="login" class="btn-block btn btn-primary" value="login" />
			</form>
<!-- End login form -->

<!-- start signup form -->
			<form class="signup" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
			<!-- username -->
				<div class="group">
					<input 
					pattern=".{4,}" 
					title="it can't be less than 4 letters" 
					type="text" 
					class="form-control" 
					name="username" 
					required="required"
					placeholder="Username" />
				</div>
			<!-- password 1 -->	
				<div class="group">
					<input 
					pattern=".{5,}" 
					title="it can't be less than 5 letters" 
					type="password" 
					class="form-control" 
					name="password" 
					required="required" 
					placeholder="password" />
				</div>
			<!-- password 2 -->	
				<div class="group">	
					<input 
					pattern=".{5,}" 
					title="it can't be less than 5 letters"
					type="password"
					class="form-control" 
					name="password2" 
					required="required" 
					placeholder="password again" />
				</div>
			<!-- Email -->		
				<div class="group">	
					<input 
					type="email" 
					class="form-control" 
					name="email" 
					required="required" 
					placeholder="Email" />
				</div>	
				<input type="submit" name="signup" class="btn-block btn btn-success" value="Signup" />
			</form>
<!-- End signup form --> 

			<?php 
				if(!empty($formErrors)){	
					foreach($formErrors as $error){
						echo "<span class='msg error'>" . $error ."</span>";
					}	
				}
				if(isset($msg)){
					echo "<span class='msg success'>" . $msg ."</span>";
				}
			?>		
		</div>
	</div>


<?php include $temp . 'footer.php'; ?>