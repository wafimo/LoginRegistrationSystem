<?php 
	include 'lib/user.php';
	include 'inc/header.php';
	Session::checkSession();
 ?>
	
<?php  
	if (isset($_GET['id'])) 
	{
		$userid = (int)$_GET['id'];
		$ssId = Session::get("id");
		if ($userid != $ssId) 
		{
			header("Location:index.php");
		}
	}
	$user = new User();
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatePass']))
 	{
 		$update_pass = $user->updatePassword($userid, $_POST);
 	}
?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Change Password <span class="pull-right"><a class="btn btn-primary" href="profile.php?id=<?php echo $userid;?>">Back</a></span></h2>
		</div>

		<div class="panel-body">
			<div style="max-width: 500px; margin:0 auto;">
<?php  
	if (isset($update_pass)) 
	{
		echo $update_pass;
	}
?>

			<form action="" method="post">				
					<div class="form-group">
						<label for="old_password">Old Password</label>
						<input type="password" name="old_password" id="old_password" class="form-control">
					</div>
					<div class="form-group">
						<label for="new_password">New Password</label>
						<input type="password" name="new_password" id="new_password" class="form-control">
					</div>
					

					<!-- <div class="form-group">
						<label for="email">Confirm New Password</label>
						<input type="email" name="email" id="email" class="form-control">
					</div> -->
					
					<button type="submit" name="updatePass" class="btn btn-success">Update</button>					
				</form>
			</div>
		</div>
	</div>
<?php include 'inc/footer.php' ?>





	