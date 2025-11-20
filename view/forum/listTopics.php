<?php


$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
$user = \App\Session::getUser();
$messageManager = new \Model\Managers\MessageManager();



?>

<h1>Catégorie : <?= htmlspecialchars($category->getName(), ENT_QUOTES, 'UTF-8') ?></h1>

<?php if ($user): ?>
    <h2>Créer un nouveau topic</h2>

    <form action="index.php?ctrl=forum&action=addTopic&id=<?= $category->getId() ?>" method="post">
        <label for="title">Titre du topic :</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Message :</label><br>
        <textarea name="content" id="content" required></textarea><br><br>

        <button type="submit" name="submit">Créer le topic</button>
    </form>
<?php else: ?>
    <p><em>Vous devez être connecté pour créer un topic.</em></p>
<?php endif; ?>

<h2>Topics :</h2>

<?php if ($topics): ?>
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <a href="index.php?ctrl=forum&action=listMessage&id=<?= $topic->getId() ?>">
                    <strong><?= htmlspecialchars($topic->getTitle(), ENT_QUOTES, 'UTF-8') ?></strong>
                </a><br>
                
                <!-- Récupérer le premier message du topic -->
                <?php 
                $messages = $messageManager->findMessagesByTopic($topic->getId());
                $firstMessage = $messages[0] ?? null;
                 var_dump($firstMessage); // Affiche le premier message
                ?>
                
                <small>Créé le <?= $topic->getCreationDate() ?></small>
                <small>
                    par 
                    <a href="index.php?ctrl=security&action=showProfile&id=<?= $topic->getUser()->getId() ?>">
                        <?= htmlspecialchars($topic->getUser()->getNickName(), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </small>
            </li>
        <?php endforeach; ?>
        
    </ul>
<?php else: ?>
    <p>Aucun topic dans cette catégorie pour le moment.</p>
<?php endif; ?>

<p><a href="index.php">⬅️ Retour aux catégories</a></p>
