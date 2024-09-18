<?= isset($_SESSION['name']) ? "Welcome {$_SESSION['name']}!" : "" ?>
<p>Welcome to your very own blog website! Here you will be able to upload posts to your blog. With privacy options,
    whether you want them to be a personal journal entry or a public post is up to you!</p>
<p>
    <a class="text-blue-400 hover:underline" href="/users/signup">Creating an account</a>
    is as easy as the click of a button!
</p>