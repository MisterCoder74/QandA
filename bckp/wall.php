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

?>

<!DOCTYPE html>
<html>
<head>
	<title>Wall</title>
        
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
  
  nav  a {
width: 10%;
font-size: 1.2rem;
color: white;
text-decoration: none;

}

nav  a:hover {
color: black;
text-decoration: underline;

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
box-shadow: 0px 2px 4px black;
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
    box-shadow: 0px 4px 10px grey;
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
  <a href="wall.php">Wall</a>
  <a href="#">Link 3</a>
  <a href="profile.php?username=<?php echo $_SESSION['username']; ?>"><?php echo $_SESSION['username']; ?></a>
  <form style="">
    <input type="text" placeholder="Search" />
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

	
		<?php foreach ($questions as $question): ?>
		<div class="singlequestion">
                <div class="questionmain">
				<a class="questionlink" href="questiondetails.php?id=<?php echo $question['id']; ?>"><?php echo $question['title']; ?></a><br>
                </div>                
				<!-- by <?php echo $question['username']; ?> on <?php echo $question['date']; ?> -->
                                <a class="profilelink" href="profile.php?username=<?php echo $question['username']; ?>"><?php echo $question['username']; ?></a> on <?php echo $question['date']; ?>
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
