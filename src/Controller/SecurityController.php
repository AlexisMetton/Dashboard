<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/oubli_pass", name="forgotten_password")  
     */
    
     public function forgottenPassword(Request $request, 
     UserRepository $userRepository, 
     TokenGeneratorInterface $tokenGeneratorInterface, 
     EntityManagerInterface $entityManagerInterface,
     SendMailService $mail,
     UserPasswordHasherInterface $userPasswordHasher): Response
     {
        $form = $this->createForm(ResetPasswordRequestFormType::class);


        $form->handleRequest($request);

       
        if($form->isSubmitted() && $form->isValid()){
            //on va chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            //on verifie si on a un utilisateur
            if($user){

                //on génère un token de réinitialisation
                    $token = $tokenGeneratorInterface->generateToken();
                    $user->setResetToken($token);
                    $entityManagerInterface->persist($user);
                    $entityManagerInterface->flush();

                    //on génère un lien de réinitialisation du mot de passe
                    $url = $this->generateUrl('reset_pass', ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL);
                    
                    //on crée les données du mail
                    $context = compact('url','user');

                    //Envoie du mail
                    $mail->send(
                        'no-reply@e-commerce.fr',
                        $user->getEmail(),
                        'réinitialisation de mot de passe',
                        'password_reset',
                        $context
                    );

                     
                    $this->addFlash('goodpass', 'Email envoyé avec succès');
                   return $this->redirectToRoute('login');
            }
            // $user est null
            $this->addFlash('dangerpass','un problème est survenu');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset_password_request.html.twig',[
            'requestPassForm' => $form->createView()
        ]);

     }

     /**
      * @Route("/oubli_pass/{token}", name="reset_pass")
      */

      public function resetPass(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $userPasswordHasher): Response
      {
        //on vérifie si on a ce token dans la base de donnée
        $user = $userRepository->findOneByResetToken($token);
        
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                // on efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();

                $this->addFlash('successpass','mot de passe changer avec succès');
                return $this->redirectToRoute('login');
            }

            return$this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);

        }
        $this->addFlash('danger', 'jeton invalide');
        return $this->redirectToRoute('login');

      }
}