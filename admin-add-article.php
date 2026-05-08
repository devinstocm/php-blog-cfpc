<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/Enums/Role.php';
require_once 'app/helpers.php';

// blogprocedural
if ($_SESSION['auth']['role'] !== Role::ADMIN->value) {
  header('Location: index.php');
  exit();
}
if(isset($_POST['add-article'])){
  $title =clean_input((string)($_POST['title'] ?? ''));
  $slug = createSlug($title);
  $introduction =clean_input((string)($_POST['introduction'] ?? ''));
  $content = $_POST['content'];
  $imagePath = null;

  // Validation des données
    if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
        $error = 'Veuillez remplir tous les champs obligatoires du formulaire !';
  
        $query = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
        $query->execute([':slug' => $slug]);
        if($query->fetchColumn()>0){
           $error = " Le slug '$slug' existe déjà. Vueillez en choisir un autre";
        }else{
          $query =$pdo->prepare('INSERT INTO articles
          (title, slug, introduction, content, image, created_at)
          VALUES (:title, :slug, :introduction, :content,:image,NOW())');
           $query->execute([
                'title' => $title,
                'slug' => $slug,
                'introduction' => $introduction,
                'content' => $content,
                'image' => $imagePath,
            ]);  
             if($query->rowCount() >0) {
              $_SESSION['success']['update'] = 'Articles créé avec succès';
              header('Location: admin-list-article.php');
                exit();
            } else {
                $error = "Erreur lors de la création de l'article";
            }
          }
        }
}

$pageTitle = 'Add Articles';
ob_start();
require_once 'resources/views/admin/articles/admin-add-article_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
