<?php
require_once __DIR__. "/lib/functions.php";
require_once __DIR__.'/lib/db_config.php';

$mentees = mentees();
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
        <a href="mentors.php" class="px-3 mx-2 text-white hover:text-gray-200">Mentors</a>
        <a href="mentees.php" class="px-2 mx-2 text-white hover:text-gray-200">Mentees</a>
    </div>
    <div class="w-1/3 h-full px-6 py-10">
        <h3 class="text-2xl py-2">Mentees</h3>
        <div>
            <ul>
            <?php foreach($mentees as $mentee) { ?>
                <li class="ml-2"><a href="mentee.php?menteeid=<?=$mentee['id']?>" class="py-2 px-2 block"><?= ucwords($mentee['fullname']) ?></a> </li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
