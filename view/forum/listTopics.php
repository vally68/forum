<?php
$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
?>

<h1>Catégorie : <?= $category->getName() ?></h1>

<?php if ($topics): ?>
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
<a href="index.php?ctrl=forum&action=listMessage&id=<?= $topic->getId() ?>">
    <strong><?= htmlspecialchars($topic->getTitle(), ENT_QUOTES, 'UTF-8') ?></strong>
</a>
                </a><br>                <small>Créé le <?= $topic->getCreationDate() ?></small>
                <small>par <?= $topic->getuser() ?></small>

            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun topic dans cette catégorie pour le moment.</p>
<?php endif; ?>

<p><a href="index.php">⬅️ Retour aux catégories</a></p>