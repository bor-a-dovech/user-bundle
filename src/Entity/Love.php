<?php

namespace Glavnivc\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="love")
 * @ORM\Entity(repositoryClass="Glavnivc\UserBundle\Repository\LoveRepository")
 */
class Love
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
