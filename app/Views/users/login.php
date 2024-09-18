<div class="flex items-center justify-evenly">
    <form action="/users/signin" method="POST"
        class="flex flex-col border border-gray-200 w-full max-w-96 p-3 rounded-md">
        <p class="text-lg text-center">Log In</p>

        <label for="name">Username</label>
        <input type="text" name="name" placeholder="Username" class="form-input">

        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-input">

        <input type="submit" value="Log In"
            class="hover:bg-[#4b556d] hover:text-white rounded-md cursor-pointer border border-gray-200 p-2 mt-3">
        <p class="text-center text-red-500 mt-2">
            <?= isset($_GET['e']) && $_GET['e'] == 'password' ? "Invalid Password" : "" ?>
            <?= isset($_GET['e']) && $_GET['e'] == 'name' ? "Invalid Username" : "" ?>
        </p>
        <a href="/users/signup" class="text-blue-400 text-center hover:underline mt-2">No account? Sign up here!</a>
    </form>
</div>