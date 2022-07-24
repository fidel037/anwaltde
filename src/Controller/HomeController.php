<?php

namespace App\Controller;

use App\Service\ImageService;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public $serviceClass = ImageService::class;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $query = $request->query->all();
        if (empty($query)) {
            return $this->showImages();
        }
        $this->service->process($query);
        unset($_GET);
        header('Location: /');

    }

    public function showImages()
    {
        $paths = $this->service->getImagePaths();
        return $this->render('index', ['images' => $paths]);
    }
}
