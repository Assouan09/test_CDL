<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use App\Entity\Auteur;
use App\Entity\Category;
use App\Entity\Livre;
use App\Classe\Search;
use App\Form\SearchType;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $livres = $this->entityManager->getRepository(Livre::class)->findAll();

        $search = new Search();
        $searchform = $this->createForm(SearchType::class, $search);
        $searchform->handleRequest($request);

        if($searchform->isSubmitted() && $searchform->isValid()){
            $livres = $this->entityManager->getRepository(Livre::class)->findWithSearch($search);
        }

        return $this->render('home/index.html.twig', [
            'livres' => $livres,
            'search' => $searchform->createView()
        ]);
    }

    /**
     * @Route("/edit-livre/{id}", name="edit_livre")
     */
    public function edit(Request $request, $id)
    {
        $livre = $this->entityManager->getRepository(Livre::class)->findOneById($id);
        
        $form = $this->createForm(BookType::class, $livre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('add_book/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete-livre/{id}", name="delete_livre")
     */
    public function delete($id)
    {
        $livre = $this->entityManager->getRepository(Livre::class)->findOneById($id);

        if($livre) {
            $this->entityManager->remove($livre);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('home');

    }
}
