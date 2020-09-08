<?php

namespace App\Controller;

use App\Form\LoginUserType;
use App\Form\RegistrationUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityUserController extends AbstractController
{

//    Controller pour l'inscription d'un user qui utlise le formulaire src/Form/RegistrationUserType.php et qui renvoie la registrationUser.html.twig avec le formulaire en parametre
    /**
     * @Route("/inscription", name="InscriptionUser")
     */
    public function registration(Request $request, HttpClientInterface $httpClient)
    {
        $form = $this->createForm(RegistrationUserType::class);
        $form->handleRequest($request);

        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $httpClient->request('POST', 'https://localhost:8001/api/users', [
                    'json' => ['email' => $_POST['registration_user']['email'], 'password' => $_POST['registration_user']['password']['first'], 'confirmPassword' => $_POST['registration_user']['password']['second']]
                ]);
                return $this->redirectToRoute('loginUser');
            }
            catch (\Throwable $e) {
                return $this->render('security_user/registrationUser.html.twig', [
                    'form' => $form->createView(),
                    'error' => "L'email que vous avez rensigner est déjà utilisée."
                ]);
            }
        } else {
            $form->getErrors(true);
        }
        return $this->render('security_user/registrationUser.html.twig', [
            'formRegister' => $form->createView()
        ]);
    }

    // Function login où on récupère si la combinaison user et password : ok, ensuite on créer un token puis on le set avce le token récuperer
    /**
     * @Route ("/login", name="loginUser")
     */
    public function login(HttpClientInterface $httpClient, Request $request)
    {
        $form =$this->createForm(LoginUserType::class);
        $form->handleRequest($request);

        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $formdata = $form->getData();
                $result = $httpClient->request('POST', 'https://localhost:8001/api/login_check',[
                    'json' => ['username' => $formdata['email'], 'password' => $formdata['password']
                    ]]);
                $response = $this->redirectToRoute('home');
                $response->headers->setCookie(Cookie::create('BEARER', json_decode($result->getContent())->token));
                return $response;
            }
            catch (\Throwable $e) {
                return $this->render('security_user/loginUser.html.twig', [
                    'formLogin' => $form->createView(),
                    'error' => 'Email ou mot de passe incorrect !'
                ]);
            }
        }

        return $this->render('security_user/loginUser.html.twig',[
            'formLogin' => $form->createView()
        ]);
    }

}
