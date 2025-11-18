<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Manager;
use Model\Managers\UserManager;
use App\Session;

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

        // Vérifier si l'utilisateur existe déjà (email ou pseudo)
        $userManager = new \Model\Managers\UserManager();

        // Vérif email
        $existingEmail = \App\DAO::select(
            "SELECT id_user FROM user WHERE email = :email",
            ['email' => $email],
            false
        );

        if ($existingEmail) {
            $_SESSION['flash']['error'] = "⚠️ Cet email est déjà utilisé.";
            header('Location: ?ctrl=security&action=register');
            exit;
        }

        // Vérif pseudo
        $existingUsername = \App\DAO::select(
            "SELECT id_user FROM user WHERE nickName = :username",
            ['username' => $username],
            false
        );

        if ($existingUsername) {
            $_SESSION['flash']['error'] = "⚠️ Ce nom d'utilisateur est déjà pris.";
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
            $_SESSION['flash']['error'] = "☝️ Les mots de passe ne correspondent pas.";
            header('Location: ?ctrl=security&action=register');
            exit;
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer les données pour la BDD
        $data = [
            'nickName' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'creationDate' => $dateCreation
        ];

        // Ajouter l'utilisateur en BDD via le UserManager
        $userManager->add($data);

        // Message de réussite
        $_SESSION['flash']['success'] = "👍 Inscription réussie ! Vous pouvez maintenant vous connecter.";

        header('Location: ?ctrl=security&action=login');
        exit;
    }

    // 2. Si aucune soumission : affichage du formulaire
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
$userData = \App\DAO::select(
    "SELECT 
        id_user AS id, 
        nickName,
        email,
        password,
        statut
     FROM user 
     WHERE email = :email",
    ['email' => $email],
    false
);

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

public function profile() {

        // Vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?ctrl=security&action=login");
            exit;
        }

        /** @var User $user */
        $user = $_SESSION['user'];

        // Gestion de la soumission du formulaire de modification
        if (isset($_POST['submit'])) {

            $newNick = filter_input(INPUT_POST, 'nickName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            if (!$newNick || !$newEmail) {
                $_SESSION['flash']['error'] = "Tous les champs doivent être remplis.";
                header("Location: index.php?ctrl=security&action=profile");
                exit;
            }

            // Mettre à jour l'utilisateur en BDD
            $userManager = new UserManager();
            $user->setNickName($newNick)->setEmail($newEmail);
            $userManager->update($user); // a faire plus tard

            // Mettre à jour l'utilisateur en session
            $_SESSION['user'] = $user;

            $_SESSION['flash']['success'] = "Profil mis à jour avec succès !";
            header("Location: index.php?ctrl=security&action=profile");
            exit;
        }

        return [
            "view" => VIEW_DIR."forum/profile.php",
            "meta_description" => "Profil de ".$user->getNickName(),
            "data" => [
                "user" => $user
            ]
        ];
    }

    public function showProfile($id) //récupération des infos de profil
{
    $userManager = new UserManager();
    $user = $userManager->findOneById($id);

    if (!$user) {
        die("Utilisateur introuvable");
    }

    return [
    "view" => VIEW_DIR."forum/profile.php",
    "meta_description" => "Profil de ".$user->getNickName(),
    "data" => [
        "user" => $user
    ]
];

}

public function cgu() //générer la vue cgu
{
    return [
        "view" => VIEW_DIR . "forum/cgu.php",
        "meta_description" => "Conditions Générales d’Utilisation"
    ];
}

}

