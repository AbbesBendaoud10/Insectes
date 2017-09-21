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
public function  redirectTologinAction()
{

    return $this->redirectToRoute('fos_user_security');
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

        $form = $this->createFormBuilder($todo)
            ->add('username', TextType::class, array('attr' => $atrributes))
            ->add('age', TextType::class, array('attr' => $atrributes))
            ->add('race', TextType::class, array('attr' => $atrributes))
            ->add('nourriture', TextType::class, array('attr' => $atrributes))
            ->add('email', TextType::class, array('attr' => $atrributes))


            ->add('save', SubmitType::class, array('label' => 'Modifier ', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $todo->setUsername($form['username']->getData());


            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $this->addFlash('notice', 'Profil modifié');

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




    public function getDetailsAction($id)
    {
        $id2 = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $insecte = $this->getDoctrine()
            ->getRepository('AcmeInsecteBundle:Insect')
            ->find($id);
        $friends = $this->getDoctrine()
            ->getRepository('AcmeInsecteBundle:Insect')
            ->findFriends($id2);

        return $this->render('AcmeInsecteBundle:Insecte:details.html.twig', array(
            'insectes' => $insecte,
             'friends' => $friends
        ));
    }


}
