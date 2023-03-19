<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $json = file_get_contents('users.json');
    $users = json_decode($json, true);

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['username'] = $username;
            header('Location: wall.php');
            exit;
        }
    }

    $error = 'Credenziali non valide';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Accedi</title>
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
    
        input[type=text], input[type=password] {
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


                
                
        <?php if (isset($error)): ?>
            <p><?php echo $error ?></p>
        <?php endif ?>
        
        <form method="post">
            <label for="username">Nome utente</label><br>
            <input type="text" id="username" name="username" />
<p><br></p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" />
<p><br></p>
            <input type="submit" value="Accedi">
        </form>
                 <p><br></p>
		<p>Copyright &copy; Vivacity Design Web Agency, 2023</p>
                
  </center>
    </main>
</body>
</html>
