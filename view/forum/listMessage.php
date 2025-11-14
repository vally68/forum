<?php
$topic = $result['data']['topic'];
$messages = $result['data']['messages'];

// Ici on récupère l'ID de la catégorie depuis le topic
// Si getCategory() renvoie un objet, on prend getId(), sinon on prend directement la valeur
$category = $topic->getCategory();
$categoryId = is_object($category) ? $category->getId() : $category;
?>

<h1>Topic : <?= htmlspecialchars($topic->getTitle(), ENT_QUOTES, 'UTF-8') ?></h1>

<?php if (!empty($messages)): ?>
    <ul>
        <?php foreach ($messages as $msg): ?>
            <li>
                <?= nl2br(htmlspecialchars($msg->getTexte(), ENT_QUOTES, 'UTF-8')) ?><br>
                <small>Posté le <?= $msg->getCreationDate() ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun message pour le moment.</p>
<?php endif; ?>

<p>
    <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $categoryId ?>">
        ⬅️ Retour aux topics
    </a>
</p>

<!-- Formulaire pour poster un nouveau message -->
<h2>Répondre au topic</h2>
<form action="index.php?ctrl=forum&action=addMessage" method="post">
    <input type="hidden" name="id_topic" value="<?= $topic->getId() ?>">
    <textarea name="texte" rows="5" required></textarea><br>
    <button type="submit">Envoyer</button>
</form>
