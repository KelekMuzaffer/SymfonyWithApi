<?php

namespace App\Controller;


use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// Controller et template pages.html.twig creer grace à la commande : php bin/console make:controller dans le terminal
class HomeController extends AbstractController
{

    /**
     * Présente tous les articles dans la pages
     * @Route(path="/", name="home")
     * @param HttpClientInterface $httpClient
     * @param Request $request
     * @param TokenInterface|null $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function home(HttpClientInterface $httpClient,Request $request)
    {
        $article = $httpClient->request('GET','https://localhost:8001/api/articles');

        return $this->render('pages/home.html.twig', [
            'controller_name' => 'HomeController',
            'article' => $article->toArray()
        ]);
    }

    /**
     * @Route(path="/show/{id}", name="article_show")
     */
    public function show(HttpClientInterface $httpClient,$id)
    {
      $response = $httpClient->request('GET','https://localhost:8001/api/articles/'.$id);

       return $this->render('pages/show.html.twig',[
           'articleById' => $response->toArray()
       ]);
    }


    /**
     * @Route("/article/new",name="newArticle")
     */
    public function createArticle(HttpClientInterface $httpClient)
    {

        $article = $httpClient->request('POST','https://localhost:8001/api/articles');


       $this->redirectToRoute('/');
    }
}
