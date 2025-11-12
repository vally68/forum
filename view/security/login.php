<h2>Connexion</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<form action="?ctrl=security&action=login" method="post">
    <div>
        <label for="email">Adresse email :</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
    </div>

    <button type="submit" name="submit">Se connecter</button>
</form>
