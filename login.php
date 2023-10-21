<?php
session_start();

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

$errorMessage = "";

$fp = fopen("./data/users.txt", "r");

$roles = array();
$emails = array();
$firstnames = array();
$lastnames = array();
$ages = array();
$passwords = array();

while ($line = fgets($fp)) {
    $values = explode(",", $line);  

    array_push($roles, trim($values[0]));
    array_push($emails, trim($values[1]));
    array_push($passwords, trim($values[2]));
    array_push($firstnames, trim($values[3]));
    array_push($lastnames, trim($values[4]));
    array_push($ages, trim($values[5]));
}

fclose($fp);

$credentialsMatched = false;  // Variable to track if credentials match

for ($i = 0; $i < count($roles); $i++) {
    if ($email == $emails[$i] && $password == $passwords[$i]) {
        $_SESSION["role"] = $roles[$i];
        $_SESSION["email"] = $emails[$i];
        $_SESSION["firstname"] = $firstnames[$i];
        $_SESSION["lastname"] = $lastnames[$i];
        $_SESSION["age"] = $ages[$i];
        header("Location: index.php");
        exit();  // Exit the script after redirect
    }
}

$errorMessage = "Wrong email or password";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    
    <div class="container mt-5 max-w-md bg-white p-10 rounded shadow-lg">
        <h1 class="text-center text-2xl mb-5 font-bold text-gray-800">Login to your account</h1>

        <form action="login.php" method="POST" class="space-y-4">
            <div class="form-group">
                <label for="exampleInputEmail1" class="text-sm font-bold text-gray-700 block">Email address</label>
                <input type="email" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>">
            
            
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1" class="text-sm font-bold text-gray-700 block">Password</label>
                <input type="password" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="password" id="exampleInputPassword1" placeholder="******">
            </div>
            <div class="form-group flex items-center">
                <input type="checkbox" class="form-checkbox" id="exampleCheck1">
                <label class="text-sm text-gray-700 ml-2" for="exampleCheck1">Remember me</label>
            </div>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errorMessage)) : ?>
    <p class="text-red-500">
        <?php echo $errorMessage; ?>
    </p>
<?php endif; ?>
<div class="text-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Login</button></div>
        </form>

        <p class="mt-4 text-center">Don't have an account? <a href="signup.php" class="text-blue-500">Sign up</a></p>
    </div>

</body>
</html>
