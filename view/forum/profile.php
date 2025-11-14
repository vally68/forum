<?php
$user = $result['data']['user'];
?>

<h1>Profil de <?= htmlspecialchars($user->getNickName(), ENT_QUOTES, 'UTF-8') ?></h1>

<ul>
    <li><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user->getNickName(), ENT_QUOTES, 'UTF-8') ?></li>
    <li><strong>Email :</strong> <?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></li>
    <li><strong>Statut :</strong> <?= htmlspecialchars($user->getStatut(), ENT_QUOTES, 'UTF-8') ?></li>
</ul>

<p>
    <a href="index.php">⬅️ Retour à l'accueil</a>
</p>
