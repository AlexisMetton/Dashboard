<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\CategoriesRepository;
use App\Repository\CategoryRepository;
use App\Repository\PropertyRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;


class AdminPropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */

    private $repository;

    /**
     * @var ObjectManager
     */

    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     */

    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }


    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request,FileUploader $fileUploader,EntityManagerInterface $entityManager): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
       
       

        if($form->isSubmitted() && $form->isValid())
        { 
          //  $property->setBrochureFilename(
              //  new File($this->getParameter('brochures_directory').$property->getBrochureFilename()));
         
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
            
                $brochureFileName = $fileUploader->upload($brochureFile);
                $property->setimage($brochureFileName);

            }

            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Achat créé avec succès');
            return $this->redirectToRoute('admin.property.index',[], Response::HTTP_SEE_OTHER);

        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]
        );
       
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit")
     */

    public function edit(Property $property, Request $request, FileUploader $fileUploader,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        { 

            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $property->setimage($brochureFileName);

                // Move the file to the directory where brochures are stored
             /*   try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $property->setImage($newFilename);*/
            }

            

            $this->em->flush();
            $this->addFlash('success', 'Achat modifié avec succès');
            return $this->redirectToRoute('admin.property.index',[], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]
    );
    }

    /**
     * @Route("/admin/property/{id}/delete", name="admin.property.delete")
     * @param Property $property
     * @return \symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token')))
        {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Achat supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }

    /**
     * @Route("/admin/stats", name="stats")
     */

     public function statistiques(CategoryRepository $categRepo, PropertyRepository $proprepo){
        //On va chercher toutes les catégories
        $categories = $categRepo->findAll();

        $categNom = [];
        $categCount =[];
        // on demonte les données pour les séparer tel qu'attendu par chartJS
        foreach($categories as $categorie){
            $categNom[] = $categorie->getCategories();
            $categCount[] = count($categorie->getidCategorie());
        }


        // ON va chercher le nombre de produit publiées par date
        $propertys = $proprepo->countByDate();

        $dates = [];
        $propertyCount = [];

         // on demonte les données pour les séparer tel qu'attendu par chartJS

        foreach($propertys as $property){
            $dates[] = $property['date_achat'];
            $propertyCount[] = $property['count'];
        }



        return $this-> render('admin/stats.html.twig',[
            'categNom' => json_encode($categNom),
            'categCount' => json_encode($categCount),
            'dates' => json_encode($dates),
            'propertyCount' => json_encode($propertyCount)
        ]);
     }

}