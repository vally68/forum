<?php
$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
$user = \App\Session::getUser();
$messageManager = new \Model\Managers\MessageManager();
?>

<meta charset="UTF-8">

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
                <!-- Lien vers le topic -->
                <a href="index.php?ctrl=forum&action=listMessage&id=<?= $topic->getId() ?>">
                    <strong><?= htmlspecialchars($topic->getTitle(), ENT_QUOTES, 'UTF-8') ?></strong>
                </a><br>

                <?php
                // Récupérer le premier message du topic
                $messages = $messageManager->findMessagesByTopic($topic->getId());
            if ($messages instanceof \Traversable) {
                $messages = iterator_to_array($messages);
            }
            $firstMessage = !empty($messages) ? $messages[0] : null;
            ?>

                <?php if ($firstMessage): ?>
                    <p>
                        <em>Premier message :</em><br>
                        <?= nl2br(htmlspecialchars($firstMessage->getTexte(), ENT_QUOTES, 'UTF-8')) ?>
                    </p>
                <?php endif; ?>

                <div class="topic-meta">
                    <small>Créé le <?= htmlspecialchars($topic->getCreationDate()) ?></small><br>
                    <small>
                        par 
                        <a href="index.php?ctrl=security&action=showProfile&id=<?= $topic->getUser()->getId() ?>">
                            <?= htmlspecialchars($topic->getUser()->getNickName(), ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </small>

                    <!-- ✅ DROITS DE MODIFICATION -->
                    <?php if (
                        $user &&
                        (
                            in_array($user->getStatut(), ['Admin', 'Moderator'], true)
                            || $user->getId() === $topic->getUser()->getId()
                        )
                    ): ?>
                        <!-- Formulaire d'édition inline -->
                        <form action="index.php?ctrl=forum&action=updateTopic&id=<?= $topic->getId() ?>" 
                              method="post" 
                              style="display:inline-block; margin-left:10px;">
                            <input type="text" name="title" 
                                   value="<?= htmlspecialchars($topic->getTitle()) ?>" 
                                   required style="padding:3px;">
                            <button type="submit" style="padding:3px 6px;">✏️ Modifier</button>
                        </form>

                        <!-- Bouton suppression -->
                        <form class="delete-topic-form"
                              action="index.php?ctrl=forum&action=deleteTopic&id=<?= $topic->getId() ?>"
                              method="post" style="display:inline-block; margin-left:10px;">
                            <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce topic ?');">
                                🗑️ Supprimer
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun topic dans cette catégorie pour le moment.</p>
<?php endif; ?>

<p><a href="index.php">⬅️ Retour aux catégories</a></p>
