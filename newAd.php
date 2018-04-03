<?php 
	ob_start();
	session_start();
	include 'init.php';
	if(isset($_SESSION['user'])){ 

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$name 	=  filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 	=  filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
			$price 	= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$cat 	= filter_var($_POST['cat'], FILTER_SANITIZE_NUMBER_INT);

			$fromErrors = array();

			if(strlen($name) < 3){
				$fromErrors[] = "Title can't be less than 3 letters";	
			}
			if(strlen($desc) < 5){
				$fromErrors[] = "Description can't be less than 5 letters";	
			}	
			if(empty($price)){
				$fromErrors[] = "Price can't ne empty";	
			}
			if(strlen($country) < 2){
				$fromErrors[] = "Country can't be less than 5 letters";	
			}
			if(empty($status)){
				$fromErrors[] = "status can't ne empty";	
			}
			if(empty($cat)){
				$fromErrors[] = "Category can't ne empty";	
			}
			if(empty($fromErrors)){	
				$stmt = $con->prepare("INSERT INTO items (item_name,description,price,country,status,cat_id,member_id,add_date) VALUES(?,?,?,?,?,?,?,now())");
					$stmt->execute(array($name,$desc,$price,$country,$status,$cat,$_SESSION['userid']));
			}
			if($stmt){		
				echo "<div class='container'><div class='alert alert-success'>Item added successfully, please wait for the approvation</div></div>";
				header("refresh:5;url=profile.php");
			}	
		}
	?>
		<h1 class="text-center">Create New Ad</h1>
		<div class="newAd">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">Create Ad</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8 col-sm-12">
								<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
									<div class="form-group form-group-lg">
										<label class="control-label col-sm-2 ">Item Name</label>
										<div class="col-sm-9">
											<input 
											type="text" 
											class="form-control live"
											name="name"
											required="require"
											data-class=".title"
											/>
										</div>
									</div>

									<div class="form-group form-group-lg">
										<label class="col-sm-2 control-label">Description</label>
										<div class="col-sm-9">
											<input
												type="text"
												name="desc"
												class="form-control live"
												data-class=".desc"
											/>
										</div>
									</div>
					<!-- end item Description -->
					<!-- start item price -->
									<div class="form-group form-group-lg">
										<label class="col-sm-2 control-label">Price</label>
										<div class="col-sm-9">
											<input
												type="text"
												name="price"
												class="form-control live"
												required="require"
												data-class=".tag-price"

											/>
										</div>
									</div>
					<!-- end item price -->

					<!-- start item Country -->
									<div class="form-group form-group-lg">
										<label class="col-sm-2 control-label">Country</label>
										<div class="col-sm-9">
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
										<div class="col-sm-9">
											<select class="form-control" name="status">
												<option value='0'>...</option>
												<option value='1'>New</option>
												<option value='2'>Used</option>
												<option value='3'>Like New</option>
												<option value='4'>Old</option>
											</select>
										</div>
									</div>
					<!-- end item status -->
					<!-- start item category -->
									<div class="form-group form-group-lg">
										<label class="col-sm-2 control-label">Category</label>
										<div class="col-sm-9">
											<select class="form-control" name="cat">
												<option value='0'>...</option>
											<?php
												$allCats = getAllFrom('*','categories',null,null,'catid');	
												 foreach($allCats as $cat){
													echo "<option value = '" . $cat['catid'] . "'>" . $cat['name'] ."</option>";
												}
											?>
											</select>
										</div>
									</div>
					<!-- end item category -->
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-2 col-sm-10">
											<input class="btn btn-primary btn-lg" type="submit" value="Add Item">
										</div>
									</div>
								</form>
								<?php
									if(!empty($fromErrors)){ 
										echo '<div class="error">'; 
											foreach($fromErrors as $error){
												echo "<div class='alert alert-danger'>". $error . "</div>";
											}
										
									echo "</div>";
								} ?>	
							</div>	
							<div class="col-md-4 col-sm-12">
								<div class="thumbnail item-box">
									<img class="img-responsive" src='layout/imgs/avatar.png'>
									<span class="price">$<span class="tag-price">0</span></span>
									<div class="caption">
										<h3 class="title">Title</h3>
										<p class="desc">Description</p>
									</div>
								</div>		
							</div>
						</div>	
					</div>
				</div>	
			</div>	
		</div>

<?php	
	} else{
		header("location:login.php");
	}
	include $temp . 'footer.php';
	ob_end_flush();
?>