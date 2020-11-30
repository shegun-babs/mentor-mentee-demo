<?php
require_once __DIR__. "/lib/functions.php";
require_once __DIR__.'/lib/db_config.php';

$mentee_id = $_GET['menteeid'];

$m = mentor_mentees($mentee_id);
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
    <div class="w-2/3 h-full px-6 py-10">
        <?php if (count($m)): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Mentee Profile
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Personal details and mentors.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Full name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 capitalize">
                            <?= $m[0]['fullname'] ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Email Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 lowercase">
                            <?= $m[0]['email'] ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            About
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $m[0]['about'] ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Attachments
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                <?php foreach($m as $mentor): ?>
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <span class="ml-2 flex-1 w-0 truncate capitalize">
                                            <?= $mentor['mentor_fullname'] ?>
                                        </span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="mentor.php?mentorid=<?=$mentor['mentor_id']?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            View
                                        </a>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        <?php
        endif;
        if (empty($m)):
            ?>

            No record found. Please try again

        <?php endif; ?>
    </div>
</div>
