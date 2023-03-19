<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id = substr(preg_replace("/[^a-zA-Z0-9]+/", "", uniqid()), 0, 10);
    $gender = $_POST['gender'];
    $city = $_POST['city'];

    // Check if the username or email already exist
    $json = file_get_contents('users.json');
    $data = json_decode($json, true);
    foreach ($data as $user) {
        if ($user['username'] == $username) {
            die('Username already exists');
        }
        if ($user['email'] == $email) {
            die('Email already exists');
        }
    }

    // Create new user
    $user = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'gender'  => $gender,
        'about' => 'Aggiungi due righe su di te',
        'city' => $city,
        'userpic' => '',
    ];

    // Add new user to the users.json file
    $data[] = $user;
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('users.json', $json);

    header('Location: login.php');
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Registrati</title>
	<link rel="stylesheet" href="style.css">
        
        <style>
    .logo {
  margin-top: 60px;
  margin-bottom: 40px;
  }
  
   *, body{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
    }
    
        input[type=text], input[type=password], input[type=email] {
  border: 1px solid black; 
  border-radius: 10px;
  background-color: white; 
  height: 30px; 
  padding: 5px 10px;
  }

    input[type=submit] {
    background-color: red;
    color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 8px grey;
    width: 100px;
    padding: 8px 15px;
    cursor: pointer;
    margin-bottom: 25px;
    }
        
        </style>
        
</head>
<body>

	<main>
        
        <center>
    <img class="logo" src="qanda_logo.png" height=100 />


		<h2>Il social di domande e risposte</h2>

        <p><br></p>
        
		<form method="post">
			<label for="username">Nome utente</label><br>
			<input type="text" id="username" name="username">
<p><br></p>
			<label for="email">Indirizzo email</label><br>
			<input type="email" id="email" name="email">
<p><br></p>
			<label for="password">Password</label><br>
			<input type="password" id="password" name="password">
<p><br></p>

                        <label for="gender">Sesso:</label><br>
                        <select name="gender" id="gender">
                                <option value="uomo">Uomo</option>
                                <option value="donna">Donna</option>
                        </select>
<p><br></p>   
                        <label for="city">Città</label><br>
			<input type="text" id="city" name="city">
<p><br></p>
			<input type="submit" value="Registrati">
		</form>
                        <p><br></p>
		<p>Copyright &copy; Vivacity Design Web Agency, 2023</p>
                
  </center>
	</main>
</body>
</html>
