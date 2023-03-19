<?php
session_start();

// verificare se l'utente ha effettuato l'accesso
if (!isset($_SESSION['username'])) {
  // reindirizzare l'utente alla pagina di login
  header("Location: login.php");
  exit();
}

// ottenere i dati dal form
$title = $_POST['title'];
$username = $_SESSION['username'];

// creare un nuovo oggetto per la domanda
$question = [
  'id' => uniqid(),
  'title' => $title,
  'username' => $username,
  'date' => date('Y-m-d H:i:s'),
  'answers' => []
];

// salvare la domanda nel file questions.json
$questions = json_decode(file_get_contents('questions.json'), true);
$questions[] = $question;
file_put_contents('questions.json', json_encode($questions));

// reindirizzare l'utente alla pagina wall.php
header("Location: wall.php");
exit();
