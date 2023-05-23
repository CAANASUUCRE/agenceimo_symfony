<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    private PropertyRepository $repo;
    private EntityManagerInterface $em;

    public function __construct(PropertyRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     */
    public function index( PaginatorInterface $paginator, Request $request): Response
    {

        /*$property = new Property();
        $property->setTitle('Mon premier bien')
            ->setPrice(200000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription('une petite description')
            ->setSurface(60)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity("Montpellier")
            ->setAddress('15 boulevard Gambetta')
            ->setPostalCode(34000);
        //$em = $this->getDoctrine()->getManager();
        $em = $doctrine->getManager();
        $em->persist($property);
        $em->flush();*/

        /*$repo = $doctrine->getRepository(Property::class);
        dump($repo);*/

        //$property = $this->repo->findAllVisible();
        //$property[0]->setSold(false);
        //dump($property);
        //$this->em->flush();
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);


        $properties = $paginator->paginate(
            $this->repo->findAllVisibleQuery($search),
            $request->query->getInt('page',1),
            12
        );

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{id}", name="property.show")
     */
    public function show($id): Response
    {
        $property = $this->repo->find($id);

        return $this->render('property/show.html.twig',[
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
}
