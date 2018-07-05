<?php

namespace FIVEPOINTS\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="FIVEPOINTS\ChatBundle\Repository\ConversationRepository")
 */
class Conversation {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FIVEPOINTS\ChatBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="FIVEPOINTS\ChatBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $destinateur;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set source
     *
     * @param \FIVEPOINTS\ChatBundle\Entity\User $source
     *
     * @return Conversation
     */
    public function setSource(\FIVEPOINTS\ChatBundle\Entity\User $source = null) {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \FIVEPOINTS\ChatBundle\Entity\User
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Set destinateur
     *
     * @param \FIVEPOINTS\ChatBundle\Entity\User $destinateur
     *
     * @return Conversation
     */
    public function setDestinateur(\FIVEPOINTS\ChatBundle\Entity\User $destinateur = null) {
        $this->destinateur = $destinateur;

        return $this;
    }

    /**
     * Get destinateur
     *
     * @return \FIVEPOINTS\ChatBundle\Entity\User
     */
    public function getDestinateur() {
        return $this->destinateur;
    }

}
