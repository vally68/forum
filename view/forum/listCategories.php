<?php
$categories = $result["data"]['categories'];
$user = \App\Session::getUser();
?>

<section class="categories-page">
  <h1>CATEGORIES</h1>

  <?php if (!empty($categories)): ?>
    <div class="categories-container">
      <?php foreach ($categories as $category): ?>
        <div class="category-wrapper">
          <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"
             class="category-item">
            <span class="category-name"><?= htmlspecialchars($category->getName()) ?></span>
          </a>

          <?php if (\App\Session::isAdmin() || \App\Session::isModerator()): ?>
            <div class="category-actions">
              <a href="index.php?ctrl=forum&action=deleteCategory&id=<?= $category->getId() ?>"
                 class="btn-delete"
                 onclick="return confirm('Supprimer cette catégorie ?');">❌</a>

              <form action="index.php?ctrl=forum&action=updateCategory&id=<?= $category->getId() ?>"
                    method="post"
                    class="edit-category-form">
                <input type="text" name="name"
                       value="<?= htmlspecialchars($category->getName()) ?>"
                       required>
                <button type="submit" class="btn-edit">✏️</button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Aucune catégorie trouvée.</p>
  <?php endif; ?>
</section>

<?php if (\App\Session::isAdmin() || \App\Session::isModerator()): ?>
  <section class="add-category">
    <h2>Ajouter une catégorie</h2>
    <form action="index.php?ctrl=forum&action=addCategory" method="post">
      <label for="name">Nom :</label>
      <input type="text" id="name" name="name" required>
      <button type="submit">Ajouter</button>
    </form>
  </section>
<?php endif; ?>
