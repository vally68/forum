<?php
$categories = $result["data"]['categories'];
?>

<h1>Liste des catégories</h1>

<?php foreach ($categories as $category): ?>
    <p>
        <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>">
            <?= htmlspecialchars($category->getName()) ?>
        </a>

        <?php if (\App\Session::isAdmin() || \App\Session::isModerator()): ?>
            <!-- Bouton suppression -->
            <a href="index.php?ctrl=forum&action=deleteCategory&id=<?= $category->getId() ?>"
               style="color:red; margin-left:10px;"
               onclick="return confirm('Supprimer cette catégorie ?');">
               ❌ Supprimer
            </a>

            <!-- Formulaire de modification -->
            <form action="index.php?ctrl=forum&action=updateCategory&id=<?= $category->getId() ?>" 
                  method="post" 
                  style="display:inline-block; margin-left:10px;">
                <input type="text" name="name" 
                       value="<?= htmlspecialchars($category->getName()) ?>" 
                       required style="padding:3px;">
                <button type="submit" style="padding:3px 6px;">✏️ Modifier</button>
            </form>
        <?php endif; ?>
    </p>
<?php endforeach; ?>


<!--  Formulaire d'ajout de catégorie (ADMIN + MODO uniquement) -->
<?php if (\App\Session::isAdmin() || \App\Session::isModerator()): ?>
    <h2>Ajouter une catégorie</h2>

    <form action="index.php?ctrl=forum&action=addCategory" method="post">
        <label for="name">Nom de la catégorie :</label>
        <input type="text" id="name" name="name" required>

        <button type="submit" name="submit">Ajouter</button>
    </form>
<?php endif; ?>
