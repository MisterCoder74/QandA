<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect user to login page
  header("Location: login.php");
  exit();
}

// Get question ID from URL
if (!isset($_GET['id'])) {
  // Redirect user to questions page
  header("Location: questions.php");
  exit();
}

$questionId = $_GET['id'];

// Load questions from JSON file
$questions = json_decode(file_get_contents('questions.json'), true);

// Find the question with the specified ID
$question = null;
foreach ($questions as $q) {
  if ($q['id'] === $questionId) {
    $question = $q;
    $numAnswers = count($question['answers']);
    break;
  }
}

if (!$question) {
  // Question not found, redirect user to questions page
  header("Location: questions.php");
  exit();
}

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

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $answer = [
    'author' => $_SESSION['username'],
    'text' => $_POST['text'],
    'date' => date('Y-m-d H:i:s'),
  ];

  $question['answers'][] = $answer;

  // Update question in the array of questions
  foreach ($questions as &$q) {
    if ($q['id'] === $questionId) {
      $q = $question;
      break;
    }
  }

  // Save questions back to JSON file
  file_put_contents('questions.json', json_encode($questions));

  // Redirect user to this question's page
  header("Location: questiondetails.php?id=$questionId");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Question Details</title>
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
  height: 30px; 
  padding: 4px 10px;
  cursor: pointer;
  }
  
  .logo {
  margin-top: 15px;
  margin-bottom: 15px;
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
  
    /* stili per la finestra modale */
    .modal {
      display: none; /* nascondere la finestra modale per impostazione predefinita */
      position: fixed; /* posizionamento fisso */
      z-index: 1; /* posizionamento in primo piano */
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4); /* sfondo nero trasparente */
    }

    /* stili per il contenuto della finestra modale */
    .modal-content {
      background-color: #fefefe;
      margin: 10% auto;
      padding: 20px;
      border: 1px solid red;
      width: 50%;
      max-height: 80%;
      overflow-y: auto;
    }

    /* stili per il pulsante di chiusura della finestra modale */
    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }
    
       /* stili per la formattazione della domanda */
    .questioncontainer {
    width: 60%;
    margin: 20px auto;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px grey;
    } 
    
    .goback {
    font-size: .9rem;
    text-align: right;
    }
    
    .walllink {
    font-size: .9rem;
color: grey;
text-decoration: none;
    }
    
    .walllink:hover {
    color: red;
text-decoration: underline;
    }
    
   /* stili per la formattazione della nuova risposta */
    .youranswercontainer {
    width: 60%;
    margin: 20px auto;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px grey;
    } 
    
    .youranswercontainer form {
margin: 10px auto;
width: 90%;
    text-align: center;

    }
    
    .youranswercontainer textarea {
margin: 10px auto;
width: 90%;
    text-align: justify;
    resize: none;

    }

   /* stili per la formattazione delle risposte esistenti */
.answerslistcontainer {
    width: 60%;
    margin: 20px auto;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px grey;
    }  
    
.singleanswer {
    width: 100%;
    margin: 10px auto;
    padding: 10px 4px;
    box-shadow: 0px 2px 4px grey;
    color: black;
    }
    
.answermain {
font-size: 1.1rem;
color: black;
}
    
.answerlink {
font-size: .9rem;
color: grey;
text-decoration: none;

}

.answerlink:hover {
color: red;
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
</center><p>

<div class="questioncontainer">
  <h1 class="questiontitle"><?php echo $question['title']; ?></h1>
  <p>Asked by <?php echo $question['username']; ?> on <?php echo $question['date']; ?></p>
  <p>N° of answers: <?php echo $numAnswers; ?></p>
    <p class="goback"><a class="walllink" href="wall.php">Return to questions list</a></p>

  
</div>  

<div class="youranswercontainer">

  <form method="post">
  <h2>Answer the question:</h2>
    <textarea rows=10 name="text" id="text"></textarea><br>
    <input type="submit" value="Submit"/>
  </form>
</div>

<div class="answerslistcontainer">
  <h2>Answers List</h2>
  
    <?php foreach ($question['answers'] as $answer): ?>
    

    <div class="singleanswer">
    <div class="answermain">
      <?php
        $text = $answer['text'];
        echo $text . "<br><br>by: " . $answer['author'] . " (" . $answer['date'] . ")";
      ?>
     </div>
      <!-- <a class = "answerlink" href="#" onclick="openModal('<?php echo $answer['text']; ?>', '<?php echo $answer['author']; ?>', '<?php echo $answer['date']; ?>')">Read More</a> -->
    </div>
     <?php endforeach; ?>
</div>

<script>
  function openModal(text, author, date, time) {
    // get the modal element
    const modal = document.getElementById('answerModal');
    
    // set the text and author in the modal
    modal.querySelector('.modal-text').textContent = text;
    modal.querySelector('.modal-author').textContent = author;
    modal.querySelector('.modal-date').textContent = date;
//    modal.querySelector('.modal-time').textContent = time;
    
    // show the modal
    modal.style.display = "block";
  }
  
  function closeModal() {
    // get the modal element
    const modal = document.getElementById('answerModal');
    
    // hide the modal
    modal.style.display = "none";
  }
</script>

<div id="answerModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Full Answer</h2>
    <p><strong>Author:</strong> <span class="modal-author"></span></p>
    <p><strong>Date:</strong> <span class="modal-date"></span></p>
    <br>
    <p><span class="modal-text"></span></p>
  </div>
</div>

  
</body>
</html>
