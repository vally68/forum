<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Manager;
use Model\Managers\UserManager;

class SecurityController extends AbstractController{
    // contiendra les méthodes liées à l'authentification : register, login et logout

    public function register () {
    // 1. Gérer la soumission du formulaire (POST)
    if (isset($_POST['submit'])) {
        
        // 2. Valider et filtrer les données 
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        
        // 3. Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 4. Préparer les données pour le Manager
        $data = [
            'nickName' => $username,
            'email'    => $email,
            'password' => $hashedPassword
        ];
        
        // 5. Utiliser le UserManager pour insérer l'utilisateur
        $userManager = new UserManager();
        $userManager->add($data);
        
        // 6. Rediriger
        // $this->redirectTo('login');
    }

    // 7. Afficher le formulaire (GET)
     return [
        'view' => VIEW_DIR . 'security/register.html.php',
        'meta_description' => 'Inscription sur le site'
    ];;
}
    public function login()
{
    // Si le formulaire est soumis
    if (isset($_POST['submit'])) {
        
        // 1 Récupération et filtrage des champs
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        // 2 On appelle le UserManager
        $userManager = new \Model\Managers\UserManager();
$userData = \App\DAO::select("SELECT * FROM user WHERE email = :email", ['email' => $email], false);

if ($userData && password_verify($password, $userData['password'])) {
    // Stocker un objet User, pas un tableau
    $_SESSION['user'] = new \Model\Entities\User($userData);

    header("Location: index.php");
    exit;
} else {
    $error = "Email ou mot de passe incorrect.";
}

    }

    //  Affichage du formulaire
    return [
        'view' => VIEW_DIR . 'security/login.php',
        'meta_description' => 'Connexion utilisateur',
        'error' => $error ?? null
    ];
}

    public function logout()
{
    session_destroy();
    header("Location: index.php");
    exit;
}

}