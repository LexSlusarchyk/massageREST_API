<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//$config['db']['host']   = 'localhost';
//$config['db']['user']   = 'root';
//$config['db']['pass']   = '';
//$config['db']['dbname'] = 'massage';

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name =$request->getAttribute('name');
    $response->getBody()->write("Hello", $name);

    return $response;
});

//Procedures Routes
require '../src/routes/procedures.php';

// Run app
$app->run();
