<?php
$users = $result["data"]["users"];
?>

<h1>Liste des utilisateurs</h1>

<?php if ($users): ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Date d'inscription</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->getId() ?></td>
                    <td>
                        <a href="index.php?ctrl=security&action=profile&id=<?= $user->getId() ?>">
                            <?= htmlspecialchars($user->getNickName()) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                    <td><?= $user->getCreationDate() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Aucun utilisateur enregistré.</p>
<?php endif; ?>
