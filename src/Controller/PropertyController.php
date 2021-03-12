<?php
namespace App\Controller;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController {


    /**
     * @var PropertyRepository
     */
    private $repository;

    // on injecte le service mieux que procéder comme ça:
    //  $repository = $this->getDoctrine()->getRepository(PropertyRepository::class);
    // notez bien aussi qu'avec symfony l'injection de services peut se faire également dans les méthodes et non seulement dans le constructeur .

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response{
        //$property = $this->repository->findAllVisible(); juste pour se familiariser avec le repository :)
        //dump($property);
        //$properties = $this->repository->findAllVisible();

        // Créer une entité qui va répresenter notre recherche. (prix maximale, nombre de pièces ...) :) c'est fais (PropertySearch)
        $search = new PropertySearch();
        // Créer un formulaire :) c'est fait (PropertySearchType)
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        // Gérer le traitement dans le controller

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig', [
            'current_view' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    // Avec Symfony la magie se continue et on peut aussi injecter les dependences necessaires. soit par l'inection d'une instance
    // de l'entité Propriety
    public function show(Property $property, string $slug): Response{
        //$property = $this->repository->find($id);
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_view' => 'properties'
        ]);
    }

}
