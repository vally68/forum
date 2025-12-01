<?php

namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\MessageManager;

class ForumController extends AbstractController implements ControllerInterface
{
    public function index()
    {

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

    public function listTopicsByCategory($id)
    {

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
        $topic = $topicManager->findOneById($id);

        //  Ajout : vérifier si le topic existe
        if (!$topic) {
            \App\Session::addFlash("error", "Message introuvable, y'a un souci quelque part -_- ."); //message flash(reste quelque secondes)
            $this->redirectTo("forum", "index"); //redirection
        }

        $messages = $messageManager->findMessagesByTopic($id);

        return [
            "view" => VIEW_DIR."forum/listMessage.php",
            "meta_description" => "Messages du topic : " . $topic->getTitle(),
            "data" => [
                "topic" => $topic,
                 "messages" => $messages
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

    public function updateCategory($id)
    {
        $this->restrictTo(["Admin", "Moderator"]);

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$name) {
            \App\Session::addFlash("error", "Le nom de la catégorie est invalide.");
            $this->redirectTo("forum", "index");
        }

        $manager = new \Model\Managers\CategoryManager();
        $manager->update($id, ["name" => $name]);

        \App\Session::addFlash("success", "Catégorie mise à jour avec succès !");
        $this->redirectTo("forum", "index");
    }

    public function addTopic($categoryId)
    {
        // 1. Vérifier que l’utilisateur est connecté
        $user = Session::getUser();
        if (!$user) {
            Session::addFlash("error", "Vous devez être connecté pour créer un topic.");
            $this->redirectTo("security", "login");
        }

        // 2. Récupérer les données du formulaire
        $title   = filter_input(INPUT_POST, 'title');
        $content = filter_input(INPUT_POST, 'content');

        if (!$title || !$content) {
            Session::addFlash("error", "Tous les champs doivent être remplis.");
            $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
        }

        // 3. Créer le topic
        $topicManager   = new TopicManager();
        $messageManager = new MessageManager();

        $topicId = $topicManager->add([
            "title"        => $title,
            "creationDate" => date("Y-m-d H:i:s"),
            "user_id"      => $user->getId(),
            "id_category"  => $categoryId
        ]);

        // 4. Créer le premier message lié à ce topic
        $messageManager->add([
             "texte"        => $content,
             "creationDate" => date("Y-m-d H:i:s"),
             "topic_id"     => $topicId
        ]);

        // 5. Message + redirection
        Session::addFlash("success", "Topic créé avec succès !");
        $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
    }

    public function updateTopic($id)
    {
        $user = \App\Session::getUser();
        if (!$user) {
            \App\Session::addFlash("error", "Vous devez être connecté pour modifier un topic.");
            $this->redirectTo("security", "login");
        }

        $topicManager = new \Model\Managers\TopicManager();
        $topic = $topicManager->findOneById($id);

        if (!$topic) {
            \App\Session::addFlash("error", "Topic introuvable.");
            $this->redirectTo("forum", "index");
        }

        //  récupérer la catégorie, même si c’est juste un id
        $category = $topic->getCategory();
        $categoryId = is_object($category) ? $category->getId() : $category;

        // Vérification des droits
        if (
            !in_array($user->getStatut(), ['Admin', 'Moderator'], true)
            && $user->getId() !== $topic->getUser()->getId()
        ) {
            \App\Session::addFlash("error", "Vous n'avez pas la permission de modifier ce topic.");
            $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
        }

        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$title) {
            \App\Session::addFlash("error", "Le titre est invalide.");
            $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
        }

        $topicManager->update($id, ["title" => $title]);

        \App\Session::addFlash("success", "Topic modifié avec succès !");
        $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
    }



    public function deleteTopic($id)
    {
        // sécurité : seulement via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            \App\Session::addFlash("error", "Suppression non autorisée.");
            $this->redirectTo("forum", "index");
        }

        // restriction par rôles (ton helper utilise déjà les labels de l'enum)
        $this->restrictTo(["Admin", "Moderator"]);

        $topicManager = new \Model\Managers\TopicManager();
        $topic = $topicManager->findOneById($id);

        if (!$topic) {
            \App\Session::addFlash("error", "Le topic n'existe pas ou a déjà été supprimé.");
            $this->redirectTo("forum", "index");
        }

        // récupérer l'id de catégorie pour revenir à la bonne page
        $category = $topic->getCategory();
        $categoryId = is_object($category) ? $category->getId() : $category;

        $topicManager->delete($id);

        \App\Session::addFlash("success", "Topic supprimé avec succès !");
        $this->redirectTo("forum", "listTopicsByCategory", $categoryId);
    }




    public function addMessage()
    {
        // 1️ Vérifie que l'utilisateur est connecté
        $user = Session::getUser();
        if (!$user) {
            Session::addFlash("error", "Vous devez être connecté pour répondre à un topic.");
            $this->redirectTo("security", "login");
        }

        // 2️ Récupère les données du formulaire
        $topicId = filter_input(INPUT_POST, 'id_topic', FILTER_SANITIZE_NUMBER_INT);
        $texte   = filter_input(INPUT_POST, 'texte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // 3️ Vérifie la validité
        if (!$topicId || !$texte) {
            Session::addFlash("error", "Le message ne peut pas être vide.");
            $this->redirectTo("forum", "listMessage", $topicId);
        }

        // 4️ Ajoute le message
        $messageManager = new MessageManager();

        $messageManager->add([
            "texte"        => $texte,
            "creationDate" => date("Y-m-d H:i:s"),
            "topic_id"     => $topicId
        ]);

        // 5️ Redirige vers le topic après ajout
        Session::addFlash("success", "Message ajouté avec succès !");
        $this->redirectTo("forum", "listMessage", $topicId);
    }

    public function updateMessage($id)
    {
        $user = \App\Session::getUser();
        if (!$user) {
            \App\Session::addFlash("error", "Vous devez être connecté pour modifier un message.");
            $this->redirectTo("security", "login");
        }

        $messageManager = new \Model\Managers\MessageManager();
        $message = $messageManager->findOneById($id);

        if (!$message) {
            \App\Session::addFlash("error", "Message introuvable.");
            $this->redirectTo("forum", "index");
        }

        // Récupération du topic pour redirection
        $topic = $message->getTopic();
        $topicId = is_object($topic) ? $topic->getId() : $topic;

        // Vérification des droits
        if (
            !in_array($user->getStatut(), ['Admin', 'Moderator'], true)
            && $user->getId() !== $message->getUser()->getId()
        ) {
            \App\Session::addFlash("error", "Vous n'avez pas la permission de modifier ce message.");
            $this->redirectTo("forum", "listMessage", $topicId);
        }

        $texte = filter_input(INPUT_POST, "texte", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$texte) {
            \App\Session::addFlash("error", "Le contenu du message est invalide.");
            $this->redirectTo("forum", "listMessage", $topicId);
        }

        $messageManager->update($id, ["texte" => $texte]);

        \App\Session::addFlash("success", "Message modifié avec succès !");
        $this->redirectTo("forum", "listMessage", $topicId);
    }

    public function deleteMessage($id)
    {
        $user = \App\Session::getUser();
        if (!$user) {
            \App\Session::addFlash("error", "Vous devez être connecté pour supprimer un message.");
            $this->redirectTo("security", "login");
        }

        $messageManager = new \Model\Managers\MessageManager();
        $message = $messageManager->findOneById($id);

        if (!$message) {
            \App\Session::addFlash("error", "Message introuvable.");
            $this->redirectTo("forum", "index");
        }

        $topic = $message->getTopic();
        $topicId = is_object($topic) ? $topic->getId() : $topic;

        if (
            !in_array($user->getStatut(), ['Admin', 'Moderator'], true)
            && $user->getId() !== $message->getUser()->getId()
        ) {
            \App\Session::addFlash("error", "Vous n'avez pas la permission de supprimer ce message.");
            $this->redirectTo("forum", "listMessage", $topicId);
        }

        $messageManager->delete($id);

        \App\Session::addFlash("success", "Message supprimé avec succès !");
        $this->redirectTo("forum", "listMessage", $topicId);
    }


}
