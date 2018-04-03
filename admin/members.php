<?php
    session_start();

    $pageTitle = 'Members';

    if(isset($_SESSION['username'])){
        include 'init.php';
        
        // if request = 'do' go to 'do' pages else go to 'manage' page 
        //يعني لو كتبت اي حاجة غير ال do
        // هيوديني لصفحة ال manage
       
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
// manage page   

        if($do == 'manage'){
        // showing only the pending users     
            $query = '';
            if(isset($_GET['page']) && $_GET['page'] == 'pending'){
                $query = "AND regstatus = 0";
            }

        $rows = getAllFrom("*","users","WHERE groupid !=1",$query,"userid","ASC");
?> 

            <h1 class="text-center">Members Profiles</h1>
            <div class="container">
                <div class="table-resposive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Register Date</td>
                            <td>Control</td>
                        </tr>
                        
                            <?php
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>".$row['userid'] . "</td>";
                                        echo "<td>".$row['username'] . "</td>";
                                        echo "<td>".$row['email'] . "</td>";
                                        echo "<td>".$row['fullname']."</td>";
                                        echo "<td>".$row['date']."</td>";
                                        echo "<td> 
                                                   <a href='members.php?do=edit&userid=" . $row['userid'] . "'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                   <a href='members.php?do=delete&userid=" . $row['userid'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                            
                                                if ($row['regstatus'] == 0){
                                                   echo "<a href='members.php?do=activate&userid=" . $row['userid'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                                }
                                        echo "</td>"; 
                                        
                                                          
                                    echo "</tr>";
                                }
                            ?>   
                        
                    </table>    
                </div>

                <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>

            </div>
                
            
            
        <?php }

// add page

        elseif($do == 'add'){ ?>
          <h1 class="text-center">Add New Member Profile</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=insert" method="POST">
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
                                    <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                    <!-- end button -->
                        </form>
                    </div>
                   
                     
                    

        <?php 

// insert page  

        }elseif($do == 'insert'){
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
                    $check = checkExist("username", "users", $username);
                    
                    if($check == 1){
                        $msg = "<div class='container'><div class='alert alert-danger'>This username is already used, please choose another name</div></div>";
                        redirectHome($msg, 'back');
                        
                    }else{
 // INSERT SQL                   
                        $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,date) values(?,?,?,?,now())");
                        $stmt->execute( array($username,$password,$email,$fullname) );
                        echo '<h1 class="text-center">INSERT page</h1>';
                        $msg =  "<div class='container'><div class='alert alert-success'>". $stmt->rowCount(). " Record inserted</div></div>";
                        redirectHome($msg, 'members');
                         
                    }      
                }
                       
            }else{
                $msg = "<div class='container'><div class='alert alert-danger'>You can't browse this page directly</div></div>";
                redirectHome($msg);
            }
        }

