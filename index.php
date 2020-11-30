<?php
require_once __DIR__. '/lib/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mento - Mentee App</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="css/app.css" rel="stylesheet">
</head>
<body class="antialiased">
<div class="bg-gray-50 min-h-screen text-sm flex flex-col items-center justify-start">
    <div class="navbar w-full bg-gray-600 h-12 flex flex-row items-center justify-end">
        <a href="index.php" class="px-3 mx-2 text-white hover:text-gray-200">Home</a>
        <a href="mentor.php" class="px-3 mx-2 text-white hover:text-gray-200">Mentors</a>
        <a href="mentees.php" class="px-2 mx-2 text-white hover:text-gray-200">Mentees</a>
    </div>
    <div class="flex flex-row items-center justify-center w-full h-40">
        <span class="px-4">Register as a</span>
        <a href="register.php?type=<?=REG_MENTOR?>" class="button mx-2 hover:bg-blue-600">Mentor</a>
        <a href="register.php?type=<?=REG_MENTEE?>" class="button mx-2 hover:bg-blue-600">Mentee</a>

    </div>
</div>
</body>
</html>