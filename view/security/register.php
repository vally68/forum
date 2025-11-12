<h2>Inscription</h2>

<form action="?ctrl=security&action=register" method="post">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password"  minlength="12"
       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}" 
       required>
        <label for="password">Verification du mot de passe :</label>
    <input type="password" name="passwordverif" id="passwordverif"  minlength="12"
       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}" 
       required>


    <button type="submit" name="submit">S'inscrire</button>
    <div>le mot de passe doit contenir: 
        <ul>
            <li>12 caractères</li> 
            <li>1 majuscules</li> 
            <li>1 chiffre</li> 
            <li>1 symbole spécial</li>
        </ul>
    </div>
</form>
