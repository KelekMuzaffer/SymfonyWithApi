<?php

namespace App\Controller;

use App\Form\RegistrationUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionUserController extends AbstractController
{

//    Controller pour l'inscription d'un user qui utlise le formulaire src/Form/RegistrationUserType.php et qui renvoie la registrationUser.html.twig avec le formulaire en parametre
    /**
     * @Route("/inscription", name="InscriptionUser")
     */
    public function registration()
    {
        $form = $this->createForm(RegistrationUserType::class);

        return $this->render('inscription_user/registrationUser.html.twig',[
            'form' => $form->createView()
        ]);

    }

}
