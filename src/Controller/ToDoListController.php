<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use function PHPUnit\Framework\never;

class ToDoListController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if(!empty($token)){
            return $this->redirectToRoute('app_to_do_list');
        }
        return $this->render('to_do_list/home.html.twig');
    }

    /**
     * @Route("/my_list", name="app_to_do_list")
     */
    public function index(UserRepository $repository): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
//            $data =$this->em->getRepository(ToDoList::class)->findAll();
            $data = $repository->findUserInfo();
//            dd($data);
        }else {
            $userID = $this->getUser()->getId();
            $data = $this->getDoctrine()->getRepository(ToDoList::class)->findBy(array('user_id'=>$userID));
        }
        return $this->render('to_do_list/index.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/create", name="app_create_to_do")
     */
    public function create(Request $request):Response{
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class,$toDoList);
        $form->handleRequest($request);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userID = $user->getId();
        if($form->isSubmitted() && $form->isValid()){
            $createdAt= new \DateTime();
            $toDoList->setCreationDate($createdAt);
            $toDoList->setModifyDate($createdAt);
            $toDoList->setUserId($userID);

            $this->em->persist($toDoList);
            $this->em->flush();

            $this->addFlash('notice','Submitted Successfully!!');
            return $this->redirect('/my_list');
        }
        return $this->render('to_do_list/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}",name="app_edit_to_do")
     */
    public function edit(Request $request,$id):Response{
        $toDoList = new ToDoList();
        $toDoList = $this->getDoctrine()->getRepository(ToDoList::class)->find($id);
        $form =$this->createForm(ToDoListType::class,$toDoList);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        $toDoList = new ToDoList();
        $createdAt = new \DateTime();
        $toDoList->setTitle($form->get('title')->getData());
        $toDoList->setDescription($form->get('description')->getData());
//        dd($createdAt);
        $toDoList->setModifyDate($createdAt);
//            dd($this->em->flush());
        $this->em->flush();
        return $this->redirect('/my_list');

        }
        return $this->render('to_do_list/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}",name="app_delete_to_do")
     */
    public function delete(Request $request,$id):Response{
        $toDoList = new ToDoList();
        if(!empty($id)){
            $toDoList =$this->em->getRepository(ToDoList::class)->find($id);
        }else{
            throw new \Exception("Sorry, we can't find that row");
        }
        $this->em->remove($toDoList);
        $this->em->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    /**
     *@Route("/view/{id}",name="app_view_to_do")
     */
    public function view(Request $request,$id):Response{
        $toDoList =$this->em->getRepository(ToDoList::class)->find($id);
        $form =$this->createForm(ToDoListType::class,$toDoList);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $toDoList = new ToDoList();
            $createdAt = new \DateTime();
            $toDoList->setTitle($form->get('title')->getData());
            $toDoList->setDescription($form->get('description')->getData());
//        dd($createdAt);
            $toDoList->setModifyDate($createdAt);
//            dd($this->em->flush());
            $this->em->flush();
        }

        return $this->render('to_do_list/view.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     *@Route("/profile",name="app_profile")
     */
    public function profile(){
        return $this->render('to_do_list/profile.html.twig',[]);
    }
}
