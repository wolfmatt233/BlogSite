<table class="border-collapse w-full">
    <tr class="bg-gray-200 border-b-2 border-white">
        <th class="text-left p-2">Name</th>
        <th class="text-left p-2">Password</th>
        <?php if ($_SESSION['admin'] == 1): ?>
            <th>Actions</th>
        <?php endif; ?>

    </tr>
    <?php foreach ($users as $user): ?>

        <tr id="user-info-<?= htmlspecialchars($user->getId()) ?>" class="bg-gray-200 border-b-2 border-white">
            <td class="text-left p-2"><?= htmlspecialchars($user->getName()) ?></td>
            <td class="text-left p-2"><?= htmlspecialchars($user->getPassword()) ?></td>

            <?php if ($_SESSION['admin'] == 1): ?>
                <td class="text-left p-2">
                    <button class="form-button mt-0 border-[#4b556d] w-full p-[2px]"
                        onclick='showEdit(<?= htmlspecialchars($user->getId()) ?>)'>Edit</button>
                </td>
            <?php endif; ?>
        </tr>
        <?php if ($_SESSION['admin'] == 1): ?>
            <form action="/updateUser" method="POST">
                <tr id="user-inputs-<?= htmlspecialchars($user->getId()) ?>" class="hidden bg-gray-200 border-b border-white">
                    <td class="text-left p-2">
                        <input type="text" value="<?= htmlspecialchars($user->getName()) ?>" class="user-input">
                    </td>
                    <td class="text-left p-2">
                        <input type="text" value="<?= htmlspecialchars($user->getPassword()) ?>" class="user-input w-full">
                    </td>
                    <td class="text-left p-2">
                        <input class="form-button mt-0 border-[#4b556d] w-full p-[2px]" type="submit" value="Update">
            </form>
            <button class="form-button mt-[3px] border-[#4b556d] w-full p-[2px]"
                onclick="hideEdit(<?= htmlspecialchars($user->getId()) ?>)">Cancel</button>
            </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>