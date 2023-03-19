<?php
// Load user data from JSON file
$users = json_decode(file_get_contents('users.json'), true);

// Find the user with the matching username
$username = $_GET['username'];
$user = null;
$userIndex = null;
foreach ($users as $index => $u) {
    if ($u['username'] === $username) {
        $user = $u;
        $userIndex = $index;
        
        $gender = $user['gender'];

      // Determine which image to display based on the value of the 'gender' property.
      if ($gender === 'donna') {
        $image_src = 'donna.png';
      } else if ($gender === 'uomo') {
        $image_src = 'uomo.png';
      }
        
        break;
    }
}

// Redirect to homepage if user is not found
if (!$user) {
    header('Location: index.php');
    exit;
}

// Check if user is logged in and is the profile owner
$is_owner = false;
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
    $is_owner = true;
}

// Handle form submission
if ($is_owner && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save new about text
    $about = $_POST['about'];
    $user['about'] = $about;

    // Check if a new userpic was uploaded
    if ($_FILES['userpic']['size'] > 0) {
        // Generate a unique filename for the uploaded file
        $filename = uniqid() . '-' . $_FILES['userpic']['name'];
        // Move the uploaded file to the userpics directory
        move_uploaded_file($_FILES['userpic']['tmp_name'], 'userpics/' . $filename);
        // Update the userpic field with the new filename
        $user['userpic'] = $filename;
    }

// Update the user data in the users array
    $users[$userIndex] = $user;

    // Save the updated user data back to the JSON file
    file_put_contents('users.json', json_encode($users));
}

// Store the userpic filename in a variable
$userpic = $user['userpic'];

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user['username']; ?>'s Profile</title>
    <style>
        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            width: 350px;
            margin: 20px auto;
            padding: 20px;
        }

        .card h2 {
            color: #d5274d;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card img {
            display: block;
            margin: 0 auto 10px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border:1px solid red;
        }

        .card p {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .card button {
            background-color: red;
            border: none;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #bf1745;
        }
        
        .card textarea, .card textarea:focus {
width: 100%;
font-size: 1.1rem;
padding: 4px;
margin-top: 10px;
resize: none;
        border: 2px solid transparent;

         outline: none !important;
        }
        
       
     
     label.att-each {
            border: 1px solid #dedede;
            padding: 2px;
            display: inline-block !important;
            margin-right: 18px !important;
            width: 48px;
            height: 48px;
            background: url("addimage.png") no-repeat;
            text-indent: -9999px;
        }
        
        *, body{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
    }
    
  nav {
  background-color: red;
  height: 60px; 
  display: flex; 
  align-items: center; 
  justify-content: space-between;
  padding: 10px 15px;
  }
  
  .links {
  display: flex;
  justify-content: space-between;
width: 200px;
font-size: 1.2rem;
color: white;
text-decoration: none;

}
nav img {
border-radius: 50%;
border: 1px solid transparent;
box-shadow: 0px 4px 10px black ;
}
  
nav img:hover {
border: 1px solid black;
}  
  
  
  nav > form {
  width: 20%;
  display: flex; 
  align-items: center;
  justify-content: space-between;
  }
  
  nav > form > input {
  border: 1px solid black; 
  border-radius: 10px;
  background-color: white; 
  height: 30px; 
  padding: 5px 10px;
  }
  
  nav > form > button {
  background-color: white;
  border: 1px solid black;
  border-radius: 10px;
  height: 30px; padding: 4px 10px;
  cursor: pointer;
    }
  
  nav > form > button:hover {
  background-color: red;
    color: white;
  }
  
  .logo {
  margin-top: 15px;
  margin-bottom: 15px;
  }
    </style>
</head>
<body>
<nav>
<div class="links">
  <a href="wall.php"><img src="wall.png" title="Tutte le domande"></a>
  <a href="unanswered.php"><img src="filter.png" title="Domande senza risposta"></a>
<!--   <a href="profile.php?username=<?php echo $_SESSION['username']; ?>"><?php echo $_SESSION['username']; ?></a> -->
<a href="profile.php?username=<?php echo $_SESSION['username']; ?>"><img src="<?php echo $image_src; ?>" title="Il mio profilo"></a>
</div>
  <form style="" action="results.php">
    <input type="text" placeholder="Search" name="keywords" />
    <button type="submit">Search</button>
  </form>
</nav>
<center>
<img class="logo" src="qanda_logo.png" height=70>
<p></p>

   <div class="card">
    <h2><?php echo $user['username']; ?></h2>
    
    <?php if ($userpic) { ?>
        <img src="userpics/<?php echo $userpic; ?>" alt="User picture">
    <?php } else { ?>
        <div style="background-color: #f9a7b0; width: 150px; height: 150px; border-radius: 50%; margin: auto;"></div>
    <?php } ?>
    <?php if ($is_owner) { ?>
        <form method="post" enctype="multipart/form-data"><br>
<label for="userpic" style="margin:25px;width:200px;height:50px;padding:10px;background:red;cursor:pointer;border-radius:10px;color:white;">Change Picture</label><br>
<input type="file" name="userpic" id="userpic" style="display:none;"><p><br></p>

<p><br></p>

            <label for="about">About:</label><br>
            <textarea name="about" id="about" cols="32" rows="6" readonly><?php echo $user['about']; ?></textarea><br>
            <?php if ($is_owner) { ?>
                <button type="button" id="edit_about_button">Edit about</button>
                <button type="submit" id="save_button" style="display:none;">Save</button>
            <?php } ?>
        </form>
    <?php } else { ?>
        <p><?php echo $user['about']; ?></p>
    <?php } ?>
</div>

<script>
    var aboutTextarea = document.getElementById('about');
    var editAboutButton = document.getElementById('edit_about_button');
    var saveButton = document.getElementById('save_button');

    editAboutButton.addEventListener('click', function() {
        aboutTextarea.removeAttribute('readonly');
        aboutTextarea.style.border = '2px solid grey';
        editAboutButton.style.display = 'none';
        saveButton.style.display = 'block';
    });
</script>





<p><a href="logout.php">Logout</a></p>

</body>
</html>
