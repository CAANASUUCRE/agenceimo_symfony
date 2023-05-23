<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminPropertyController extends AbstractController
{
    
    private PropertyRepository $repo;
    private EntityManagerInterface $em;
    
    public function __construct(PropertyRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }


    /**
     * @Route("/admin" , name= "admin.property.index")
     * 
     */
    public function index()
    {
        $properties = $this->repo->findAll();

        return $this->render('admin/property/index.html.twig',compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request) 
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success','Crée avec succés !');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/admin/poperty/{id}" , name="admin.property.edit", methods="GET|POST")
     */
    public function edit($id, Request $request)
    {
        
        
        $property = $this->repo->find($id);

        /*$option = new Option();
        $property->addOption($option);*/

        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success','Modifié avec succés !');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/poperty/delete/{id}" , name="admin.property.delete")
     */
    public function delete($id, Request $request) 
    {
        if($this->isCsrfTokenValid('delete'.$id , $request->get('_token'))) {
            //return new Response('Suppression');
            $property = $this->repo->find($id);
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success','Supprimé avec succés !');
        }
        
        return $this->redirectToRoute('admin.property.index');     
    }
}