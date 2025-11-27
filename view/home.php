<?php
$lastMessages = isset($result["data"]["lastMessages"]) ? $result["data"]["lastMessages"] : [];
?>

<section class="home-welcome">
  <svg class="background-line" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" preserveAspectRatio="none">
    <path id="serpent-path"
      d="M45 1057.5 
         L45 757.5 
         L1800.5 757.5 
         L1800.5 357.5 
         L45 357.5 
         L45 57.5"
      fill="none" stroke="#F9F4DA" stroke-width="150" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>

 <div id="messages-path"> <!-- chemin des messages sur le serpent -->
  <?php if (!empty($lastMessages)): ?>
    <?php foreach ($lastMessages as $msg): ?>
      <a class="message-bubble"
         href="index.php?ctrl=forum&action=showMessage&id=<?= $msg->getId() ?>">
        <?= htmlspecialchars($msg->getTexte(), ENT_QUOTES, 'UTF-8') ?>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="message-bubble">Aucun message pour le moment...</div>
  <?php endif; ?>
</div>


  <div class="home-content">
    <h1>BIENVENUE SUR LE FORUM</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit...</p>
    <div class="home-actions">
      <a class="btn btn-primary" href="#">Se connecter</a>
      <a class="btn btn-secondary" href="#">S'inscrire</a>
    </div>
  </div>
</section>


<script src="public/js/homeMessages.js"></script>
