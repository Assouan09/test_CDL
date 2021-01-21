<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BookType;
use App\Entity\Livre;
use App\Entity\Category;

class AddBookController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/ajouter-livre", name="add_book")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(BookType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form->get('category')->getData()->getCategory());

            // Catégorie
            $category = $form->get('category')->getData()->getCategory();
            $id = $this->entityManager->getRepository(Category::class)->findOneByCategory($category);

            // Auteur
            $auteur = $form->get('auteur')->getData();
            $this->entityManager->persist($auteur);

            // Livre
            $livre = new Livre();

            $livre->setLivre($form->get('livre')->getData());
            if($form->get('date')->getData())
                $livre->setDate($form->get('date')->getData());
            else
                $livre->setDate(null);
        
            $livre->setAuteur($auteur);
            $livre->setCategory($id);
            $this->entityManager->persist($livre);

            $this->entityManager->flush();
            return $this->redirectToRoute('home');
        }


        return $this->render('add_book/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
