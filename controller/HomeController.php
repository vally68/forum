<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\UserManager;
use App\Session;

class HomeController extends AbstractController implements ControllerInterface {

  public function index()
{
    $messageManager = new \Model\Managers\MessageManager();
    $lastMessages = $messageManager->findLastMessages(10);

    return [
        "view" => VIEW_DIR . "home.php",
        "meta_description" => "Page d'accueil du forum",
        "data" => [
            "lastMessages" => $lastMessages
        ]
    ];
}

// "serpent" est un complément qui renvoie la même vue + data
public function serpent()
{
    return $this->index();
}

        
    public function users(){
        $this->restrictTo("Admin");

        $manager = new UserManager();
        $users = $manager->findAll(['creationDate', 'DESC']);

        return [
            "view" => VIEW_DIR."forum/users.php",
            "meta_description" => "Liste des utilisateurs du forum",
            "data" => [ 
                "users" => $users 
            ]
        ];
    }

public function editUser($id)
{
    $this->restrictTo("Admin");

    $manager = new UserManager();
    $user = $manager->findOneById($id);

    if (!$user) {
        Session::addFlash("error", "Utilisateur introuvable.");
        $this->redirectTo("home", "users");
    }

    return [
        "view" => VIEW_DIR."security/editUser.php",
        "meta_description" => "Modifier un utilisateur",
        "data" => [
            "user" => $user
        ]
    ];
}

public function updateUser($id)
{
    $this->restrictTo("Admin");

    $nick = filter_input(INPUT_POST, 'nickName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$nick || !$email || !$statut) {
        Session::addFlash("error", "Champs invalides.");
        $this->redirectTo("home","users");
    }

    $manager = new UserManager();
    $manager->updateUser($id, [
        "nickName" => $nick,
        "email" => $email,
        "statut" => $statut
    ]);

    Session::addFlash("success", "Utilisateur mis à jour.");
    $this->redirectTo("home","users");
}


public function deleteUser($id)
{
    $this->restrictTo("Admin");

    $manager = new UserManager();
    $manager->deleteUser($id);

    Session::addFlash("success", "Utilisateur supprimé.");
    $this->redirectTo("home","users");
}

// public function serpent()
// {
//     $messageManager = new \Model\Managers\MessageManager();
//     $lastMessages = $messageManager->findLastMessages(10);

//     return [
//         "view" => VIEW_DIR . "home.php",
//         "meta_description" => "Accueil du forum",
//         "data" => [
//             "lastMessages" => $lastMessages
//         ]
//     ];
// }


}
