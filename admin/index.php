<?php
    session_start();      // start a new session

    if(isset($_SESSION['username'])){             // check is there an existing session already or not
            header ('location: dashboard.php');
        }
    $nonavbar = '';
    $pageTitle = 'Login';   // page title name


    include 'init.php';
    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){    // check the request method
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedpass = sha1($password);  // encrypte the password
        
        $stmt = $con->prepare("select userid,username,password,fullname 
                                from users 
                                where username=? AND password=?
                                AND groupid = 1
                                LIMIT 1");
        $stmt->execute(array($username , $hashedpass));  //  the password had been encrypted so u can't use the regular pass
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if($count > 0){             // check if there are results or not
            $_SESSION['username'] = $username;
            $_SESSION['ID'] = $row['userid'];
            $_SESSION['fullname'] = $row['fullname'];
            header('location: dashboard.php');
            exit();
        }
        else{ 
            echo '<div class="login-alert text-center alert alert-danger"> Sorry , Your username or password is wrong</div>';
         }
    }

       

?>
<div class="login">
<form class="log" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<h1 class="text-center">Login</h1>
	<input class="form-control" type="text" name="username" placeholder="User Name" autocomplete="off"></input>
	<input class="form-control" type="password" name="password" placeholder="Password" autocomplete="off"></input>
	<button class="btn btn-primary btn-block" type="submit" value="login">Login</button>
</form>
<a href="signup.php" class="signup">
    <button class="btn btn-info btn-block" type="submit" action="signup.php">Signup</button>
</a>
</div>


<?php
include $temp . 'footer.php';
?>