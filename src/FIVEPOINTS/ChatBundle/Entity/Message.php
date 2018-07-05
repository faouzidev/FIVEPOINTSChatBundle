<?php

namespace FIVEPOINTS\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Message {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity="FIVEPOINTS\ChatBundle\Entity\Conversation")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $conversation;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Message
     */
    public function setTexte($texte) {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte() {
        return $this->texte;
    }

    /**
     * Set conversation
     *
     * @param \FIVEPOINTS\ChatBundle\Entity\Conversation $conversation
     *
     * @return Message
     */
    public function setConversation(\FIVEPOINTS\ChatBundle\Entity\Conversation $conversation = null) {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \FIVEPOINTS\ChatBundle\Entity\Conversation
     */
    public function getConversation() {
        return $this->conversation;
    }

}
