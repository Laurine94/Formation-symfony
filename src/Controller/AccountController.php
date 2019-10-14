<?php

namespace App\Controller;


use App\Form\AccountType;
use App\Entity\Utilisateur;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error=$utils->getLastAuthenticationError();
        $username=$utils->getLastUsername();


        return $this->render('account/login.html.twig',[
            'hasError'=>$error !==null,
            'username'=>$username
        ]);
    }


    /**
     * Permet de se deconnecter
     *
     * @Route("/logout" ,name="account_logout")
     * 
     * @return void
     */
    public function logout()
    {
        //rien...
    }


    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){

        $user=new Utilisateur();

        $form=$this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        

        if ($form->isSubmitted()&& $form->isValid()) {

            $hash=$encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé! Vous pouvez maintenant vous connecter!"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',[
            'form'=>$form->createView()
        ]);

    }


    /**
     * Permet d'afficher et de taraiter le formulaire de modification de profil
     * 
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){

        $user=$this->getUser();

        $form=$this->createForm(AccountType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrées avec succès!"
            );
        }

        return $this->render('account/profile.html.twig',[
            'form'=>$form->createView()
        ]);

    }


    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $passwordUpdate=new PasswordUpdate();

        $user=$this->getUser();

        $form=$this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            //1.Vérifier que le oldPassword du formulaire soit le meme que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(),$user->getHash())) {
                //gérer l'erreur

                $form->get("oldPassword")->addError(new FormError("Ce n'est pas le bon mot de passe."));
                
            } else {
                $newPassword=$passwordUpdate->getNewPassword();
                $hash=$encoder->encodePassword($user,$newPassword);

                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été enregistré."
                );

                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('account/password.html.twig',[
            'form'=>$form->createView()
        ]);

    }


    /**
     * Permet d'afficher le profil de l'utilisateur connécté
     * 
     * @Route("/account",name="account_index")
     *
     * @return Response
     */
    public function myAccount(){

        return $this->render('user/index.html.twig',[
            'user'=>$this->getUser()
        ]);

    }
}
