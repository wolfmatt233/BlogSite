<div class="p-2 border-t border-gray-200 mb-5 flex flex-col">
    <a class="text-2xl">
        <?= htmlspecialchars($post->getTitle()) ?>
    </a>
    <a href="/posts/users/<?= htmlspecialchars($post->getUserId()) ?>" class="hover:underline">By
        <?= htmlspecialchars($post->getName()) ?></a>
    <p>
        <?=
            htmlspecialchars(
                (new DateTime($post->getCreated()))->format('F j, Y \a\t g:i a')
            );
        ?>
    </p>

    <p class="my-3"><?= htmlspecialchars($post->getContent()) ?></p>

    <?php if ($post->getCreated() != $post->getUpdated()): ?>
        <p class="text-sm">
            <?=
                "Updated " . htmlspecialchars(
                    (new DateTime($post->getUpdated()))->format('F j, Y \a\t g:i a')
                );
            ?>
        </p>
    <?php endif ?>

    <?php if ($_SESSION['user_id'] == $post->getUserId() && $_SESSION['loggedin'] == TRUE): ?>
        <div class="flex items-center justify-start">
            <a class="form-button mr-2" href="/posts/edit/<?= htmlspecialchars($post->getId()) ?>">Edit</a>
            <button onclick="deleteModal(<?= htmlspecialchars($post->getId()) ?>)" class="form-button">Delete</button>
        </div>
    <?php endif ?>
</div>

<div class="p-2 border-t border-gray-200 mb-5">
    <p class="text-xl text-center mb-2">Comments</p>
    <?php if (count($comments) > 0): ?>
        <!-- Comments -->
        <?php foreach ($comments as $comment): ?>
            <div class="border border-gray-200 rounded-md p-2">
                <a href="/users/<?= htmlspecialchars($comment->getUserId()) ?>"
                    class="hover:underline"><?= htmlspecialchars($comment->getName()) ?></a>
                <p><?= htmlspecialchars($comment->getContent()) ?></p>
            </div>
        <?php endforeach ?>

        <!-- Comments Pagination -->
        <div class="absolute bottom-4 left-1/2">
            <?php for ($i = 1; $i < $pages + 1; $i++): ?>
                <?php if ($i > 4): ?>
                    <p class="mr-1">...</p>
                    <a class="form-button mr-1 px-3"
                        href="<?= BASE_URL ?>/posts/<?= htmlspecialchars($post->getId()) ?>?page=<?= htmlspecialchars($pages) ?>"><?= htmlspecialchars($pages) ?>
                    </a>
                <?php else: ?>
                    <a class="form-button mr-1 px-3"
                        href="<?= BASE_URL ?>/posts/<?= htmlspecialchars($post->getId()) ?>?page=<?= htmlspecialchars($i) ?>"><?= htmlspecialchars($i) ?>
                    </a>
                <?php endif ?>
            <?php endfor ?>
        </div>
    <?php else: ?>
        <p class="text-center mt-3">No comments yet.</p>
    <?php endif ?>
</div>