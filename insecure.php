<?php
//StAuth10065: I Bobby Filippopoulos, 000338236 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else.
error_reporting(E_ALL & ~E_NOTICE);

include 'credentials.php';

session_save_path("/home/faculty/tooltime/tooltime/test_dir/mycookies");
session_start();
$_SESSION['value']++;
if (empty($_SESSION['hiddenToken'])) {
  $_SESSION['hiddenToken'] = md5(uniqid(mt_rand(), true));
}


?>
<?php

if ($_REQUEST['act'] == "file")
{
  if($_REQUEST['tk'] == $_SESSION['hiddenToken'])
  {
    file_put_contents("A.txt", "1", FILE_APPEND);
  }
  echo file_get_contents("A.txt");
  exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if($_POST['hiddenToken'] == $_SESSION['hiddenToken'])
  {
    try {
      $conn = new PDO($dsn, $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //set variables from form post
      $name =  htmlentities($_POST['name']);
      $email =  htmlentities($_POST['email']);
      $comment =  htmlentities($_POST['comment']);

      // prepare and bind
      $stmt = $conn->prepare("INSERT INTO COMMENTS (NAME, EMAIL, COMMENT) VALUES (:name, :email, :comment)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':comment', $comment);
      /*
      $InsertString = "INSERT INTO COMMENTS VALUES (NULL,"
                      . "\"" . $_POST['name'] . "\","
                      . "\"" . $_POST['email'] . "\","
                      . "\"" . $_POST['comment'] . "\");";
      */



      //$pdo = new PDO($dsn, $username, $password);
      $stmt->execute();
      //$stmt->execute();
    }
    catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
  }
}

?>

<html>
<head>
<title>Insecure PHP Web App</title>
</head>
<body>

<h1>Insecure Web App</h1>

<form action="insecure.php" method="post">
Name: <input type="text" name="name" value=""<br>
E-mail: <input type="text" name="email" value=""><br>
Comment: <input type="text" name="comment" value""><br>
<input type="hidden" name="hiddenToken" value="<?=$_SESSION['hiddenToken']?>">
<input type="submit">
</form>

<br>

<a id="add" href="#">Add 1</a> <br> <br>

<strong>File Data:</strong> <br>
<div id="filedata">
<?php

echo file_get_contents("A.txt");

?>
</div>

<br> <br>

<strong>Session Data:</strong> <br>
<?php
print_r($_SESSION);
?>

<br> <br> <br>

<strong>Comment Database Data:</strong> <br> <br>
<?php

$pdo = new PDO($dsn, $username, $password);

$stmt = $pdo->query('SELECT * FROM COMMENTS');
while ($row = $stmt->fetch())
{
	?>
	<strong>Name:</strong> <?php echo $row['NAME']; ?> <br>
	<strong>Email:</strong> <?php echo $row['EMAIL']; ?> <br>
	<strong>Comment:</strong> <?php echo $row['COMMENT']; ?> <br>
    <hr><br><br>
    <?
}

?>

<br> <br>

<strong>Form Data:</strong> <br>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	?>
	<strong>Name:</strong> <?php echo $_POST['name'] ?> <br>
	<strong>Email:</strong> <?php echo $_POST['email'] ?> <br>
	<strong>Comment:</strong> <?php echo $_POST['comment'] ?> <br>
    <?php
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>


$("#add").click(function(event){
  event.preventDefault();
  $("#filedata").load("insecure.php?act=file&tk=<?=$_SESSION['hiddenToken']?>")
});

</script>
</body>
</html>
