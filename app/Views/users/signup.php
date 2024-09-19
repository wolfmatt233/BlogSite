<div class="flex items-center justify-evenly">
    <form action="/users/create" method="POST"
        class="flex flex-col w-full max-w-96 border border-gray-200 p-3 rounded-md">
        <p class="text-lg text-center">Sign Up</p>
        <label for="name">Username</label>
        <input type="text" name="name" placeholder="Username" class="form-input">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-input">
        <input type="submit" value="Sign Up" class="form-button">
        <p class="text-center text-red-500 mt-2">
            <?= isset($_GET['e'])? $_GET['e'] : "" ?>
        </p>
        <a href="/users/login" class="text-blue-400 text-center hover:underline mt-3">
            Already have an account? Log in here!
        </a>
    </form>
</div>