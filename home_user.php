<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: login.php");
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body>

    <h1 class="text-4xl text-center mt-20  text-red-500 font-bold mb-4">User panel</h1>
    <h1 class="text-xl  text-center mb-2">Welcome! <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"];  ?></h1>
    <h2 class="text-lg  text-center mb-2">Role: <?php echo $_SESSION["role"];  ?></h2>
<div class="text-center mt-5 ">
    <a class="bg-blue-500  hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block" href="logout.php">
        Logout
    </a></div>

</body>
</html>
