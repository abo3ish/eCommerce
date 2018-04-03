 <?php 
    $pageTitle = 'Sing Up';   // page title name
    $nonavbar = '';
    include 'init.php';

     $do = isset($_GET['do']) ? $_GET['do'] : '';
    // insert page  
        if($do == 'sign'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $username = $_POST['username'];
                $pass     = $_POST['password'];
                $password = sha1($_POST['password']);
                $email    = $_POST['email'];
                $fullname =  $_POST['fullname'];

// validate insert form

                $formErrors = array();
                
                if(empty($username)){
                    $formErrors[] = '<div class="alert alert-danger">Username field cant be empty</div>';
                }
                if(empty($email)){
                    $formErrors[] = '<div class="alert alert-danger">Email field cant be empty</div>';
                }
                
                foreach($formErrors as $error){
                    echo $error;
                }
//check all the fields are validate
                
                if(empty($formErrors)){
                    $check = checkExist("username", "user", $username);
                    
                    if($check == 1){
                        $msg = "<div class='container'><div class='alert alert-danger'>This username is already used, please choose another name</div></div>";
                        redirectHome($msg, 'back');
                        
                    }else{

 // INSERT SQL          
                        global $con;
                        global $stmt;

                        $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,regstatus,date) values(?,?,?,?,1,now())");
                        $stmt->execute( array($username,$password,$email,$fullname) );
                        echo '<h1 class="text-center">Signup page</h1>';
                        $msg =  "<div class='container'><div class='alert alert-success'> Successfull Signup wait for the Approving </div></div>";
                        redirectHome($msg);
                         
                    }      
                }else{
                    foreach($formErrors as $error)
                    {
                        echo $error;
                    }
                }            
            }else{
                $msg = "<div class='container'><div class='alert alert-danger'>You can't browse this page directly</div></div>";
                redirectHome($msg);
            }
        }
?>    
 
 <h1 class="text-center">Add New Member Profile</h1>
                    <div class="container">
                        <form class="form-horizontal" action="signup.php?do=sign" method="POST">
                    <!-- start username -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-sm-6">
                                    <input type="text" name="username" autocomplete="off" class="username-alert form-control" required = "required"/>
                                    <div class="alert alert-danger">Username field cant be less than 3 characters</div>
                                </div>
                            </div>
                    <!-- end username -->            
                    <!-- start Password -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-sm-6">               
                                    <input type="Password" name="password" autocomplete="new-password" class="password form-control" required = "required" />
                                    <div class="alert alert-danger">password cant be less than 2</div>
                                    <i class="show-pass fa fa-eye fa-2x"></i>
                                </div>
                            </div>
                    <!-- end Password -->

                    <!-- start Email -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="Email" name="email" class="form-control" required = "required" />
                                </div>
                            </div>
                    <!-- end Email -->

                    <!-- start fullname -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Full name</label>
                                <div class="col-sm-6">
                                    <input type="name" name="fullname" autocomplete="off" class="form-control" require = "required" />
                                </div>
                            </div>
                    <!-- end fullname -->

                    <!-- start button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Signup " class="btn btn-primary btn-lg">
                                </div>
                            </div>
                    <!-- end button -->
                        </form>
                    </div>
   <?php              
 include $temp . 'footer.php';

 ?>