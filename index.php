<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note</title>
    <link rel="stylesheet" href="./files/style.css" type="text/css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
    <script src="./files/autoresize.jquery.js"></script>
    <script type="text/javascript">
      $(function() {
        $('#resizable').autoResize();
      });
    </script>
  </head>
  <body>
    <?php
      $host = "localhost";
      $username = "root";
      $password = "";
      $database = "note";
      $connection = mysqli_connect($host, $username, $password, $database);
      if (mysqli_connect_errno($connection)) {
        echo "Connection to MySQL database failed: ".mysqli_connect_error();
      } else {
    ?>
    <div id="main">
      <h1><a href="<?php echo $_SERVER["PHP_SELF"] ?>">Note</a></h1>
      <div class="left-container">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
          <h2>Topic:</h2>
          <input type="text" name="topic" required>
          <h2>Note:</h2>
          <textarea id="resizable" style="height: 200px;" name="note" required></textarea>
          <h2>Tag:</h2>
          <datalist id="tags">
            <?php
              $sqlTask = mysqli_query($connection, "SELECT DISTINCT `tag` FROM `notes`");
              while ($result = mysqli_fetch_array($sqlTask)) {
                echo "\t    <option value=\"" . $result[tag] . "\">" . $result[tag] . "</option>\n";
              }
            ?>
          </datalist>
          <input type="text" name="tag" list="tags" required>
          <br>
          <input type="image" src="./files/add.png" alt="Add">
        </form>
        <?php
          if (isset($_REQUEST['note']) && (isset($_REQUEST['topic']))) {
            if ($_REQUEST['note'] !== '' && $_REQUEST['topic'] !== '') {
              $_topic = HTMLSpecialChars($_POST[topic]);
              $_note = HTMLSpecialChars($_POST[note]);
              $_tag = HTMLSpecialChars($_POST[tag]);
              $sqlTask = "INSERT INTO `notes` (`topic`, `note`, `tag`, `date`) VALUES ('$_topic', '$_note', '$_tag', now())";
              if (!mysqli_query($connection, $sqlTask)) {
                die('Error during saving note: ' . mysqli_error($connection));
              } else {
              echo "<span>The note was written in</span>";
              }
            }
          }
        ?>
      </div>
      <div class="right-container">
        <div class="form">
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
          <h2>Notes: </h2>
            <input id="search" type="search" placeholder=" Search..." name="search">
            <input type="submit" style="visibility: hidden;" /> 
          </form>
        </div>
        <?php
            if (isset($_GET['tag'])) {
              if ($_GET['tag'] !== '') { 
                $sqlTask = mysqli_query($connection, "SELECT * FROM `notes` WHERE `tag` = '$_GET[tag]' ORDER BY `date` DESC");
                while ($result = mysqli_fetch_array($sqlTask)) {
                  echo "      <div class=\"module green\">\n";
                  echo "\t<h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
                  echo nl2br("\t" . $result['note'])."\n";
                  echo "      </div>\n";
                }
              }
            } else {
              if (isset($_REQUEST['search'])) {
                if ($_REQUEST['search'] !== '') { 
                  $sqlTask = mysqli_query($connection, "SELECT * FROM `notes` WHERE (`topic` REGEXP '$_REQUEST[search]' OR `note` REGEXP '$_REQUEST[search]' OR `tag` REGEXP '$_REQUEST[search]' OR `date` REGEXP '$_REQUEST[search]') ORDER BY `date`");
                  while ($result = mysqli_fetch_array($sqlTask)) {
                    echo "\t<div class=\"module green\">\n";
                    echo "\t  <h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
                    echo nl2br("\t  " . $result['note'])."\n";
                    echo "\t</div>\n";
                  }
                }
              } else {
                $sqlTask = mysqli_query($connection, "SELECT * FROM `notes` ORDER BY `date` DESC");
                while ($result = mysqli_fetch_array($sqlTask)) {
                  echo "\t<div class=\"module green\">\n";
                  echo "\t  <h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
                  echo nl2br("\t  " . $result['note'])."\n";
                  echo "\t</div>\n";
                }
              }
            }
            ?>
      </div>
    </div>
    <?php
        mysqli_close($connection);
      }
    ?>
  </body>
</html>
