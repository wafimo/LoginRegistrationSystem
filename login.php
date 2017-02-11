<?php 
	require 'inc/header.php';
	require 'lib/User.php';
	Session::checkLogin();
 ?>
 <?php 

 $user = new User();

 if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']))
 {
 	
 	$login_user = $user->userLogin($_POST);
 	//echo $user->userLogin($_POST);
 	//print_r($xyz = $user->getLoginUser('omix02@gmail.com','202cb962ac59075b964b07152d234b70'));
 }
 ?>



 <body>
	<div class="panel panel-default">
				<?php 
					if (!empty($login_user)) 
					{
						echo $login_user;
					}
				?>
		<div class="panel-heading">
			<h2>User Login </h2>
		</div>

		<div class="panel-body">
			<div style="max-width: 500px; margin:0 auto;">

				<form action="" method="POST">				
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="email" name="email" id="email" class="form-control">	
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="form-control">
					</div>
					<!-- <input type="submit" name="login" value="submit"> -->

					<button type="submit" name="login" class="btn btn-success">Login</button>
				</form>
			</div>
		</div>
	</div>
</body>
<?php include 'inc/footer.php' ?>





	