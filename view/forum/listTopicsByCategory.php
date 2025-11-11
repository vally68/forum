<?php
$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
?>

<h1>Catégorie : <?= $category->getName() ?></h1>

<?php if ($topics): ?>
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <strong><?= htmlspecialchars($topic->getTitle()) ?></strong><br>
                <small>Créé le <?= $topic->getDateCreation() ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun topic dans cette catégorie pour le moment.</p>
<?php endif; ?>

<p><a href="index.php">⬅️ Retour aux catégories</a></p>
