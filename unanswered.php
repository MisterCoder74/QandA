<?php
session_start();

// verificare se l'utente ha effettuato l'accesso
if (!isset($_SESSION['username'])) {
  // reindirizzare l'utente alla pagina di login
  header("Location: login.php");
  exit();
}

// includere il file questions.json
$questions = json_decode(file_get_contents('questions.json'), true);

$unansweredQuestions = array_filter($questions, function($question) {
    return empty($question['answers']);
    });

$users = json_decode(file_get_contents('users.json'), true);

// Find the user with the matching username
$username = $_SESSION['username'];
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



?>

<!DOCTYPE html>
<html>
<head>
	<title>Unanswered Questions</title>
        
 <style>
  
      /* stili per la pagina */
      
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

section {
width: 70%;

padding: 10px;
}

aside {
width: 20%;
margin: 5px auto;

padding: 10px;
}

main {
width: 90%;
display: flex;
align-items: start;
justify-content: space-between;
    box-shadow: 0 0 15px 0px grey;
  clip-path: inset(0px -15px 0px -15px);
padding: 10px;
}
        
       /* stili per la formattazione della domanda */
    .yourquestioncontainer {
    width: 100%;
    margin: 20px auto;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px grey;
    text-align: center;
    } 
    
    .yourquestioncontainer >form > input[type=text] {
    margin: 5px auto;
    width: 60%;
    height: 40px;
    border-radius: 10px;

    text-align: justify;

    }
    
    .yourquestioncontainer >form > input[type=submit] {
    background-color: red;
    color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 8px grey;
    width: 100px;
    padding: 8px 15px;
    cursor: pointer;
    }
   

   /* stili per la formattazione delle risposte esistenti */
.questionslistcontainer {
    width: 100%;
    margin: 20px auto;
    padding: 10px 20px;
box-shadow: 0px 4px 8px grey;
    }  
    
.singlequestion {
    width: 100%;
    margin: 10px auto;
    padding: 10px 4px;
    box-shadow: 0px 2px 4px grey;
    color: black;
    }
    
.questionmain {
font-size: 1.1rem;
color: black;
}
    
.questionlink {
font-size: 1.2rem;
color: grey;
text-decoration: none;

}

.questionlink:hover {
color: red;
text-decoration: underline;

}

/* stile per la lista domande top */
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

li {
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  background-color: #f8f8f8;
  text-align: left;
}

li a {
  text-decoration: none;
  color: #333;
  font-weight: bold;
}

li a:hover {
  text-decoration: underline;
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
<main>
<aside>
<?php
// Read the JSON file
$data = file_get_contents('questions.json');
$questions = json_decode($data, true);

// Sort the questions by the number of answers in descending order
usort($questions, function($a, $b) {
    return count($b['answers']) - count($a['answers']);
});

// Display the first five questions in a list with links to their details page
echo '<div>';
echo '<h2>Top 5 Questions</h2>';
echo '<ul>';
foreach (array_slice($questions, 0, 5) as $question) {
    $id = $question['id'];
    $title = $question['title'];
    $num_answers = count($question['answers']);
    echo '<li><a href="questiondetails.php?id=' . $id . '">' . $title . '</a><br> (' . $num_answers . ' answers)</li>';
}
echo '</ul>';
echo '</div>';
?>

</aside>
<section>
        <div class="yourquestioncontainer">
	<form action="save_question.php" method="post">
		<label for="title">Ask your Question:</label><br>
		<input type="text" name="title" id="title" /><br>


		<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>" />

		<input type="submit" value="INVIA">
	</form>
        </div>

        <div class="questionslistcontainer">
	<h2>Questions List</h2>

	
		<?php foreach ($unansweredQuestions as $question): 
                    $numAnswers = count($question['answers']);
                ?>
		<div class="singlequestion">
                <div class="questionmain">
				<a class="questionlink" href="questiondetails.php?id=<?php echo $question['id']; ?>"><h3><?php echo $question['title']; ?></h3></a><br>
                </div>                
				<!-- by <?php echo $question['username']; ?> on <?php echo $question['date']; ?> -->
                                Asked by: <a class="profilelink" href="profile.php?username=<?php echo $question['username']; ?>"><?php echo $question['username']; ?></a> 
                                <br>On: <?php echo $question['date']; ?>
                                <br>Answers: <?php echo $numAnswers; ?>
		</div>	
		<?php endforeach; ?>
	
        </div>
</section>
<aside>
<?php
// Read questions from JSON file
$json = file_get_contents('questions.json');
$questions = json_decode($json, true);

// Sort questions by date in descending order
usort($questions, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Display the 5 most recent questions in an unordered list
echo '<div>';
echo '<h2>Recent Questions</h2>';

echo '<ul>';
foreach (array_slice($questions, 0, 5) as $question) {
    echo '<li><a href="questiondetails.php?id='.$question['id'].'">'.$question['title'].'</a></li>';
}
echo '</ul>';
?>

</aside>
</main>
</center>
</body>
</html>
