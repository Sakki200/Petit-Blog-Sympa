<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles/{id}', name: 'article_show', methods: ["GET"])]
    public function show(ArticleRepository $articles, $id): Response
    {
        $specifyArticle = $articles->findOneBy(['id' => $id],);
        $user = $specifyArticle->getAuthor();

        return $this->render('article/show.html.twig', [
            'article' => $specifyArticle,
            'authorArticles' => $articles->findBy(['author' => $user], ['createdAt' => 'DESC'], 3)
        ]);
    }
    #[Route('/articles', name: 'app_article')]
    public function allArticles(ArticleRepository $articles): Response
    {
        return $this->render('article/all.html.twig', [
            'allArticles' => $articles->findBy(
                [],
                ['createdAt' => 'DESC']
            )

        ]);
    }
}
