<?php
    ob_start();
    SESSION_START();

    $pageTitle = 'Categories';
    if(isset($_SESSION['username'])){
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

        if($do == 'manage'){

            $sort = 'ASC';
            $sort_order = array('ASC', 'DESC');

            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_order)){
                $sort = $_GET['sort'] ;
            }
            $cats = getAllFrom("*","categories","WHERE parent = 0",null,"name",$sort);

        ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <span><i class="fa fa-edit"></i> Categories Panel</span>
                        <div class="ordering pull-right">
                            <i class="fa fa-sort"></i> Sort : [ 
                            <a href="?sort=ASC" class="<?php if($sort == 'ASC') {echo "active";}?>">Asc</a>|
                            <a href="?sort=DESC" class="<?php if($sort == 'DESC') {echo "active";}?>"> Desc</a> ]
                            <i class="fa fa-eye"></i>  View : [
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">  Classic</span> ]
                        </div>
                    </div>
                    <div class="panel-body">   
                        <?php
                            foreach($cats as $cat){
                                echo "<div class='cats'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a  href='?do=edit&catid=" . $cat['catid'] .  "'class='btn btn-primary btn-xs'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a href='?do=delete&catid=" . $cat['catid'] . "'class=' confirm btn btn-danger btn-xs'><i class='fa fa-close'></i> Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['name'].  "</h3>";
                                    $rows = getAllFrom("*","categories","WHERE parent = {$cat['catid']}",null,"name","ASC");
                                        foreach($rows as $c){
                                            echo "<ul class='list-unstyled subcats'>";
                                                echo "<li><a href='?do=edit&catid=" . $c['catid'] . "'>" . $c['name'] . "</a></li>";
                                            echo "</ul>";
                                    }
                                    echo "<div class='full-view'>";
                                        if($cat['description'] == ''){
                                            echo "<h4>No description</h4>";
                                        } else{
                                            echo "<h4>" . $cat['description'] . "</h4>";
                                        } 
                                        if($cat['visibility'] == 0){
                                            echo "<span class='visibility'>Hidden</span>";
                                        }
                                        if($cat['Commenting'] == 0){
                                           echo "<span class='commenting'> Commenting Disabled</span>"; 
                                        }
                                        if($cat['ads'] == 0){
                                           echo "<span class='ads'> Ads Disabled</span>"; 
                                        } 

                                    echo "</div>";      
                            echo "</div>";
                            echo "<hr>";
                            }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=add" class="add btn btn-primary" ><i class="fa fa-plus"></i> Add new Category</a>
            </div>

            <?php
        }

// ADD PAGE 

        elseif($do == 'add'){ ?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action='?do=insert' method='POST'>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="name" class="form-control username-alert" required = "required"> 
                            <div class="alert alert-danger">Name can't be less than 2 </div>  
                        </div> 
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="description" class="form-control">
                        </div> 
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-md-6 col-sm-10">
                            <select class="form-control" name="parent">
                                <option value="0">Main category</option>
                               <?php 
                                   $rows = getAllFrom("*","categories","WHERE parent = 0",null,"name","ASC");
                                    foreach($rows as $cat){
                                        echo "<option value = '". $cat['catid'] . "'>" . $cat['name'] . "</option>";
                                    }   
                               ?>
                            </select>
                        </div> 
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="ordering" class="form-control">
                        </div> 
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">visibility</label>
                            <div class="col-md-6 col-sm-10">
                                <div>
                                    <input type="radio" id="vis-yes" name="visibility" value="1" checked="checked">
                                    <label for="vis-yes">Yes</label>  
                                </div>
                                <div>
                                     <input type="radio" id="vis-no" name="visibility" value="0">
                                    <label for="vis-no">No</label>
                                </div>    
                        </div>  
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Commenting</label>
                            <div class="col-md-6 col-sm-10">
                                <div>
                                    <input type="radio" id="com-yes" name="commenting" value="1" checked="checked">
                                    <label for="com-yes">Yes</label>
                                    
                                </div>
                                <div>
                                    <input type="radio" id="com-no" name="commenting" value="0">
                                    <label for="com-no">No</label>
                            
                                </div>    
                        </div>  
                    </div>

                    <div class="form-group form-group-lg">    
                        <label class="col-sm-2 control-label">Ads</label>
                            <div class="col-md-6 col-sm-10">
                                <div>
                                    <input type="radio" id="ads-yes" name="ads" value="1" checked="checked">
                                    <label for="ads-yes">Yes</label>
                                    
                                </div>
                                <div>
                                    <input type="radio" id="ads-no" name="ads" value="0" >
                                    <label for="ads-no">No</label>
                            
                                </div>    
                        </div>  
                    </div>

                   
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary btn-lg" type="submit" value="Add Category">
                        </div>
                    </div>

                </form>

            </div>

          <?php  
        }

