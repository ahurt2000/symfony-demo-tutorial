<?php

namespace App\Controller;

use App\Model\CategoryDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category', methods: ['GET','HEAD'])]
    public function index(): Response
    {
        $categories = [new CategoryDto(id:1, name:"Categoria1"), new CategoryDto(2,"Categoria2")];

        return new JsonResponse($categories, Response::HTTP_CREATED);
    }
}
