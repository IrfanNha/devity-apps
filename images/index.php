<?php
if (strpos($_SERVER['REQUEST_URI'], '/images/') !== false) {
  header("HTTP/1.0 403 Forbidden");
  include("403.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>unindexes</title>

</head>

<body>
</body>

</html>