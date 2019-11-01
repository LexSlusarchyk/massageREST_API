<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get All Procedures
$app->get('/api/procedures', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Procedures ORDER BY id DESC";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedures = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedures);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Get Procedure
$app->get('/api/procedures/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Procedures WHERE id = $id ";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchObject();;
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Create Procedure
$app->post('/api/admin/procedures/add', function (Request $request, Response $response) {
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $duration = $request->getParam('duration');
    $price = $request->getParam('price');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');
    $category_id = $request->getParam('category_id');
    $videoUrl = $request->getParam('videoUrl');

    $sql = "INSERT INTO Procedures (title, titleEn, duration, price, text, textEn, image, category_id, videoUrl) VALUES (:title, :titleEn, :duration, :price, :text, :textEn, :image, :category_id, :videoUrl)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':videoUrl', $videoUrl);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update Procedure
$app->put('/api/admin/procedures/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $duration = $request->getParam('duration');
    $price = $request->getParam('price');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');
    $category_id = $request->getParam('category_id');
    $videoUrl = $request->getParam('videoUrl');


    $sql = "UPDATE Procedures SET
                title = :title,
                titleEn = :titleEn,
                duration = :duration,
                price = :price,
                text = :text,
                textEn = :textEn,
                image = :image,
                category_id = :category_id,
                videoUrl = :videoUrl
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':videoUrl', $videoUrl);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Updated"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Delete Procedure
$app->delete('/api/admin/procedures/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM Procedures WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Deleted"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Get Procedures by Category Id
$app->get('/api/procedures/category/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $allCategories = getAllCategories();
    $children = getChildren($allCategories, $id);

    if(empty($children)) {
        $sql = "SELECT * FROM Procedures WHERE category_id = $id ORDER BY id DESC";
    } else {
      $childrenComaSeparated = implode(', ', $children);
      $sql = "SELECT * FROM Procedures WHERE category_id IN ($childrenComaSeparated) ORDER BY id DESC";
    }

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedures = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedures);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

function getAllCategories () {
  $sql = "SELECT * FROM Categories";
  $db = new db();
  $db = $db->connect();
  $stmt = $db->query($sql);
  $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

  $db = null;
  return $categories;
}

function getChildren ($categories, $categoryId) {
  $children = array();

  for ($i = 0; $i < count($categories); $i++) {
      if ($categories[$i]->parent_id === $categoryId) {
          array_push($children, $categories[$i]->id);
          $subCategories = getChildren($categories, $categories[$i]->id);
          $children = array_merge($children, $subCategories);
      }
  }

  return $children;
}
