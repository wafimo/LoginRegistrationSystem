<?php 
	include 'lib/user.php';
	include 'inc/header.php';
	Session::checkSession();
	$user = new User();
?>
<?php  
	$login_msg = Session::get("loginmsg");
		if (isset($login_msg)) 
		{
			echo $login_msg;
		}


		Session::set("loginmsg",NULL);

?>


	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>User List <span class="pull-right">Welcome!! <strong> 
			<?php  
				$name = Session::get("username");
				if (isset($name)) 
				{
					echo $name;
				}
				else {
					echo "no name found";
				}
			?>
			</strong></span></h2>
		</div>

		<div class="panel-body">
			<table class="table table-striped">
				<tr>
					<th width="20%">Serial</th>
					<th width="20%">Name</th>
					<th width="20%">Username</th>
					<th width="20%">Email Address</th>
					<th width="20%">Action</th>
				</tr>

<?php  
	$user = new User();
	$userdata= $user->getUserData();

	if ($userdata) 
	{
		$i =0;
		foreach ($userdata as $data) 
		{
			$i++;

?>			<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $data['name']; ?></td>
					<td><?php echo $data['username']; ?></td>
					<td><?php echo $data['email']; ?></td>
					<td><a class="btn btn-primary" href="profile.php?id=<?php echo $data['id']; ?>">View</a></td>
				</tr>
				<?php
		}
	} 
	else
	{
		?>
		<tr><td colspan="5"><h2>No User data found...!!</h1></td></tr>
	<?php  	
	}
?>

				
			</table>
		</div>
	</div>	
<?php include 'inc/footer.php' ?>





	