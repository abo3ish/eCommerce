<?php

	ob_start();
	SESSION_START();
	$pageTitle = 'Items';

	 if(isset($_SESSION['username'])){
		include 'init.php';
		$items = getAllFrom("*","items",null,null,"itemid","desc");
		$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

		if($do == 'manage'){ ?>

		<h1 class="text-center">Items Details</h1>
		<div class="container">
			<div class="table-resposive">
				<table class=" main-table table table-bordered text-center">
						<tr>
							<td>ID</td>
							<td>Name</td>
							<td>Description</td>
							<td>Country</td>
							<td>Add Date</td>
							<td>Price</td>
							<td>Control</td>
						</tr>
				<?php
					foreach($items as $item){
						echo "<tr>";
							echo "<td>" . $item['itemid'] . "</td>";
							echo "<td>" . $item['item_name'] . "</td>";
							echo "<td>"; 
								if($item['description'] == '0'){
										echo "<h4>No description</h4>";
								} else{
										echo "<h4>" . $item['description'] . "</h4>";
								}  
							echo "</td>";
							echo "<td>" . $item['country'] . "</td>";
							echo "<td>" . $item['add_date'] . "</td>";
							echo "<td>" . $item['price'] . "</td>";
							echo "<td> 
												   <a href='items.php?do=edit&itemid=" . $item['itemid'] . "'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												   <a href='items.php?do=delete&itemid=" . $item['itemid'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
												   if($item['approve'] == 0){
												   		echo "<a href='items.php?do=approve&itemid=" . $item['itemid'] . "' class='btn btn-info confirm'><i class='fa fa-check'></i> Approve</a>";
						echo "</tr>";				}
						}
					?>

				</table>
				<a href="items.php?do=add" class="btn btn-primary btn-lg">Add Item</a>
			</div>
		</div>
		<?php

// ADD ITEM

		} elseif ($do == 'add'){ ?>

			<h1 class="text-center">Add Item</h1>
				<div class="container">
					<form class="form-horizontal" action='?do=insert' method='POST'>
				<!-- start item name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="name"
									class="form-control"
									required="require"

								/>
							</div>
						</div>
				<!-- end item name -->
				<!-- start item Description -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="description"
									class="form-control"
								/>
							</div>
						</div>
				<!-- end item Description -->
				<!-- start item price -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="price"
									class="form-control"
									required="require"

								/>
							</div>
						</div>
				<!-- end item price -->

				<!-- start item Country -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="country"
									class="form-control"
								/>
							</div>
						</div>
				<!-- end item Country -->
				<!-- start item status -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="status">
									<option value='0'>...</option>
									<option value='1'>New</option>
									<option value='2'>Used</option>
									<option value='3'>Like New</option>
									<option value='4'>Old</option>
								</select>
							</div>
						</div>
				<!-- start item status -->

				<!-- start item seller -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Seller Name</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="seller">
									<option value='0'>...</option>
								<?php
									$users = getAllFrom("*","users",null,null,"userid","desc");
									foreach($users as $user){
										echo "<option value = '" . $user['userid'] . "'>" . $user['username'] ."</option>";
									}
									?>
								</select>
							</div>
						</div>
				<!-- end item seller -->
				<!-- start item category -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="cat">
									<option value='0'>...</option>
								<?php
									$cats = getAllFrom("*","categories",null,null,"catid","desc");
									foreach($cats as $cat){
										echo "<option value = '" . $cat['catid'] . "'>" . $cat['name'] ."</option>";
									}
									?>
								</select>
							</div>
						</div>
				<!-- end item category -->
				<!-- start item Country -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Tags</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="tags"
									class="form-control"
								/>
							</div>
						</div>
				<!-- end item Country -->



						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input class="btn btn-primary btn-lg" type="submit" value="Add Item">
							</div>
						</div>
					 </form>
				 </div>
		<?php

// INSERT PAGE 

		} elseif($do == 'insert'){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$name = $_POST['name'];
				$desc = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['country'];
				$status = $_POST['status'];
				$seller = $_POST['seller'];
				$cat = $_POST['cat'];
				$tags = $_POST['tags'];

				$formErrors = array();

				if(empty($name)){
					$formErrors[] = "The Name Can't be <strong>Empty</strong>";
				}

				if(empty($price)){
					$formErrors[] = "The Price Can't be <strong>Empty</strong>";
				}

				if($status == 0){
					$formErrors[] = "The status Can't be <strong>Empty</strong>";
				}
				if($seller == 0){
					$formErrors[] = "The Seller Can't be <strong>Empty</strong>";
				}
				if($cat == 0){
					$formErrors[] = "The Category Can't be <strong>Empty</strong>";
				}

				foreach($formErrors as $msg){
					$msg = "<div class='alert alert-danger'>" . $msg . "</div>";
					redirectHome($msg,'items');

				}

				if(empty($formErrors)){

					$stmt = $con->prepare("INSERT INTO items (item_name,description,price,country,status,member_id,cat_id,add_date,tags) VALUES(?,?,?,?,?,?,?,now())");
					$stmt->execute(array($name,$desc,$price,$country,$status,$seller,$cat,$tags));
					echo '<h1 class="text-center">INSERT Category</h1>';
						$msg =  "<div class='alert alert-success'> Record inserted</div>";
						redirectHome($msg, 'back');
				}


			} else{
				$msg = "<div class='container'><div class='alert alert-danger'>You can't browse this page directly</div></div>";
				redirectHome($msg);
			}

// EDIT PAGE

		} elseif ($do == 'edit'){ 

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
								  
			$stmt = $con->prepare("SELECT *
									FROM items 
									WHERE itemid=?
									LIMIT 1");
			$stmt->execute(array($itemid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			// check if the item exist

			$count = checkExist('itemid' , 'items', $itemid);
			if($count > 0){

			?>

			<h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action='?do=update' method='POST'>
				<!-- start item name -->
						<input type="hidden" name="itemid" value = "<?php echo $itemid; ?>" />
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="name"
									class="form-control"
									required="require"
									value = "<?php echo $row['item_name'] ?>"

								/>
							</div>
						</div>
				<!-- end item name -->
				<!-- start item Description -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="description"
									class="form-control"
									value = "<?php echo $row['description'] ?>"
								/>
							</div>
						</div>
				<!-- end item Description -->
				<!-- start item price -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="price"
									class="form-control"
									required="require"
									value = "<?php echo $row['price'] ?>"

								/>
							</div>
						</div>
				<!-- end item price -->

				<!-- start item Country -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="country"
									class="form-control"
									value = "<?php echo $row['country'] ?>"
								/>
							</div>
						</div>
				<!-- end item Country -->
				<!-- start item status -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="status">
									<option value='0'>...</option>
									<option value='1' <?php if($row['status'] == 1) {echo "selected";} ?> >New</option>
									<option value='2' <?php if($row['status'] == 2) {echo "selected";} ?> >Used</option>
									<option value='3' <?php if($row['status'] == 3) {echo "selected";} ?> >Like New</option>
									<option value='4' <?php if($row['status'] == 4) {echo "selected";} ?> >Old</option>
								</select>
							</div>
						</div>
				<!-- start item status -->

				<!-- start item seller -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Seller Name</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="seller">
									<option value='0'>...</option>
								<?php
									$users = getAllFrom("*","users",null,null,"userid","desc");
									 foreach($users as $user){
										echo "<option value = '" . $user['userid'] . "'";
										 if($row['member_id'] == $user['userid']) {echo "selected";}
										 echo ">" . $user['username'] . "</option>";
									}
									?>
								</select>
							</div>
						</div>
				<!-- end item seller -->
				<!-- start item category -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-md-6 col-sm-10">
								<select class="form-control" name="cat">
									<option value='0'>...</option>
								<?php
									$cats = getAllFrom("*","categories",null,null,"catid","desc");		
									foreach($cats as $cat){
										echo "<option value = '" . $cat['catid'] . "'";
										if($row['cat_id'] == $cat['catid']) {echo "selected";} 
										echo ">" . $cat['name'] . "</option>";
									}
								?>
								</select>
							</div>
						</div>
				<!-- end item category -->
				<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Tags</label>
							<div class="col-md-6 col-sm-10">
								<input
									type="text"
									name="tags"
									class="form-control"
									value="<?php echo $row['tags']?>"
								/>
							</div>
						</div>


						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input class="btn btn-primary btn-lg" type="submit" value="Save Item">
							</div>
						</div>
					 </form>
				 </div>

		<?php
				} else {
					$msg = "<div class='alert alert-danger'>there is no such item</div>";
					redirectHome($msg, 'items');
				}

// UPDATE PAGE

		} elseif($do == 'update'){ ?>
			<h1 class="text-center">Update Page</h1>
			<?php

				if($_SERVER['REQUEST_METHOD'] == 'POST'){

					$formErrors = array();

					$itemid = $_POST['itemid'];
					$name = $_POST['name'];
					$desc = $_POST['description'];
					$price = $_POST['price'];
					$country = $_POST['country'];
					$seller = $_POST['seller'];
					$status = $_POST['status'];
					$cat = $_POST['cat'];
					$tags = $_POST['tags'];

						if(empty($name)){
							$formErrors[] = "the name can't be empty";
						}
						if(empty($price)){
							$formErrors[] = "the price can't be empty";
						}
						if(empty($formErrors)){

							$stmt =  $con->prepare("UPDATE items SET
								item_name = ?,
								description = ?,
								price = ?,
								country = ?,
								member_id = ?,
								status = ?,
								cat_id = ?,
								tags=?
								WHERE itemid = ?" );
							$stmt->execute(array($name, $desc, $price, $country, $seller, $status, $cat,$tags,$itemid));
							$msg = "<div class='alert alert-success'>". $stmt->rowCount(). " Record updated</div>"; 
							redirectHome($msg, 'items');
						} else{
							foreach($formErrors as $error){
								echo $error;
							}
						}
					
				} else {
					$msg = "<div class='alert alert-danger'> You can't browse this page directly </div>";
					redirectHome($msg);
				}

// APPROVE PAGE 

		} elseif ($do == 'approve'){

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			$count = checkExist('itemid' , 'items', $itemid);
			if($count > 0){
				$stmt = $con->prepare("UPDATE items SET approve = 1 WHERE itemid = ?");
				$stmt->execute(array($itemid));

				$msg = "<div class='alert alert-success'>". $stmt->rowCount(). " Record approved</div>"; 
				redirectHome($msg, 'items');

			} else{
				$msg = "<div class='alert alert-danger'>Item can't be found</div>";
				redirectHome($msg, 'items');
			}

		 }
			
// DELETE PAGE

		elseif($do == 'delete'){
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 
			$count = checkExist('itemid' , 'items', $itemid);
			if($count > 0){
				$stmt = $con->prepare("DELETE FROM items WHERE itemid = ?");
				$stmt->execute(array($itemid));
				$msg = "<div class='alert alert-success'>". $stmt->rowCount(). " Record Deleted</div>"; 
				redirectHome($msg, 'items');


			} else{
				$msg = "<div class='alert alert-danger'>Item can't be found</div>";
				redirectHome($msg, 'items');
			}

		}

		include $temp . 'footer.php';
	} else {
		header('location:index.php');
	}

	ob_end_flush();
?>
