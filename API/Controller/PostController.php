<?php
// PostController.php
// Correcciones: rutas a Model/ y Views/post/ para coincidir con estructura del proyecto
require_once __DIR__ . '/../Model/ApiPostService.php';

class PostController
{
    private ApiPostService $service;

    public function __construct()
    {
        $this->service = new ApiPostService();
    }

    public function index()
    {
        $posts = $this->service->getPosts();
        // preparar paginación o slicing si es necesario
        require __DIR__ . '/../Views/post/index.php';
    }

    public function show($id)
    {
        $post = $this->service->getPost((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Post no encontrado";
            return;
        }
        // Si más adelante se añade la vista show.php colócala en Views/post/show.php
        require __DIR__ . '/../Views/post/show.php';
    }

    public function store()
    {
        $input = $_POST;
        // validar $input -> si falla, redirigir con errores
        $created = $this->service->createPost($input);
        // redirigir o mostrar vista
        header('Location: /posts');
        exit;
    }

    // update, delete similares...
}
