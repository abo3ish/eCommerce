<?php 
ob_start();
session_start();
$pageTitle = 'Comments';

if(isset($_SESSION['username'])){

	include 'init.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

	if($do == 'manage'){
			$stmt = $con->prepare("SELECT 
										comments.*,
										items.item_name AS item,
										users.username AS name
									FROM 
										comments	
								 	JOIN 
										items
									ON 
										items.itemid = comments.item_id
									JOIN 
										users
									ON	
										users.userid = comments.user_id
									 	
										");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            
             ?> 
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-resposive comments">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item</td>
                            <td>user Name</td>
                            <td>Comment Date</td>
                            <td>Control</td>
                        </tr>
                        
                            <?php
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>".$row['commentid'] . "</td>";
                                        echo "<td>".$row['comment_text'] . "</td>";
                                        echo "<td>".$row['item'] . "</td>";
                                        echo "<td>".$row['name'] . "</td>";
                                        echo "<td>".$row['comment_date']."</td>";
                                   
                                        echo "<td> 
                                                   <a href='comments.php?do=edit&commentid=" . $row['commentid'] . "'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                   <a href='comments.php?do=delete&commentid=" . $row['commentid'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                            
                                                if ($row['status'] == 0){
                                                   echo "<a href='comments.php?do=approve&commentid=" . $row['commentid'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                                }
                                        echo "</td>";                    
                                    echo "</tr>";
                                }
                            ?>   
                        
                    </table>    
                </div>
            </div>     
        <?php 	
	} 

// EDIT PAGE
	elseif($do == 'edit'){

		$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
		$count = checkExist('commentid', 'comments', $commentid);
		$stmt = $con->prepare("SELECT commentid,comment_text FROM comments WHERE commentid=?");
		$stmt->execute(array($commentid));
		$row = $stmt->fetch();	
		if($count > 0){ ?>
			<h1 class="text-center">Edit Comment</h1>
			<div class="container">
				<form class="form-horizontal" action="comments.php?do=update" method="POST">
					<div class="form-group form-group-lg edit-comment">
									<input type="hidden" name="commentid" value="<?php echo $commentid ?>">
									<label class="col-sm-2 control-label">Comment</label>
									<div class="col-md-6 col-sm-10">
										<textarea required="require" name = "comment_text"class="form-control" /><?php echo $row['comment_text']; ?></textarea>
									</div>
					</div>
					<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input class="btn btn-primary btn-sm" type="submit" value="Save Comment">
							</div>
					</div>
				</form>
			</div>

			<?php

		}

	}

// UPDATE PAGE
	elseif($do == 'update'){
	 	if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$commentid 		= $_POST['commentid'];
	 		$comment_text 	= $_POST['comment_text'];

	 		if(!empty($comment_text)){

		 		$stmt = $con->prepare("UPDATE Comments SET comment_text=? WHERE commentid=?");
		 		$stmt->execute(array($comment_text,$commentid));

		 		$msg = "<div class='alert alert-success'>Your Comment had been updated</div>";
	 			redirectHome($msg,'Pervious Page');
	 		} else{
	 			$msg = "<div class='alert alert-danger'>This field can't be EMPTY</div>";
	 			redirectHome($msg,'back');
	 		}
	 	} else{
	 		$msg = "<div class='alert alert-danger'>You can't browse this page directly</div>";
	 		redirectHome($msg,'Pervious Page');
	 	}
	}	

// DELETE PAGE
	elseif($do == 'delete'){
		$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
		$count = checkExist('commentid' , 'comments', $commentid);
		if($count > 0){
			$stmt = $con->prepare("DELETE FROM Comments WHERE commentid=?");
			$stmt->execute(array($commentid));

			$msg = "<div class='alert alert-success'>Your Comment had been Deleted</div>";
		 	redirectHome($msg,'Pervious Page');
	 	} else{
				$msg = "<div class='alert alert-danger'>Comment can't be found</div>";
				redirectHome($msg);
			}
	} 

// APPROVE PAGE 

	elseif($do == 'approve'){

		$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
		$count = checkExist('commentid' , 'comments', $commentid);
		if($count > 0){
			$stmt = $con->prepare("UPDATE Comments SET status = 1 WHERE commentid=?");
			$stmt->execute(array($commentid));

			$msg = "<div class='alert alert-success'>Your Comment had been Approved</div>";
		 	redirectHome($msg,'Pervious Page');
	 	} else{
				$msg = "<div class='alert alert-danger'>Comment didn't approved yet</div>";
				redirectHome($msg);
			}
	}

	include $temp . 'footer.php';
	
} else{
        header('location: index.php');
        exit();
    }



?>