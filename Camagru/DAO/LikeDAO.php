<?php
namespace Camagru\DAO;

use Camagru\Domain\Like;

class LikeDAO extends DAO
{
    /* @var \Camagru\DAO\UserDAO */
    private $userDAO;

    /* @var \Camagru\DAO\ImageDAO */
    private $imageDAO;

    public function setUserDAO(UserDAO $dao)
    {
        $this->userDAO = $dao;
    }

    public function setImageDAO(ImageDAO $dao)
    {
        $this->imageDAO = $dao;
    }

    public function countByImage($image_id)
    {
        $sql = 'SELECT COUNT(*) AS count FROM t_image_like WHERE img_id=?';
        $row = $this->getDb()->fetchAssoc($sql, array(
            'img_id'    => $image_id
        ));

        return $row['count'];
    }

    public function exist(Like $like)
    {
        $sql = 'SELECT COUNT(*) AS count FROM t_image_like WHERE img_id=? AND usr_id=?';
        $row = $this->getDb()->fetchAssoc($sql, array(
            'img_id'    => $like->getImage()->getId(),
            'usr_id'    => $like->getUser()->getId()
        ));

        return $row['count'];
    }

    public function findByUser($user_id)
    {
        $sql = 'SELECT * FROM t_image_like WHERE usr_id=?';
        $result = $this->getDb()->fetchAll($sql, array($user_id));

        $return = array();
        foreach ($result as $row)
            $return[$row['img_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function save(Like $like)
    {
        $data = array(
            'img_id'    => $like->getImage()->getId(),
            'usr_id'    => $like->getUser()->getId(),
        );

        $this->getDb()->insert('t_image_like', $data);
    }

    public function delete(Like $like)
    {
        $this->getDb()->delete('t_image_like', array(
            'img_id'    => $like->getImage()->getId(),
            'usr_id'    => $like->getUser()->getId(),
        ));
    }

    protected function buildDomainObject($row)
    {
        $like = new Like();

        if (array_key_exists('usr_id', $row))
        {
            $user = $this->userDAO->find($row['usr_id']);
            $like->setUser($user);
        }

        if (array_key_exists('img_id', $row))
        {
            $image = $this->imageDAO->find($row['img_id']);
            $like->setImage($image);
        }
        return ($like);
    }
}