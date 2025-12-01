<?php
ob_start();
?>
<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<h1>Règlement du Forum</h1>


    <h2>1. Respect et courtoisie</h2>
    <p>
        Tous les membres doivent se montrer respectueux.  
        Les insultes, propos discriminatoires, harcèlement ou comportements toxiques sont strictement interdits.
    </p>

    <h2>2. Contenu interdit</h2>
    <p>
        Il est interdit de publier :
        <ul>
            <li>Contenu violent, pornographique ou choquant</li>
            <li>Spam, publicité ou liens douteux</li>
            <li>Contenu illégal ou incitant à des activités illégales</li>
        </ul>
    </p>

    <h2>3. Structure des discussions</h2>
    <p>
        Merci de :
        <ul>
            <li>Poster dans la bonne catégorie</li>
            <li>Utiliser un titre clair</li>
            <li>Ne pas créer de doublons de sujets</li>
        </ul>
    </p>

    <h2>4. Modération</h2>
    <p>
        Les modérateurs et administrateurs peuvent :
        <ul>
            <li>Modifier ou supprimer un message</li>
            <li>Fermer un sujet</li>
            <li>Sanctionner un membre en cas d'abus</li>
        </ul>
    </p>

    <h2>5. Sanctions</h2>
    <p>
        Selon la gravité des faits, les sanctions peuvent inclure :
        <ul>
            <li>Avertissement</li>
            <li>Suspension temporaire</li>
            <li>Bannissement définitif</li>
        </ul>
    </p>

    <h2>6. Acceptation du règlement</h2>
    <p>
        En utilisant ce forum, vous acceptez automatiquement ce règlement.  
        Celui-ci peut être mis à jour à tout moment.
    </p>
 <div class="home-actions">
             <div id="nav-right">
            <?php if (App\Session::getUser()): ?>
                <!-- Utilisateur connecté -->
                <a href="index.php?ctrl=security&action=profile">
                    <span class="fas fa-user"></span>&nbsp;<?= App\Session::getUser() ?>
                </a>
                
                <a class="btn btn-primary" href="index.php?ctrl=security&action=logout">Déconnexion</a>
                <a class="btn btn-primary" href="index.php?ctrl=index.php">Retour</a>
            <?php else: ?>
                <!-- Utilisateur non connecté -->
                <a class="btn btn-primary" href="index.php?ctrl=security&action=login">Connexion</a>
                <a class="btn btn-secondary" href="index.php?ctrl=security&action=register">Inscription</a>
            <?php endif; ?>
        </div>


