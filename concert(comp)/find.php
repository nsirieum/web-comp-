<?php

    $allowed_files = ['login.php', 'showing.php', 'concert_detail.php'];

    if (isset($file)) {
        if (in_array($pages, $allowed_files)) {
            include($pages);
        } else {
            echo htmlspecialchars("You cannot view this file!",ENT_QUOTES, 'UTF-8');
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>hello world </h1>
</body>
</html>