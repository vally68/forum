<?php
header('Content-Type: text/html; charset=UTF-8');
?>


<?php
$topic = $result['data']['topic'];
$messages = $result['data']['messages'];
$user = \App\Session::getUser();
?>
<meta charset="utf-8">
<h1>Topic : <?= htmlspecialchars($topic->getTitle(), ENT_QUOTES, 'UTF-8') ?></h1>

<?php if (!empty($messages)): ?>
    <ul>
        <?php foreach ($messages as $msg): ?>
            <li style="margin-bottom: 1.5rem;">
                <div class="message-content">
                    <?= ($msg->getTexte()) ?><br>
                    <small>Posté le <?= htmlspecialchars($msg->getCreationDate()) ?></small>
                    <small>Par 
    <?= $msg->getUser() ? htmlspecialchars($msg->getUser()->getNickName()) : "<em>Utilisateur inconnu</em>" ?>
</small>

                   

                </div>

                <?php if ($user): ?>
                    <?php
                        //  Autorisations :
                        //  - Admin / Modérateur : ok
                        
                        $canEdit = (
                            in_array($user->getStatut(), ['Admin', 'Moderator'], true)
                            || ($topic->getUser() && $user->getId() === $topic->getUser()->getId())
                        );
                    ?>

                    <?php if ($canEdit): ?>
                        <div class="message-actions">
                            <form class="edit-form"
                                  action="index.php?ctrl=forum&action=updateMessage&id=<?= $msg->getId() ?>"
                                  method="post" style="display:inline-block;margin-right:8px;">
                                <div class="edit-row">
                                    <textarea name="texte" rows="1" required><?= ($msg->getTexte()) ?></textarea>
                                    <button type="submit" class="btn-edit">✏️ Modifier</button>
                                </div> 
                            </form>

                            <form action="index.php?ctrl=forum&action=deleteMessage&id=<?= $msg->getId() ?>"
                                  method="post" style="display:inline-block;">
                                <button type="submit" class="btn-delete"
                                        onclick="return confirm('Supprimer ce message ?');">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun message pour le moment.</p>
<?php endif; ?>

<p>
    <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= is_object($topic->getCategory()) ? $topic->getCategory()->getId() : $topic->getCategory() ?>">
        ⬅️ Retour aux topics
    </a>
</p>

<?php if ($user): ?>
    <h2>Répondre au topic</h2>
    <form action="index.php?ctrl=forum&action=addMessage" method="post">
        <input type="hidden" name="id_topic" value="<?= $topic->getId() ?>">
        <textarea name="texte" rows="5" required></textarea><br>
        <button type="submit">Envoyer</button>
    </form>
<?php else: ?>
    <p><em>Connectez-vous pour répondre à ce topic.</em></p>
<?php endif; ?>
