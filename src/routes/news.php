<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get All News
$app->get('/api/news', function (Request $request, Response $response) {
    $sql = "SELECT * FROM News ORDER BY id DESC";

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

//Get Article
$app->get('/api/news/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM News WHERE id = $id ";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $procedure = $stmt->fetchObject();
        $db = null;
        echo json_encode($procedure);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Create Article
$app->post('/api/admin/news/add', function (Request $request, Response $response) {
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');

    $sql = "INSERT INTO News (title, titleEn, text, textEn, image) VALUES (:title, :titleEn, :text, :textEn, :image)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Added"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update Article
$app->put('/api/admin/news/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $titleEn = $request->getParam('titleEn');
    $text = $request->getParam('text');
    $textEn = $request->getParam('textEn');
    $image = $request->getParam('image');


    $sql = "UPDATE News SET
                title = :title,
                titleEn = :titleEn,
                text = :text,
                textEn = :textEn,
                image = :image
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':titleEn', $titleEn);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':textEn', $textEn);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "Updated"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Delete Article
$app->delete('/api/admin/news/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM News WHERE id = $id";

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
