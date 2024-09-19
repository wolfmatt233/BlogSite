<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <div class="p-2 border-t border-gray-200 mb-5">
            <a href="/posts/<?= htmlspecialchars($post->getId()) ?>" class="text-2xl hover:underline">
                <?= htmlspecialchars($post->getTitle()) ?>
            </a>
            <p>By <?= htmlspecialchars($post->getName()) ?></p>
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
                    <a class="form-button mr-2" href="/posts/<?= htmlspecialchars($post->getId()) ?>/edit">Edit</a>
                    <button onclick="deleteModal(<?= htmlspecialchars($post->getId()) ?>)" class="form-button">Delete</button>
                </div>
            <?php endif ?>
        </div>
    <?php endforeach ?>

    <div class="absolute bottom-4 left-1/2">
        <?php for ($i = 1; $i < $pages + 1; $i++): ?>
            <?php if ($i > 4): ?>
                <p class="mr-1">...</p>
                <a class="form-button mr-1 px-3"
                    href="<?= BASE_URL ?>/posts?page=<?= htmlspecialchars($pages) ?>"><?= htmlspecialchars($pages) ?>
                </a>
            <?php else: ?>
                <a class="form-button mr-1 px-3"
                    href="<?= BASE_URL ?>/posts?page=<?= htmlspecialchars($i) ?>"><?= htmlspecialchars($i) ?>
                </a>
            <?php endif ?>
        <?php endfor ?>
    </div>
<?php else: ?>
    <p>No posts yet</p>
<?php endif ?>