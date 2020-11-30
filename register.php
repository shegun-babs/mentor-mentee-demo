<?php
require_once __DIR__. "/lib/functions.php";
require_once __DIR__.'/lib/db_config.php';
$regType = $_GET['type'] ?? null;
$validator = null;
//process form submit
if ( array_key_exists('submitted', $_POST) && !is_null($regType))
{
    require_once __DIR__.'/lib/Validator.php';
    $rules = $regType == REG_MENTEE ? menteeRules() : mentorRules();
    $validator = new Validator($rules);

    if ($validator->passed() && $regType == REG_MENTOR)
    {
        $data = $validator->validated();
        $data['specializations'] = json_encode($data['specializations'], true);
        //dd($data['specializations']);
        unset($data['submitted'], $_POST);
        //insert into database
        $id = insertToTable(plural($regType), $data);
        $success = true;
    }

    if ($validator->passed() && $regType == REG_MENTEE)
    {
        $data = $validator->validated();
        $mentors = $data['mentors'];
        unset($data['mentors'], $data['submitted'], $_POST);
        $mentee_id = insertToTable(plural($regType), $data);
        foreach($mentors as $mentor_id){
            insertToTable('mentees_mentor', compact('mentor_id', 'mentee_id'));
        }
        $success = true;
    }
}

