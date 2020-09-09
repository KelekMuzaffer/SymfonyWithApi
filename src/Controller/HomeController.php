<?php

namespace App\Controller;


use App\Form\NewArticleType;
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
    public function createArticle(HttpClientInterface $httpClient, Request $request)
    {
        $token = $request->cookies->get('BEARER');
        $form = $this->createForm(NewArticleType::class);
        $form->handleRequest($request);

        $error = null;
        if ($request->isMethod('POST'))
        {
            try {
                $formdata = $form->getData();
                $article = $httpClient->request('POST','https://localhost:8001/api/articles',
                    [
                        'headers' => [
                            'Authorization' => 'Bearer '.$token,
                        ],

                        'json' => [ 'name' => $formdata['name'], 'content' => $formdata['content'], 'price' => intval($formdata['price'])]
                    ]);
                return $this->redirectToRoute('home');
            }
            catch (\Throwable $e)
            {
                return $this->render('pages/createArticle.html.twig',[
                   'formNewArticle' => $form->createView(),
                   'error' => 'Cet article existe déjà!'
                ]);
            }
        }
        return $this->render('pages/createArticle.html.twig', [
            'formNewArticle' => $form->createView()
        ]);
    }
}
