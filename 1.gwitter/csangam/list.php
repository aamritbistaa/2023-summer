<?php
session_start();

include 'db_connect.php';

if (!isset($_SESSION['username'])) {
  header("Location: loginpage.php"); 
  exit();
}

$userId = $_SESSION['user_id'];

$query = "SELECT * FROM user WHERE user_id != :userId AND user_id NOT IN (SELECT user_id FROM follower WHERE follower_id = :userId)";
$statement = $db->prepare($query);
$statement->bindValue(':userId', $userId);
$result = $statement->execute();

if (isset($_POST['logout'])) {
  session_unset(); 
  session_destroy(); 
  header("Location: loginpage.php"); 
  exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Twitter Clone - List</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous">
</head>
<body>
<!-- sidebar starts -->
<div class="sidebar">
  <i class="fab fa-twitter"></i>
  <div class="sidebarOption" onclick="redirectToHomepage()">
    <span class="material-icons"> home </span>
    <h2>Home</h2>
  </div>

  <div class="sidebarOption" onclick="redirectToProfile()">
    <span class="material-icons"> perm_identity </span>
    <h2>Profile</h2>
  </div>

  <div class="sidebarOption active">
    <span class="material-icons"> list_alt </span>
    <h2>Follow</h2>
  </div>

  <div>
    <form method="post">
      <button type="submit" class="tweetBox__logoutButton" name="logout">Logout</button>
    </form>
  </div>
</div>
<!-- sidebar ends -->

<!-- feed starts -->
<div class="feed">
  <div class="feed__header">
    <h2>Welcome <?php echo $_SESSION['username']; ?></h2>
  </div>

  <div class="userList">

    <?php while ($row = $result->fetchArray()) {
    $user=$row['user_name']; 
    $userid=$row['user_id'];
    echo '
      <div class="userList__item">
        <h3>'.$user.'</h3>
        <form action="follow.php" method="post">
          <button type="submit" class="userList__followButton" name="followid" action="follow.php" value='.$userid.'>Follow</button>
        </form>
      </div>
    '; } ?>
  </div>
</div>
<!-- feed ends -->

<!-- widgets starts -->
<div class="widgets">
  <div class="widgets__input">
    <span class="material-icons widgets__searchIcon"> search </span>
    <input type="text" placeholder="Search Gwitter">
  </div>

  <div class="widgets__widgetContainer">
    <h2>Hackers Takeover NASA</h2>
    <div class="post">
      <div class="post__avatar">
        <img src="path_to_image" alt="">
      </div>

      <div class="post__body">
        <div class="post__header">
          <div class="post__headerText">
            <h3>
              CyberHacker
              <span class="post__headerSpecial"
                ><span class="material-icons post__badge"> verified </span
                >@cyberhacker</span
              >
            </h3>
          </div>
          <div class="post__headerDescription">
            <p>Hackers have successfully taken over NASA's systems! $10 million bounty asked.</p>
          </div>
        </div>
        <img src="https://www.thetechoutlook.com/wp-content/uploads/2022/05/Untitled-design-10-15.jpg" alt="Hackers Takeover NASA">
      </div>
    </div>
  </div>
</div>
<!-- widgets ends -->
<script>
  function redirectToHomepage() {
    window.location.href = "homepage.php";
  }

  function redirectToProfile() {
    window.location.href = "profile.php";
  }
</script>
</body>
</html>
