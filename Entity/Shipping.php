<?php



namespace fjerbi\AdminBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints\Date;


/**
 * Shipping
 *
 * @ORM\Table(name="shipping")
 * @ORM\Entity
 */
class Shipping
{
    /**
     * The unique identifier for this entity
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The print friendly title to be displayed
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * The raw content of this entity
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * The raw content of this entity
     *
     * @var integer
     *
     * @ORM\Column(name="telephone", type="bigint")
     */
    private $telephone;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getTelephone(): int
    {
        return $this->telephone;
    }

    /**
     * @param int $telephone
     */
    public function setTelephone(int $telephone): void
    {
        $this->telephone = $telephone;
    }

}

