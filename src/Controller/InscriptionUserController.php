<?php

namespace App\Controller;

use App\Form\RegistrationUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Error\Error;

class InscriptionUserController extends AbstractController
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

        //            $response = $httpClient->request('GET', 'https://localhost:8001/api/users');
        //            $info = $response->toArray();
        //
        //            foreach ($info['hydra:member'] as $value) {
        //
        //                if ($value['email'] === $_POST['registration_user']['email']) {
        //                    $error = "L'email existe déjà, veuillez en utilisé un autre!";
        //                    return $this->render('inscription_user/registrationUser.html.twig', [
        //                        'form' => $form->createView(),
        //                        'error' => $error
        //                    ]);
        //                }
        //            }
                    // Function qui permet d'enregistrer une inscription si il y à aucune erreur et catch une erreur si c'est pas bon
                    try {
                        $httpClient->request('POST', 'https://localhost:8001/api/users', [
                            'json' => ['email' => $_POST['registration_user']['email'], 'password' => $_POST['registration_user']['password']['first'], 'confirmPassword' => $_POST['registration_user']['password']['second']]
                        ]);
                       return $this->redirectToRoute('home');
                    } catch (\Throwable $e) {
                        return $this->render('inscription_user/registrationUser.html.twig', [
                                'form' => $form->createView(),
                                'error' => $e->getMessage()
                            ]);
                    }
                }
                else {
                    $form->getErrors(true);
                }
        return $this->render('inscription_user/registrationUser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
