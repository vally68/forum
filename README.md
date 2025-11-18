# 🗨️ Forum PHP – Projet MVC

Ce projet est un forum développé en PHP en utilisant une architecture MVC conçue sans framework.  
Il permettra aux utilisateurs de s’inscrire, se connecter, créer des topics, publier des messages et interagir dans différentes catégories.

---

## 🚀 Fonctionnalités

### 👤 Utilisateurs
- Inscription avec validation et sécurisation des mots de passe
- Connexion et déconnexion sécurisées
- Affichage du profil public d’un utilisateur
- Modification du profil personnel
- Système de rôles :
  - **User**
  - **Moderator**
  - **Admin**
- Permissions gérées via le rôle (RBAC)

---

### 🗂️ Catégories
- Affichage de toutes les catégories
- Ajout de catégorie *(Admin & Moderator)*
- Suppression de catégorie *(Admin & Moderator)*

---

### 🧵 Topics
- Liste des topics par catégorie
- Création de topic *(utilisateur connecté uniquement)*
- Suppression :
  - par son auteur
  - par **Admin** / **Moderator** pour tous les topics(en cas de débordement)

---

### 💬 Messages
- Affichage de tous les messages d’un topic
- Création d’un message (utilisateur connecté)
- Messages triés par date
-Edition du message
-Suppression du message par son auteur ou admin/modérateur

---

## 🗄️ Base de données

Le projet se base sur une structure MySQL contenant les tables suivantes :

- **user**
- **category**
- **topic**
- **message**
- **moderation**

Chaque table est reliée correctement via des clés étrangères respectant la logique :

- 1 user → plusieurs topics  
- 1 topic → plusieurs messages  
- 1 catégorie → plusieurs topics

---

## 🧱 Architecture du projet

/app
DAO.php
Entity.php
Manager.php
Session.php

/controller
ForumController.php
SecurityController.php
HomeController.php

/model
/entities
User.php
Topic.php
Message.php
Category.php
Moderation.php

/managers
    UserManager.php
    TopicManager.php
    MessageManager.php
    CategoryManager.php
    ModerationManager.php

/view
/forum
/security
/home

/public
/css
/js

index.php


---

## 🔧 Installation

### 1️⃣ Cloner le projet

### bash
git clone https://github.com/ton-compte/ton-repo.git
cd ton-repo

2️⃣ Configurer la base de données

Importer le fichier SQL correspondant à la structure disponible dans le projet
ou créer la base selon le MCD fourni.

Modifier les identifiants MySQL dans :

app/DAO.php

3️⃣ Lancer le projet

Placer le dossier dans :

C:/wamp64/www/nomDuProjet

Puis ouvrir :

http://localhost/nomDuProjet/index.php

👩‍💻 Auteur

David Balga
📧 test@test.com


Projet réalisé dans le cadre d’un projet d’étude.
📄 Licence

Projet libre d’utilisation à des fins pédagogiques.
Toute utilisation commerciale est interdite sans autorisation.
