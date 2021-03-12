<?php
namespace App\Controller;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController {

    /**
     * @var Environment
     */

    // On peut procéder comme suit pour beneficier de service "twig" ou bien extendre la classe AbstractController
    // lui comprends l'objet "Container" source de tous les services actifs pour notre application.
    /*private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }*/


    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
    // Puisque le HomeController est normalement comprend qu'une seule methode on peut injecter notre service directement
    // à notre methode vu que symfony nous le permis.

    public function index(PropertyRepository $repository): Response{
        $properties = $repository->findLatest();
        //dump($properties);
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }
}