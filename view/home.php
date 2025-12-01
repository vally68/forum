<?php
header('Content-Type: text/html; charset=UTF-8');
?>
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
        <?= ($msg->getTexte()) ?>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="message-bubble">Aucun message pour le moment...</div>
  <?php endif; ?>
</div>


  <div class="home-content">
    <h1>BIENVENUE SUR LE FORUM</h1>
    <p>WELCOME TO THE WEB-JUNGLE!</p>
    <div class="home-actions">
             <div id="nav-right">
            <?php if (App\Session::getUser()): ?>
                <!-- Utilisateur connecté -->
                <a href="index.php?ctrl=security&action=profile">
                    <span class="fas fa-user"></span>&nbsp;<?= App\Session::getUser() ?>
                </a>
                <a class="btn btn-primary" href="index.php?ctrl=security&action=logout">Déconnexion</a>
            <?php else: ?>
                <!-- Utilisateur non connecté -->
                <a class="btn btn-primary" href="index.php?ctrl=security&action=login">Connexion</a>
                <a class="btn btn-secondary" href="index.php?ctrl=security&action=register">Inscription</a>
            <?php endif; ?>
        </div>
    
    </div>
  </div>
</section>


<script src="public/js/homeMessages.js"></script>
