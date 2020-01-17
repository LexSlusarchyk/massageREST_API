<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/../../public/uploads';

$app->post('/api/uploads', function (Request $request, Response $response) {
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();

//     handle single input with single file upload
    $uploadedFile = $uploadedFiles['image'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedFile($directory, $uploadedFile);
        echo "\"$filename\"";
    }
});

$app->post('/api/uploads/thumbnail', function (Request $request, Response $response) {
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();

//     handle single input with single file upload
    $uploadedFile = $uploadedFiles['image'];
    $name = $request->getParam('name');
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedThumbnail($directory, $uploadedFile, $name);
        echo "\"$filename\"";
    }
});

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

function moveUploadedThumbnail($directory, UploadedFile $uploadedFile, $name)
{
    $filename = $name;

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}
