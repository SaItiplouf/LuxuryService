<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Applications
 *
 * @ORM\Table(name="applications", indexes={@ORM\Index(name="fk_candidate_id", columns={"candidate_id"}), @ORM\Index(name="fk_offer_id", columns={"offer_id"})})
 * @ORM\Entity
 */
class Applications
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Offers
     *
     * @ORM\ManyToOne(targetEntity="Offers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="offer_id", referencedColumnName="id")
     * })
     */
    private $offer;

    /**
     * @var \Candidates
     *
     * @ORM\ManyToOne(targetEntity="Candidates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="candidate_id", referencedColumnName="id")
     * })
     */
    private $candidate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffer(): ?Offers
    {
        return $this->offer;
    }

    public function setOffer(?Offers $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getCandidate(): ?Candidates
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidates $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }


}
