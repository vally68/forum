<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Manager;
use Model\Managers\UserManager;

class SecurityController extends AbstractController{
    // contiendra les fonctions liées à l'authentification : register, login et logout

public function register()
{
    // 1. Si le formulaire est soumis
    if (isset($_POST['submit'])) {

        // Récupération et filtrage des champs
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $passwordRepeat = filter_input(INPUT_POST, 'password_repeat', FILTER_DEFAULT);
        $dateCreation = date('Y-m-d H:i:s');

        // Vérifier les champs vides
        if (!$username || !$email || !$password || !$passwordRepeat) {
            $_SESSION['flash']['error'] = "Tous les champs doivent être remplis.";
            header('Location: ?ctrl=security&action=register');
            exit;
        }

        // Vérifier la force du mot de passe coté serveur
        $pattern = "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}/";
        if (!preg_match($pattern, $password)) {
            $_SESSION['flash']['error'] = "🖐️ Mot de passe invalide : il doit contenir au moins 12 caractères, une majuscule, un chiffre et un symbole spécial.";
            header('Location: ?ctrl=security&action=register');
            exit;
        }

        // Vérifier la correspondance des mots de passe
        if ($password !== $passwordRepeat) {
            $_SESSION['flash']['error'] = " ☝️ Les  mots de passe ne correspondent pas.";
            header('Location: ?ctrl=security&action=register');
            exit;
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer les données qui seront inséres en  BDD
        $data = [
            'nickName' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'creationDate' => $dateCreation
        ];

        // Ajouter l'utilisateur en BDD  via le  UserManager
        $userManager = new \Model\Managers\UserManager();
        $userManager->add($data);

        // Message de réussite 
        $_SESSION['flash']['success'] = "👍Inscription réussie ! Vous pouvez maintenant vous connecter.";

        // Redirection vers login qui fonctionne cette fois
        header('Location: ?ctrl=security&action=login');
        exit;
    }

    // 2. Si aucune soumission ou quand on arrive afficher le formulaire
    return [
        'view' => VIEW_DIR . 'security/register.php',
        'meta_description' => 'Inscription sur le site'
    ];
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