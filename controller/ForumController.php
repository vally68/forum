<?php
namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;

class ForumController extends AbstractController implements ControllerInterface{

    public function index() {
        
        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "DESC"]);

        // le controller communique avec la vue "listCategories" (view) pour lui envoyer la liste des catégories (data)
        return [
            "view" => VIEW_DIR."forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }

public function listTopicsByCategory($id) {

    $categoryManager = new CategoryManager();
    $category = $categoryManager->findOneById($id);

    // Vérification : si la catégorie n'existe pas, on retourne à la liste des catégories
    if (!$category) {
        header("Location: index.php?ctrl=forum&action=index");
        exit;
    }

    $topicManager = new TopicManager();
    $topics = $topicManager->findTopicsByCategory($id);

    return [
        "view" => VIEW_DIR."forum/listTopics.php",
        "meta_description" => "Liste des topics par catégorie : ".$category->getName(),
        "data" => [
            "category" => $category,
            "topics" => $topics
        ]
    ];
}

public function listMessage($id)
{
    $topicManager = new \Model\Managers\TopicManager();
    $messageManager = new \Model\Managers\MessageManager();

    // ID reçu : ID du topic
    $topic = $topicManager->findOneById($id);
    $messages = $messageManager->findMessagesByTopic($id);

    return [
        "view" => VIEW_DIR."forum/listMessage.php",
        "meta_description" => "Messages du topic : " . $topic->getTitle(),
        "data" => [
            "topic" => $topic,          // ← OBLIGATOIRE
            "messages" => $messages     // ← OBLIGATOIRE
        ]
    ];
}

public function addCategory()
{
    $this->restrictTo(["Admin", "Moderator"]);

    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$name) {
        \App\Session::addFlash("error", "Nom de catégorie invalide.");
        $this->redirectTo("forum", "index");
    }

    $manager = new \Model\Managers\CategoryManager();
    $manager->add(["name" => $name]);

    \App\Session::addFlash("success", "Catégorie ajoutée !");
    $this->redirectTo("forum", "index");
}

public function deleteCategory($id)
{
    $this->restrictTo(["Admin", "Moderator"]);

    $manager = new \Model\Managers\CategoryManager();
    $manager->delete($id);

    \App\Session::addFlash("success", "Catégorie supprimée !");
    $this->redirectTo("forum", "index");
}


}