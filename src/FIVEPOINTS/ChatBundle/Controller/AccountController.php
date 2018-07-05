<?php

namespace FIVEPOINTS\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use FIVEPOINTS\ChatBundle\Entity\User;

class AccountController extends Controller {

    public function getloginAction() {
        return $this->render('FIVEPOINTSChatBundle:Account:login.html.twig');
    }

    public function postloginAction() {
        $this->session = new Session();
        $mp = $this->getRequest()->get('mp');
        $login = $this->getRequest()->get('login');
        if (empty($login)) {
            return $this->redirect($this->generateUrl('login', array('msg' => 'Veuillez entrer votre login')));
        }
        if (empty($mp)) {
            return $this->redirect($this->generateUrl('login', array('msg' => 'Veuillez entrer votre mot de passe')));
        }
        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $usersData = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->findBy(
                array('login' => $login, 'mp' => $mp)
        );
        if (isset($usersData) && !empty($usersData)) {
            $perosnnelData = $usersData[0];
            $this->session->set('personnel_id', $perosnnelData->getId());
            $this->session->set('personnel_nom', $perosnnelData->getNom());
            $this->session->set('personnel_prenom', $perosnnelData->getPrenom());
            return $this->redirect($this->generateUrl('conversation_home'));
        }
        $this->session->getFlashBag()->add('msg', 'Veuillez verifier votre login et mot de passe');
        return $this->redirect($this->generateUrl('login'));
    }

    function registerAction() {
        return $this->render('FIVEPOINTSChatBundle:Account:register.html.twig');
    }

    function storeAction() {
        $this->session = new Session();
//        $this->getVars();
        $id = $this->getRequest()->get('id');
        $id = !(isset($id) && !empty($id)) ? 0 : $id;
        $is_new = !(isset($id) && !empty($id));
        //check data
        $msg = "";
        //login empty
        $name = $this->getRequest()->get('nom');
        if (empty($msg) && empty($name)) {
            $msg = 'Veuillez entrer le nom';
        }
        $prenom = $this->getRequest()->get('prenom');
        if (empty($msg) && empty($prenom)) {
            $msg = 'Veuillez entrer le prenom';
        }
        $mp = $this->getRequest()->get('mp');
        if (empty($msg) && empty($mp)) {
            $msg = 'Veuillez entrer le mot de passe';
        }
        $mp_2 = $this->getRequest()->get('mp_2');
        if (empty($msg) && empty($mp_2)) {
            $msg = 'Veuillez entrer la confirmation du mot de passe';
        }
        if (empty($msg) && $mp_2 != $mp) {
            $msg = 'Veuillez vérifier votre mot de passe';
        }
        $login = $this->getRequest()->get('login');
        if (empty($msg) && empty($login)) {
            $msg = 'Veuillez entrer votre login';
        }
        $mail = $this->getRequest()->get('mail');
        if (empty($msg) && empty($login)) {
            $msg = 'Veuillez entrer votre e-mail';
        }
        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $usersData = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->findBy(
                array('login' => $login)
        );
        if (empty($msg) && count($usersData) > 0) {
            $msg = 'Le login déja pris!';
        }
        //login exist
        if (!empty($msg)) {
            $this->session->getFlashBag()->add('msg', $msg);
            return $this->redirect($this->generateUrl('register'));
        }
        $Entity = (isset($id) && !empty($id)) ? $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->find($id) : new User();
        $Entity->setNom($this->getRequest()->get('nom'));
        $Entity->setPrenom($this->getRequest()->get('prenom'));
        $Entity->setMp($this->getRequest()->get('mp'));
        $Entity->setLogin($this->getRequest()->get('login'));
        $Entity->setMail($this->getRequest()->get('mail'));
        $this->entityManager->persist($Entity);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'Operation terminée avec succée');
        return $this->redirect($this->generateUrl('login'));
    }

    public function logoutAction() {
        $session = new Session();
        $session->remove('personnel_id');
        $session->remove('personnel_nom');
        $session->remove('personnel_prrnom');
        return $this->redirect($this->generateUrl('login'));
    }

}