// INSERT PAGE  

        elseif($do == 'insert'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads']; 
                $parent = $_POST['parent'];

                if(!empty($name)){   // checking if the name is empty

                    $check = checkExist('name', 'Categories', $name);

                    if($check == 0){
                        $stmt = $con->prepare("INSERT Categories(name, description, ordering, visibility, commenting, ads,parent)   VALUES(?,?,?,?,?,?,?)");
                        $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads,$parent));
                        $rows = $stmt->rowCount();
                        echo '<h1 class="text-center">INSERT Category</h1>';
                        $msg =  "<div class='container'><div class='alert alert-success'>". $stmt->rowCount(). " Record inserted</div></div>";
                        redirectHome($msg, 'back');

                    } else{  // else $check !=1
                        $msg = "<div class='container'><div class='alert alert-danger'>This username is already used, please choose another name</div></div>";
                        redirectHome($msg, 'back');
                    } 

                } else{ // if the name is empty
                   $msg = "<div class='container'><div class='alert alert-danger'>This username is can't be EMPTY</div></div>";
                        redirectHome($msg, 'back'); 
               }
    
            } else{  // else the request is not POST REQUEST
                 $msg = "<div class='container'><div class='alert alert-danger'>You can't browse this page directly</div></div>";
                redirectHome($msg);
            }

        }
