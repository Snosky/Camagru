<?php
namespace Camagru\Domain;

class Image implements \JsonSerializable
{
    /* @var integer */
    private $id;

    /* @var \Camagru\Domain\User */
    private $user;

    private $created;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getRealPath()
    {
        return ROOT.'web/img/uploads/'.$this->id.'.png';
    }

    public function getPath()
    {
        return WEBROOT.'img/uploads/'.$this->id.'.png';
    }

    public function jsonSerialize()
    {
        return array(
            'id'        =>  $this->getId(),
            'user'      =>  $this->getUser(),
            'created'   => $this->getCreated(),
            'path'      => $this->getPath(),
            'real_path' => $this->getRealPath()
        );
    }
}