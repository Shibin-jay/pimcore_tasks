<?php

namespace customLogBundle\Model;

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

class CustomLog extends AbstractModel
{
    public $id;
    public $action;
    public $timestamp;
    public $controller;
    private $admin_user_id;

    /**
     * Load by ID.
     *
     * @param int $id
     * @return self|null
     */
    public static function getById(int $id): ?self
    {
        try {
            $obj = new self();
            $obj->getDao()->getById($id);
            return $obj;
        } catch (NotFoundException $ex) {
            \Pimcore\Logger::warn("AdminActivity with id $id not found");
        }

        return null;
    }

    public static function create(?int $adminUserId, string $action): self
    {
        $activity = new self();
        $activity->setAdminUserId($adminUserId);
        $activity->setAction($action);

        return $activity;
    }

    public function setAdminUserId($adminUserId): void
    {
        $this->admin_user_id = $adminUserId;
    }

    public function getAdminUserId()
    {
        return $this->admin_user_id;
    }

    public function setAction($action): void
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $controller
     */
    public function setController(mixed $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getController(): mixed
    {
        return $this->controller;
    }
}
