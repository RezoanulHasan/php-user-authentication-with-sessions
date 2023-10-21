<?php

$firstname = $_POST["firstname"] ?? "";
$lastname = $_POST["lastname"] ?? "";
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? ""; 
$age = $_POST["age"] ?? ""; 

$role = "user";

$errorMessage = "";

if ($email != "" && $password != "") {  
    $fp = fopen("./data/users.txt", "a");
    
    fwrite($fp, "\n{$role}, {$email}, {$password}, {$firstname}, {$lastname}, {$age}");
    fclose($fp);

    // Redirect to login page
    header("Location: login.php");
    exit(); // Make sure to exit after redirecting
}
else {
    $errorMessage = "Please enter your info here";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10 max-w-md p-6 bg-white rounded shadow-lg">
    <h1 class="text-4xl mb-6 text-center font-bold text-gray-800">Create account</h1>

    <form action="signup.php" method="POST">

    <div class="mb-4">
            <label for="firstname" class="block text-gray-700 text-sm font-bold mb-2">First name</label>
            <input type="text" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="firstname" id="firstname" placeholder="Enter firstname" value="<?php echo htmlspecialchars($firstname); ?>">
        </div>

        <div class="mb-4">
            <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Last name</label>
            <input type="text" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="lastname" id="lastname" placeholder="Enter lastname" value="<?php echo htmlspecialchars($lastname); ?>">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email address</label>
            <input type="email" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>">
        </div>

        <div class="mb-4">
            <label for="age" class="block text-gray-700 text-sm font-bold mb-2">Age</label>
            <input type="number" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="age" id="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="password" id="password" placeholder="******">
        </div>

        <p class="text-center text-green-500 mb-4">
            <?php echo $errorMessage; ?>
        </p>

        <div class="text-center">
            <button type="submit" class="btn bg-red-500 hover:bg-black text-white font-bold py-2 px-4 rounded">Sign up</button>
        </div>  
    </form>
</div>

<script>
    // Check if the URL contains a success parameter
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    // If success parameter is present, show an alert
    if (success === 'true') {
        alert('Registration successful! Please login.');
    }
</script>

</body>
</html>


