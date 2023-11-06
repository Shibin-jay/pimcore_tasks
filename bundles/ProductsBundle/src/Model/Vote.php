<?php

namespace ProductsBundle\Model;

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

class Vote extends AbstractModel
{
    public ?int $id= null;
    public ?string $username = null;
    public ?int $score = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param int|null $score
     */
    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }
    /**
     * get score by id
     */
    public static function getById(int $id): ?self
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        }
        catch (NotFoundException $ex) {
            \Pimcore\Logger::warn("Vote with id $id not found");
        }

        return null;
    }


}
