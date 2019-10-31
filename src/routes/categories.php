<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get All Categories
$app->get('/api/categories', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Categories";

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

//Get Category Children
$app->get('/api/categories/children/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Categories WHERE parent_id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Get Favorite Categories
$app->get('/api/categories/favorite', function (Request $request, Response $response) {

    $sql = "SELECT * FROM Categories WHERE favorite = '1'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Get Category
$app->get('/api/categories/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM Categories WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Create Category
$app->post('/api/admin/categories/add', function (Request $request, Response $response) {
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $image = $request->getParam('image');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $parent_id = $request->getParam('parentId');

    $sql = "INSERT INTO Categories (title, titleEn, image, text, textEn, parent_id) VALUES (:title, :titleEn, :image, :text, :textEn, :parent_id)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':parent_id', $parent_id);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update Category
$app->put('/api/admin/categories/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $image = $request->getParam('image');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $parent_id = $request->getParam('parentId');
    $favorite = $request->getParam('favorite');


    $sql = "UPDATE Categories SET
                title = :title,
                titleEn = :titleEn,
                image = :image,
                text = :text,
                textEn = :textEn,
                parent_id = :parent_id,
                favorite = :favorite
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':parent_id', $parent_id);
        $stmt->bindParam(':favorite', $favorite);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Updated"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Delete Category
$app->delete('/api/admin/categories/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM Categories WHERE id = $id";

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
