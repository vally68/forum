<?php
$users = $result["data"]["users"];
$editingId = $_GET['edit'] ?? null;
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
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $user): ?>

                <?php if ($editingId == $user->getId()): ?>
                    <!-- MODE ÉDITION -->
                    <tr style="background:#f3f3f3">

                        <form action="index.php?ctrl=home&action=updateUser&id=<?= $user->getId() ?>" method="post">

                            <td><?= $user->getId() ?></td>

                            <td><input type="text" name="nickName" value="<?= htmlspecialchars($user->getNickName()) ?>"></td>

                            <td><input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>"></td>

                            <td><?= $user->getCreationDate() ?></td>

                            <td>
                                <select name="statut">
                                    <option value="User" <?= $user->getStatut()=="User"?"selected":"" ?>>Utilisateur</option>
                                    <option value="Admin" <?= $user->getStatut()=="Admin"?"selected":"" ?>>Admin</option>
                                    <option value="Moderator" <?= $user->getStatut()=="Moderator"?"selected":"" ?>>Modérateur</option>
                                </select>
                            </td>

                            <td>
                                <button type="submit">💾 Sauvegarder</button>
                                <a href="index.php?ctrl=home&action=users">❌ Annuler</a>
                            </td>

                        </form>
                    </tr>

                <?php else: ?>
                    <!-- MODE AFFICHAGE -->
                    <tr>
                        <td><?= $user->getId() ?></td>

                        <td>
                            <a href="index.php?ctrl=security&action=profile&id=<?= $user->getId() ?>">
                                <?= htmlspecialchars($user->getNickName()) ?>
                            </a>
                        </td>

                        <td><?= htmlspecialchars($user->getEmail()) ?></td>

                        <td><?= $user->getCreationDate() ?></td>

                        <td><?= $user->getStatut() ?></td>

                        <td>
                            <a href="index.php?ctrl=home&action=users&edit=<?= $user->getId() ?>">✏️ Modifier</a>
                            |
                            <a href="index.php?ctrl=home&action=deleteUser&id=<?= $user->getId() ?>"
                                onclick="return confirm('Supprimer cet utilisateur ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Aucun utilisateur enregistré.</p>
<?php endif; ?>
