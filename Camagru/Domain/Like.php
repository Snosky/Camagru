<?php
namespace Camagru\Domain;

class Like
{
    /* @var \Camagru\Domain\Image */
    private $image = 0;

    /* @var \Camagru\Domain\User */
    private $user = 0;

    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getUser()
    {
        return $this->user;
    }
}