<form action="/posts/update/<?= htmlspecialchars($post->getId()) ?>" method="POST">
    <div class="flex flex-col">
        <label for="title">Title</label>
        <input type="text" name="title" placeholder="Title" class="form-input" required
            value="<?= htmlspecialchars($post->getTitle()) ?>">

        <label for="content">Content</label>
        <textarea name="content" rows="5" placeholder="Content" class="form-input"
            required><?= htmlspecialchars($post->getContent()) ?></textarea>

        <div>
            <label for="private" class="select-none">Private</label>
            <input type="checkbox" name="private" id="private" <?php print ($post->getPrivate() == true) ? "checked" : ""; ?>>
        </div>

        <input type="submit" value="Update Post" class="form-button">
    </div>
</form>