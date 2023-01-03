<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit_profile/{id}", name="app_edit_profile")
     */
    public function editProfile(Request $request,$id,EntityManagerInterface $em):Response{
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form =$this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);
//        dd($form);

        if($form->isSubmitted() && $form->isValid()){
            $user = new User();
//            dd($form->get('full_name')->getData());
            $user->setFullName($form->get('full_name')->getData());
            $user->setUsername($form->get('username')->getData());
            $user->setBirthDate($form->get('birth_date')->getData());
            $user->setMobile($form->get('mobile')->getData());
            $user->setAddres($form->get('addres')->getData());

            $em->flush();
            return $this->redirect('/profile');

        }
        return $this->render('registration/edit_profile.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