// EDIT PAGE         
        elseif($do == 'edit'){ 

            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            $stmt = $con->prepare("SELECT name,description,ordering,visibility,Commenting,ads,catid,parent FROM categories WHERE catid=?");
            $stmt->execute(array($catid));
            $rows = $stmt->fetch();
            $check = checkExist("catid", "categories",$catid);
            if($check == 1){
            ?>

            <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action='?do=update' method='POST'>

                        <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-md-6 col-sm-10">
                                <input type="text" name="name" class="form-control username-alert" value="<?php echo $rows['name']; ?>"> 
                                <div class="alert alert-danger">Name can't be less than 2 </div>  
                            </div> 
                        </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-md-6 col-sm-10">
                                <input type="text" name="description" class="form-control" value="<?php echo $rows['description']; ?>">
                            </div> 
                        </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Parent</label>
                            <div class="col-md-6 col-sm-10">
                                <select class="form-control" name="parent">
                                <option value="0">Main category</option>
                               <?php 
                                    $cats = getAllFrom("*","categories","WHERE parent = 0",null,"catid","ASC");
                                    foreach($cats as $cat){
                                        echo "<option value = '" . $cat['catid'] . "'";
                                        if($rows['parent'] == $cat['catid']){echo "selected";}
                                        echo ">";
                                        echo  $cat['name'] . "</option>";
                                    }   
                               ?>
                            </select>
                        </div> 
                    </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-md-6 col-sm-10">
                                <input type="text" name="ordering" class="form-control" value="<?php echo $rows['ordering']; ?>">
                            </div> 
                        </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">visibility</label>
                                <div class="col-md-6 col-sm-10">
                                    <div>
                                        <input type="radio" id="vis-yes" name="visibility" value="1" checked="checked">
                                        <label for="vis-yes">Yes</label>  
                                    </div>
                                    <div>
                                         <input type="radio" id="vis-no" name="visibility" value="0">
                                        <label for="vis-no">No</label>
                                    </div>    
                            </div>  
                        </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Commenting</label>
                                <div class="col-md-6 col-sm-10">
                                    <div>
                                        <input type="radio" id="com-yes" name="commenting" value="1" checked="checked">
                                        <label for="com-yes">Yes</label>
                                        
                                    </div>
                                    <div>
                                        <input type="radio" id="com-no" name="commenting" value="0">
                                        <label for="com-no">No</label>
                                
                                    </div>    
                            </div>  
                        </div>

                        <div class="form-group form-group-lg">    
                            <label class="col-sm-2 control-label">Ads</label>
                                <div class="col-md-6 col-sm-10">
                                    <div>
                                        <input type="radio" id="ads-yes" name="ads" value="1" checked="checked">
                                        <label for="ads-yes">Yes</label>
                                        
                                    </div>
                                    <div>
                                        <input type="radio" id="ads-no" name="ads" value="0" >
                                        <label for="ads-no">No</label>
                                
                                    </div>    
                            </div>  
                        </div>

                       
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input class="btn btn-primary btn-lg" type="submit" value="Update Category">
                                <?php 
                                    $stmt = $con->prepare("SELECT parent,catid FROM categories WHERE catid=?");
                                    $stmt->execute(array($catid));
                                    $parent = $stmt->fetch();
                                    if($parent['parent'] != 0){
                                       echo "<a href='?do=delete&catid=" . $catid . "'class=' confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                    }
                                ?>
                            </div>
                        </div>

                    </form>

                </div>
            
    <?php   
            } else{
        echo "<div class='alert alert-danger'>there is no such ID</div>";
            }
        }

// UPDATE PAGE         
        elseif($do == 'update'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                $catid= $_POST['catid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads']; 
                $parent = $_POST['parent'];

                if(!empty($name)){   // checking if the name is empty
                    $records = getAllFrom("name","categories","WHERE catid!={$catid}",null,"catid","ASC");
                    $key = in_array($name, array_column($records, 'name'));

                    if($key == 0){
                        $stmt = $con->prepare("UPDATE Categories SET 
                                                                    name=?,
                                                                    description=?,
                                                                    ordering=?, 
                                                                    visibility=?,
                                                                    commenting=?,
                                                                    ads=?,
                                                                    parent=?
                                                                WHERE 
                                                                    catid=?");                                            
                        $stmt->execute(array($name,$desc,$order,$visible,$comment,$ads,$parent,$catid));
                        $rows = $stmt->rowCount();
                        echo '<h1 class="text-center">INSERT Category</h1>';
                        $msg =  "<div class='alert alert-success'>". $stmt->rowCount()." Record inserted</div>";
                        redirectHome($msg, 'back');

                    } else{  // else $key !=1
                        $msg = "<div class='alert alert-danger'>This Category name is already used, please choose another name</div>";
                        redirectHome($msg, 'back');
                    }
                    

                } else{ // if the name is empty
                   $msg = "<div class='container'><div class='alert alert-danger'>This username is can't be EMPTY</div></div>";
                        redirectHome($msg, 'back'); 
               }
    
            } else{  // else the request is not POST REQUEST
                 $msg = "<div class='container'><div class='alert alert-danger'>You can't browse this page directly</div></div>";
                redirectHome($msg);
            }

        }
// DELETE PAGE         
         elseif($do == 'delete'){
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            $count = checkExist('catid' , 'categories', $catid);
            
            if($count > 0){
                echo '<h1 class="text-center">welcome to DELETE Page</h1>';
// DELETE SQL
                $stmt = $con->prepare("DELETE FROM categories WHERE catid= $catid");
                $stmt->execute();

                 $msg =  "<div class='container'><div class='alert alert-success'>". $count . " Record Deleted</div></div>";
                redirectHome($msg, 'back');  
            } 
            else{
                $msg = "<div class='container'><div class='alert alert-danger'>Category can't be found</div></div>";
                redirectHome($msg, 'back');
            } 
        } // end Delete page


        
        include $temp . 'footer.php';

    }
    else{
        header('location:index.php');
    }

      

    ob_end_flush();
?>