<form action="/posts/upload" method="POST">
    <div class="flex flex-col">
        <label for="title">Title</label>
        <input type="text" name="title" placeholder="Title" class="form-input" required>

        <label for="content">Content</label>
        <textarea name="content" rows="5" placeholder="Content" class="form-input" required></textarea>

        <div>
            <label for="private" class="select-none">Private</label>
            <input type="checkbox" name="private" id="private">
        </div>

        <p class="text-center text-red-500 mt-2">
            <?= isset($_GET['e']) ? $_GET['e'] : "" ?>
        </p>

        <input type="submit" value="Upload Post" class="form-button">
    </div>
</form>