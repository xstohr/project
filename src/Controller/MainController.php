<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('list.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/create", name="create_article", methods={"POST"})
     *
     * @return Response
     */
    public function createArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article was created!');

            return $this->redirectToRoute('index');
        }

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     *
     * @return Response
     */
    public function editArticle(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
        }

        $entityManager->persist($article);
        $entityManager->flush();
        $this->addFlash('success', 'Article was changed');

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/delete/{id}", name="delete_article")
     * 
     * @return RedirectResponse
     */

    public function removeArticle($id): RedirectResponse
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        $this->articleRepository->removeArticle($article);

        $this->addFlash('success', 'Article was deleted');

        return $this->redirectToRoute('index');
    }

}