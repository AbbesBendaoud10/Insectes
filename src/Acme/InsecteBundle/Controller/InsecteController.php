<?php

namespace Acme\InsecteBundle\Controller;
use Acme\InsecteBundle\Entity\Insect;
use Acme\InsecteBundle\Repository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class InsecteController extends Controller
{
    /**
     * @Route("insecte")
     */
    public function indexAction()
    {
        $id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $friends = $em
            ->getRepository('AcmeInsecteBundle:Insect')
            ->findFriends($id);


        // $announces = $em->getRepository('ViewBundle:Announce')->findByType('location');
        $insectes = $em->getRepository('AcmeInsecteBundle:Insect')->findAll();

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $id = $user->getId();


        return $this->render('AcmeInsecteBundle:Insecte:index.html.twig', array(
            'insectes' => $insectes,
            'user'=>$id,
            'friends' => $friends
        ));

    }

    public function defaultAction()
    {
        return $this->redirectToRoute('redirected');
    }
    public function default2Action()
    {
        return null;
    }
    public function getProfileAction()
    {
        $id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $friends = $em
            ->getRepository('AcmeInsecteBundle:Insect')
            ->findFriends($id);

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();

        // $announces = $em->getRepository('ViewBundle:Announce')->findByType('location');
        $insectes = $em->getRepository('AcmeInsecteBundle:Insect')->find($id);



        return $this->render('AcmeInsecteBundle:Insecte:profile.html.twig', array(
            'insectes' => $insectes,

        ));

    }
    public function getProfile2Action()
    {
        $id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $friends = $em
            ->getRepository('AcmeInsecteBundle:Insect')
            ->findFriends($id);

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();

        // $announces = $em->getRepository('ViewBundle:Announce')->findByType('location');
        $insectes = $em->getRepository('AcmeInsecteBundle:Insect')->find($id);



        return $this->render('AcmeInsecteBundle:Insecte:myprofil.html.twig', array(
            'insectes' => $insectes,

        ));

    }



    public function editProfileAction($id, Request $request)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AcmeInsecteBundle:Insect')
            ->find($id);



        $atrributes = array('class' => 'form-control' , 'style' => 'margin-bottom:10px');
        $choices = array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High');
        $form = $this->createFormBuilder($todo)
            ->add('username', TextType::class, array('attr' => $atrributes))


            ->add('save', SubmitType::class, array('label' => 'Update User', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $todo->setUsername($form['username']->getData());


            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $this->addFlash('notice', 'Todo updated');

            return $this->redirectToRoute('profile2');
        }

        return $this->render('AcmeInsecteBundle:Insecte:editProfile.html.twig', array(
            'form' => $form->createView(),
            'todo' => $todo
        ));
    }
    public function getFriendsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $friends = $em
            ->getRepository('AcmeInsecteBundle:Insect')
            ->findFriends(5);

        return $this->render('AcmeInsecteBundle:Insecte:friends.html.twig', array(
            'friends' => $friends,

        ));
    }







       public function addFriendToInsectAction($id)
       {
/*
           $insecte1 = new Insect();
           $insecte1->setUsername("Cafard");
           $insecte2=new  Insect();
           $insecte2->setUsername("Moustique");

*/
           $em = $this->getDoctrine()->getManager();
           $insecte1= $em->getRepository(Insect::class)->find($id);
           $user = $this->container->get('security.token_storage')->getToken()->getUser();
           $idInsecte2 = $user->getId();
           $insecte2= $em->getRepository(Insect::class)->find($idInsecte2);
           $insecte1->addFriend($insecte2);

               $em = $this->getDoctrine()->getManager();

              // $em->persist($insecte1);
               //$em->persist($insecte2);
               $em->flush();

               return $this->redirectToRoute('index_insectes');




       }

    public function removeFriendAction($id)
    {
        /*
                   $insecte1 = new Insect();
                   $insecte1->setUsername("Cafard");
                   $insecte2=new  Insect();
                   $insecte2->setUsername("Moustique");

        */
        $em = $this->getDoctrine()->getManager();
        $insecte1= $em->getRepository(Insect::class)->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idInsecte2 = $user->getId();
        $insecte2= $em->getRepository(Insect::class)->find($idInsecte2);
        $insecte1->removeFriend($insecte2);

        $em = $this->getDoctrine()->getManager();

        // $em->persist($insecte1);
        //$em->persist($insecte2);
        $em->flush();

        return $this->redirectToRoute('index_insectes');




    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        if (empty($todo)) {
            $this->addFlash('error', 'Todo not found');

            return $this->redirectToRoute('todo_list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();

        $this->addFlash('notice', 'Todo removed');

        return $this->redirectToRoute('todo_list');
    }
    public function newAnnounceAction(Request $request)
    {


        $announce = new Announce();
        $form = $this->createForm('AnnounceBundle\Form\AnnounceType', $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file

            $em = $this->getDoctrine()->getManager();

            $em->persist($announce);
            $em->flush();

            return $this->redirectToRoute('annonce');
        }
        return $this->render('ViewBundle:front:newAnnounce.html.twig', array(
            'announce' => $announce,
            'form' => $form->createView(),
        ));


    }

}