$aops = getSpecializations();
$mentors = mentors("id, fullname");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Mentor-Mentee</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="antialiased">
<div class="bg-gray-50 min-h-screen text-sm flex flex-col items-center justify-start">
    <div class="navbar w-full bg-gray-600 h-12 flex flex-row items-center justify-end">
        <a href="index.php" class="px-3 mx-2 text-white hover:text-gray-200">Home</a>
        <a href="mentors.php" class="px-3 mx-2 text-white hover:text-gray-200">Mentors</a>
        <a href="mentees.php" class="px-2 mx-2 text-white hover:text-gray-200">Mentees</a>
    </div>
    <div class="bg-gray-50 w-full text-sm flex flex-row items-center justify-center">
    <?php
        if ( is_null($regType) ):
    ?>
        <div class="flex flex-col items-center">
            <div class="my-5 rounded bg-yellow-200 px-4 py-2 border border-yellow-200">You did not select a valid registration type.</div>

            <div>
                <a href="register.php?type=<?=REG_MENTOR?>" class="button mx-2 hover:bg-blue-600">Mentor</a>
                <a href="register.php?type=<?=REG_MENTEE?>" class="button mx-2 hover:bg-blue-600">Mentee</a>
            </div>
        </div>

    <?php
        endif;
        if (REG_MENTOR == $regType):
    ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']."?type=$regType") ?>" method="post" autocomplete="off" class="w-full">
        <div class="w-full flex flex-row items-center justify-center">
        <div class="lg:w-1/3 md:w-1/2 flex flex-col w-full md:py-8 mt-8 md:mt-0">
            <?php if( !empty($success) ){ ?>
            <div class="bg-green-50 border border border-green-200 px-4 py-2 rounded my-2"><?=$regType?> created successfully</div>
            <?php } ?>
            <h2 class="text-gray-900 text-xl mb-1 font-medium uppercase text-center">Register as a <?= $regType ?></h2>
            <p class="leading-relaxed mb-5 text-gray-600">
                Fill in a little bio of yourself as a mentor, and choose one or more areas of specialization
            </p>
            <div class="relative mb-4">
                <label for="name" class="leading-7 text-sm text-gray-600">Full Name</label>
                <input type="text" id="name" name="fullname" value="<?=$_POST[$name = 'fullname']?>"
                       class="w-full text-xs bg-white rounded border border-gray-300 focus:border-indigo-500
                       outline-none text-gray-700 py-0.5 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <?php
                if (show_error($validator, $name)): ?>
                    <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                <?php endif; ?>
            </div>

            <div class="relative mb-4">
                <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                <input type="email" id="email" name="email" value="<?= old($name='email') ?>"
                       class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 text-xs
                       outline-none text-gray-700 py-0.5 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <?php if (show_error($validator, $name)): ?>
                <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                <?php endif; ?>
            </div>

            <div class="relative mb-4">
                <label for="aop" class="leading-7 text-sm text-gray-600">Areas of specializations</label>
                <div class="relative">
                    <select id="aop" name="specializations[]" multiple="multiple"
                            class="aop rounded border appearance-none border-gray-400 py-2 focus:outline-none
                    focus:border-indigo-500 text-xs pl-3 pr-10 w-full">
                        <option>Choose One</option>
                        <?php foreach($aops as $aop): ?>
                        <option value="<?=$aop?>" <?= !empty($_POST['specializations']) && in_array($aop, $_POST['specializations']) ? 'selected="selected"': '' ?>><?=$aop?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none
                    flex items-center justify-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </span>
                </div>
                <?php if (show_error($validator, $name='specializations')): ?>
                    <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                <?php endif; ?>
            </div>

            <div class="relative mb-4">
                <label for="message" class="leading-7 text-sm text-gray-600">About</label>
                <textarea id="message" name="about"
                          class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 h-20
                          text-xs outline-none text-gray-700 py-0.5 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"><?= old($name='about') ?></textarea>
                <?php if (show_error($validator, $name)): ?>
                    <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                <?php endif; ?>
            </div>
            <input type="hidden" name="submitted" value="1">
            <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Submit</button>
        </div>
    </div>
    </form>

    <?php
    endif;
    if (REG_MENTEE == $regType):
    ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']."?type=$regType") ?>" method="post" autocomplete="off" class="w-full">
            <div class="border w-full flex flex-row items-center justify-center">
                <div class="lg:w-1/3 md:w-1/2 flex flex-col w-full md:py-8 mt-8 md:mt-0">
                    <?php if( !empty($success) ){ ?>
                        <div class="bg-green-50 border border border-green-200 px-4 py-2 rounded my-2"><?=$regType?> created successfully</div>
                    <?php } ?>
                    <h2 class="text-gray-900 text-xl mb-1 font-medium uppercase text-center">Register as a <?= $regType ?></h2>
                    <p class="leading-relaxed mb-5 text-gray-600">
                        Fill in a little bio of yourself as a mentee, and choose one or more mentors
                    </p>
                    <div class="relative mb-4">
                        <label for="name" class="leading-7 text-sm text-gray-600">Full Name</label>
                        <input type="text" id="name" name="fullname" value="<?=old($name = 'fullname')?>"
                               class="w-full text-xs bg-white rounded border border-gray-300 focus:border-indigo-500
                       outline-none text-gray-700 py-0.5 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <?php
                        if (show_error($validator, $name)): ?>
                            <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                        <input type="email" id="email" name="email" value="<?= old($name='email') ?>"
                               class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 text-xs
                       outline-none text-gray-700 py-0.5 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <?php if (show_error($validator, $name)): ?>
                            <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="relative mb-4">
                        <label for="aop" class="leading-7 text-sm text-gray-600">Mentor(s)</label>
                        <div class="relative">
                            <select id="aop" name="mentors[]" multiple="multiple"
                                    class="aop rounded border appearance-none border-gray-400 py-2 focus:outline-none
                    focus:border-indigo-500 text-xs pl-3 pr-10 w-full">
                                <option>Choose One</option>
                                <?php foreach($mentors as $mentor): ?>
                                    <option value="<?=$mentor['id']?>"
                                        <?= !empty($_POST['specializations']) && in_array($aop, $_POST['specializations']) ? 'selected="selected"': '' ?>>
                                        <?=ucwords(
                                                $mentor['fullname'])?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none
                    flex items-center justify-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </span>
                        </div>
                        <?php if (show_error($validator, $name='mentors')): ?>
                            <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="relative mb-4">
                        <label for="message" class="leading-7 text-sm text-gray-600">About</label>
                        <textarea id="message" name="about"
                                  class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 h-20
                          text-xs outline-none text-gray-700 py-0.5 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"><?= old($name='about') ?></textarea>
                        <?php if (show_error($validator, $name)): ?>
                            <span class="text-xs text-red-600"><?= $validator->getErrors()[$name][0] ?></span>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="submitted" value="1">
                    <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Submit</button>
                </div>
            </div>
        </form>

    <?php
    endif;
    ?>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.aop').select2();
    });
</script>
</body>
</html>
