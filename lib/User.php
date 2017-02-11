<?php 

	include_once 'Session.php';
	include_once 'Database.php';

class User
{
public $xyz="abc";
	private $db;	
	public function __construct()
	{
		$this->db = new Database();
	}

	public function userRegistration($data)
	{
		$name 		= $data['name'];
		$username 	= $data['username'];
		$email 		= $data['email'];
		$password 	= $data['password'];

		$chk_email = $this->emailCheck($email);



		if ($name=="" || $username=="" || $email=="" || $password=="") 
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Field must not be empty</div>";
			return $msg;
		}

		if (strlen($username)<3) 
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Username is too short</div>";
			return $msg;
		}
	
		if (preg_match('/[^a-z0-9]_-/i', $username)) 
		{
		$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Username only contain alphanumerical and dashes and underscore</div>";
		return $msg;
		}

		if (filter_var($email, FILTER_VALIDATE_EMAIL)=== false)
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Invalid Email Address!</div>";
			return $msg;
		}


		if ($chk_email == true) {
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> This email is already exist!!</div>";
			return $msg;
		}


		$sql = "INSERT INTO users (name,username,email,password) VALUES (:name,:username,:email,:password) ";
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':name',$name);
		$query -> bindValue(':username',$username);
		$query -> bindValue(':email',$email);
		$query -> bindValue(':password',md5($password));
		$result = $query->execute();


		if ($result) {
			$msg = "<div class='alert alert-success'><strong>Seccess!!</strong>You have been registerd successfully!</div>";
			return $msg;
		}
		else
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong>There has been some problem while registering!!</div>";
			return $msg;
		}
	}



	public function emailCheck($email)
	{	
		$sql = "SELECT email FROM users WHERE email = :email";
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':email',$email);
		$query->execute();
		if ($query->rowCount() > 0) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getLoginUser($email,$password)
	{
		$sql = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':email',$email);
		$query -> bindValue(':password',$password);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result;


	}


	public function userLogin($data)
	{

		$email 		= $data['email'];
		$password 	= md5($data['password']);

		$chk_email = $this->emailCheck($email);


		if ($email == "" || $password == "") 
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Field must not be empty</div>";
			return $msg;
		}


		if (filter_var($email, FILTER_VALIDATE_EMAIL ) === false)
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Invalid Email Address!</div>";
			return $msg;
		}


		// if ($chk_email == false) {
		// 	$msg = "<div class='alert alert-danger'><strong>Error!!</strong> This email is not exist in the system!!</div>";
		// 	return $msg;
		// }

		// here to work

		

		$_result = $this->getLoginUser($email,$password);
		

		if ($_result) 
		{
			Session::init();
			Session::set("login",true);
			Session::set("id",$_result->id);
			Session::set("name",$_result->name);
			Session::set("username", $_result->username);
			Session::set("loginmsg","<div class='alert alert-success'><strong>Success!!</strong> You are Logged In!</div>");
			header("Location: index.php");
			//Session::set();

		}
		else
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Invalid Username or Password!</div>";
			return $msg;
		}
	}

	public function getUserData()
	{
		$sql = "SELECT * FROM users ORDER BY id DESC";
		$query = $this->db->pdo->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		return $result;
	}



	public function getUserById($id)
	{
		$sql = "SELECT * FROM users WHERE id = :id LIMIT 1 ";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':id',$id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result;	
	}



	public function userUpdate($id,$data)
	{
		$name 		= $data['name'];
		$username 	= $data['username'];
		$email 		= $data['email'];


		if ($name=="" || $username=="" || $email=="" ) 
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong> Field must not be empty</div>";
			return $msg;
		}

		$sql = "UPDATE users SET 
		name = :name , 
		username = :username,
		email = :email
		WHERE id = :id";
		
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':name',$name);
		$query -> bindValue(':username',$username);
		$query -> bindValue(':email',$email);
		$query -> bindValue(':id',$id);
		$result = $query->execute();

		if ($result) {
			$msg = "<div class='alert alert-success'><strong>Seccess!!</strong>Updated successfully!</div>";
			return $msg;
		}
		else
		{
			$msg = "<div class='alert alert-danger'><strong>Error!!</strong>There has been some problem while updating!!</div>";
			return $msg;
		}
	}

	
	private function CheckPass($id,$oldPass)
	{
		$password = md5($oldPass);
		$sql = "SELECT password FROM users WHERE $id = :id AND password = :password";
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':id',$id);
		$query -> bindValue(':password',md5($oldPass));
		$query->execute();
		if ($query->rowCount() > 0) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	

	public function updatePassword($id,$data)
	{
		$oldPass = $data['old_password'];
		$newPass = $data['new_password'];

		if ($oldPass == "" || $newPass == "") 
		{
			return $msg = "<div class='alert alert-danger'><strong>Error!! </strong>Field must not be empty!</div>";
		}

		$chkPassword = $this->CheckPass($id,$oldPass);
		if ($chkPassword == false)
		{
			return $msg = "<div class='alert alert-danger'><strong>Error!! </strong>Invalid old password!</div>";
		}

		if (strlen($newPass) < 4)
		{
			return $msg = "<div class='alert alert-danger'><strong>Warning!! </strong>Password is too short!</div>";	
		}

		$password = md5($newPass);
		$sql = "UPDATE users SET 
		password = :password
		WHERE id = :id";
		
		$query = $this->db->pdo->prepare($sql);
		$query -> bindValue(':password',$password);
		$query -> bindValue(':id',$id);
		$result = $query->execute();

		if ($result) {
			return $msg = "<div class='alert alert-success'><strong>Seccess!!</strong>Updated successfully!</div>";
		}
		else
		{
			return $msg = "<div class='alert alert-danger'><strong>Error!!</strong>There has been some problem while updating!!</div>";
		}

	}
}
?>	
