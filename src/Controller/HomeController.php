<?php

namespace App\Controller;

use App\Form\PropertySearchType;
use App\Form\SearchWordType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository, Request $request): Response
    {
        $properties = $repository->findLatest();
        $form = $this->createForm(SearchWordType::class);
        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $properties = $repository->search($search->get('mots')-> getData());
        }

        return $this->render('pages/home.html.twig', [
            'properties' => $properties,
            'formSearch' => $form->createView()
        ]);
    }
}