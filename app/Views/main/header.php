<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/output.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>/images/favicon.ico" type="image/x-icon">
    <title>Blogs - <?= $title ?></title>
</head>

<body class="bg-[#f3f4f7] text-[#4b556d]">
    <nav class="bg-[#323c48] shadow-[0px_3px_5px_#9f9898] px-3">
        <div class="flex items-center justify-evenly m-auto max-w-[900px] w-full h-12 relative">
            <div>
                <p class="text-white text-lg">Blog Website</p>
            </div>
            <div id="burger" class="burger-off w-8 cursor-pointer h-full absolute top-0 left-0 z-20">
                <hr class="w-full border border-white absolute">
                <hr class="w-full border border-white absolute">
                <hr class="w-full border border-white absolute">
            </div>
        </div>
    </nav>

    <div
        class="max-w-[900px] w-full m-auto border-b border-gray-200 flex items-center justify-between pt-[25px] pb-[10px] px-3">
        <p class="text-xl"><?= $title ?></p>
        <?php if (isset($_GET['m'])): ?>
            <p id='nav-message' class='text-center text-green-600 cursor-pointer hover:text-red-600'>&#215;
                <?= $_GET['m'] ?>
            </p>
        <?php endif ?>
        <?php if (isset($message)): ?>
            <p id='nav-message' class="text-center text-green-600 cursor-pointer hover:text-red-600">&#215; <?= $message ?>
            </p>
        <?php endif ?>
    </div>

    <div id="drawer" class="close-menu absolute top-0 h-full bg-[#f3f4f7] text-center flex flex-col z-10">
        <div class="nav-link bg-[#323c48] text-white select-none hover:bg-[#323c48]">Menu</div>
        <a href="/" class="nav-link">Home</a>
        <?= isset($_SESSION['admin']) && $_SESSION['admin'] === TRUE ?
            "<a href='/users' class='nav-link'>Users</a>" : "" ?>
        <?php
        if (isset($_SESSION['loggedin'])): ?>
            <a href='/posts?page=1' class='nav-link'>Browse Posts</a>
            <a href="/posts/personal" class="nav-link">Your Posts</a>
            <a href="/posts/create" class="nav-link">Create a Post</a>
            <a href='/users/logout' class="nav-link">Log Out</a>
        <?php else: ?>
            <a href='/users/login' class="nav-link">Log In</a>
        <?php endif ?>
    </div>

    <div onclick="closeModal(this, event)" id="modal"
        class="z-[100] absolute top-0 left-0 w-full h-full bg-[#00000012] hidden">
    </div>

    <div
        class="w-full max-w-[900px] my-[25px] mx-auto min-h-[calc(100vh-194px)] p-[20px] bg-white rounded-md shadow-[0px_0px_2px_#9f9898] relative max-lg:rounded-none">