// edit page

        elseif($do == "edit"){ 
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                                  
            $stmt = $con->prepare("select fullname,username,email,password,userid
                                from users 
                                where userid=?
                                LIMIT 1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            
            if($count > 0){ 
            ?>
        <!-- Edit page -->
                <div class="editprofile">
                    <h1 class="text-center">Edit Profile</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=update" method="post">
                            <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                            <!-- start username -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-sm-6">
                                    <input type="text" name="username" autocomplete="off" value="<?php echo $row['username']  ?>" class="username-alert form-control" required = "required"/>
                                    <div class="alert alert-danger">Username field cant be less than 3 characters</div>
                                </div>
                            </div>
                            <!-- end username -->
                             <!-- start Password -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?> "/>
                                    <input type="Password" name="newpassword" autocomplete="new-password" class="password-alert form-control" required = "required" />
                                    <div class="alert alert-danger">password cant be less than 2</div>
                                </div>
                            </div>
                            <!-- end Password -->

                             <!-- start Email -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="Email" name="email" autocomplete="off" value="<?php echo $row['email']  ?>" class="form-control" required = "required" />
                                </div>
                            </div>
                            <!-- end Email -->

                            <!-- start fullname -->
                            <div class="form-group form-group-lg">
                                <label class="col-md-2 control-label">Full name</label>
                                <div class="col-sm-6">
                                    <input type="name" name="fullname" autocomplete="off" value="<?php echo $row['fullname']  ?>" class="form-control" />
                                </div>
                            </div>
                            <!-- end fullname -->

                            <!-- start fullname -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Save" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                            <!-- end fullname -->
                        </form>
                    </div>
                </div>  
                <!-- end from -->

        <?php 
            } // end $count > 0
            else{
                $msg = '<div class="container"><div class="alert alert-danger">Member can\'t be fount</div></div>';
                 redirectHome($msg);
            }
                        
        } // End do = edit
            
// Update Page
        
        elseif($do == "update"){
                
            echo '<h1 class="text-center">welcome to Update Page</h1>';
            if($_SERVER['REQUEST_METHOD']=='POST'){
                
                // variable from the Form
                
                $userid   = $_POST['userid'];
                $username = $_POST['username'];
                $email    = $_POST['email'];
                $fullname = $_POST['fullname'];
                
                // updating the password
                
                $pass = '';
                if(empty($_POST['newpassword'])){
                   $pass = $_POST['oldpassword']; 
                } else{
                    $pass = sha1($_POST['newpassword']);
                }
                
            // form validation
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

 // UPDATE SQL                       
                        $record = getAllFrom("username","users","WHERE userid !={$userid}",null,"userid","ASC");
                        $key = in_array($username, array_column($record, 'username'));

                        if($key == 1){
                            $msg = "<div class='alert alert-danger'>This User name is already used, please choose another name</div>";
                            redirectHome($msg, 'back'); 
                       
                        } else{

                            $stmt = $con->prepare("UPDATE users 
                                                                SET 
                                                                    username=? ,
                                                                    email=? ,
                                                                    fullname=?,
                                                                    password=?
                                                                WHERE 
                                                                    userid=?");

                            $stmt->execute(array($username,$email,$fullname,$pass,$userid));
                            $msg = "<div class='alert alert-success'>". $stmt->rowCount(). " Record updated</div>"; 
                            redirectHome($msg, 'back');
                        }
                     
                   
                } else{
                    foreach($formErrors as $error)
                    {
                        echo $error;
                    }
                }
            } // end of "if it's post method"
            else{   // if it's not POST method 
                $msg =  "<div class='alert alert-danger'>You can't enter this page directly</div>";
                redirectHome($msg);
            }
            
        } // end of if do = 'update'

// start DELETE page

        elseif($do == 'delete'){
            echo '<h1 class="text-center">welcome to DELETE Page</h1>';

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $count = checkExist('userid' , 'users', $userid);
            
            if($count > 0){
                echo '<h1 class="text-center">welcome to DELETE Page</h1>';
// DELETE SQL
                $stmt = $con->prepare("DELETE FROM users WHERE userid= $userid");
                $stmt->execute();

                 $msg =  "<div class='container'><div class='alert alert-success'>". $count . " Record Deleted</div></div>";
                redirectHome($msg, 'members');  
            } 
            else{
                $msg = "<div class='container'><div class='alert alert-danger'>Member can't be found</div></div>";
                redirectHome($msg, 'members');
            } 
        } // end Delete page
// activate page        
        elseif($do == 'activate'){

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $count = checkExist('userid' , 'users', $userid);
            
            if($count > 0){
                echo '<h1 class="text-center">welcome to ACTIVATE Page</h1>';
// Activate SQL
                $stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userid= $userid");
                $stmt->execute();

                 $msg =  "<div class='container'><div class='alert alert-success'>". $count . " Record activated</div></div>";
                redirectHome($msg, 'back');  
            } 
            else{
                $msg = "<div class='container'><div class='alert alert-danger'>Member can't be found</div></div>";
                redirectHome($msg, 'members');
            }  // end of activate page 

        }
        else{  // if there is no 'do' page <recognise></recognise>
             $msg = "<div class='container'><div class='alert alert-danger'>ERROR 404</div></div>";
            redirectHome($msg);
        }
 
        
        include $temp . 'footer.php';
        
    }else{
        header('location: index.php');
        exit();
    }

