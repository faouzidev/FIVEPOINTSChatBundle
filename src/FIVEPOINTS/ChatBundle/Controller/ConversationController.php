<?php

namespace FIVEPOINTS\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use FIVEPOINTS\ChatBundle\Entity\User;
use FIVEPOINTS\ChatBundle\Entity\Message;
use FIVEPOINTS\ChatBundle\Entity\Conversation;

class ConversationController extends Controller {

    public function homeAction() {
        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $this->data = new \stdClass();
        $this->data->users = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->findAll();
        return $this->render('FIVEPOINTSChatBundle:Conversation:home.html.twig', ['data' => $this->data]);
    }

    public function getmessageAction() {
        $this->request = $this->getRequest();
        $this->session = new Session();
        $id = $this->request->get('id');
        $destinateur_id = $this->request->get('id');
        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $destinateurData = (isset($id) && !empty($id)) ? $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->find($destinateur_id) : new User();
        $this->logged_user = $this->session->get('personnel_id');
        $this->data = new \stdClass();
        $this->data->users = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->findAll();
        // get conversation id

        $conversationsData = $this->entityManager->getRepository('FIVEPOINTSChatBundle:Conversation')->findBy(
                array('source' => [$destinateurData->getId(), $this->logged_user],
                    'destinateur' => [$destinateurData->getId(), $this->logged_user]
                )
        );
        // if first conversation : 
        // create new conversation.
        $conversationEntity = new Conversation();
        if (empty($conversationsData)) {
            $source = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->find($this->logged_user);
            $conversationEntity->setSource($source);
            $destinateur = $this->entityManager->getRepository('FIVEPOINTSChatBundle:User')->find($destinateur_id);
            $conversationEntity->setDestinateur($destinateur);
            $this->entityManager->persist($conversationEntity);
            $this->entityManager->flush();
        } else {
            $conversationEntity = $conversationsData[0];
        }
        $this->data->msgsData = $this->entityManager->getRepository('FIVEPOINTSChatBundle:Message')->findBy(
                array('conversation' => [$conversationEntity->getId()]
                ), array('date' => 'DESC')
        );
        $this->data->conversationData = $conversationEntity;
        return $this->render('FIVEPOINTSChatBundle:Conversation:messages.html.twig', array('destinateurData' => $destinateurData, 'data' => $this->data));
    }

    public function storeAction() {
        $this->request = $this->getRequest();
        $this->session = new Session();
        $id = $this->request->get('id');
        $id = !(isset($id) && !empty($id)) ? 0 : $id;
        $is_new = !(isset($id) && !empty($id));
        //check data
        $msg = "";
        //login empty
        $name = $this->request->get('texte');
        if (empty($msg) && empty($name)) {
            $msg = 'Veuillez entrer le message';
        }
        //login exist
        if (!empty($msg)) {
            $this->session->getFlashBag()->add('msg', $msg);
            $this->redirect($this->generateUrl('register'))->sendHeaders();
            exit;
        }
        $this->entityManager = $this->getDoctrine()->getEntityManager();
        $this->logged_user = $this->session->get('personnel_id');
        $messageEntity = new Message();
        $conversation_id = $this->request->get('conversation');
        $conversation = $this->entityManager->getRepository('FIVEPOINTSChatBundle:Conversation')->find($conversation_id);
        $messageEntity->setConversation($conversation);
        $destinateur_id = $this->request->get('destinateur');
        $messageEntity->setTexte($this->request->get('texte'));
        $date = new \DateTime();
        $messageEntity->setDate($date);
        $this->entityManager->persist($messageEntity);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'Message envoyé avec succée');
        return $this->redirect($this->generateUrl('conversation', array('id' => $destinateur_id)));
    }

}
