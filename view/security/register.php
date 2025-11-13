<h2>Inscription</h2>

<!-- Messages flash -->
<?php if (!empty($_SESSION['flash']['error'])): ?>
    <div >
        <?= htmlspecialchars($_SESSION['flash']['error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash']['success'])): ?>
    <div>
        <?= htmlspecialchars($_SESSION['flash']['success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash']['success']); ?>
<?php endif; ?>

<form action="?ctrl=security&action=register" method="post">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" minlength="12"
        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}"
        required>

    <label for="password_repeat">Vérification du mot de passe :</label>
    <input type="password" name="password_repeat" id="password_repeat" minlength="12"
        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}"
        required>

    <button type="submit" name="submit">S'inscrire</button>

    <div>
        <strong>Le mot de passe doit contenir :</strong>
        <ul>
            <li>12 caractères minimum</li>
            <li>1 majuscule</li>
            <li>1 chiffre</li>
            <li>1 symbole spécial</li>
        </ul>
    </div>
</form>
