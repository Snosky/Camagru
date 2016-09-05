<?php
namespace Camagru\DAO;

use Camagru\Domain\Comment;

class CommentDAO extends DAO
{
    /* @var \Camagru\DAO\UserDAO */
    private $userDAO;

    /* @var \Camagru\DAO\ImageDAO */
    private $imageDAO;

    public function setUserDAO(UserDAO $dao)
    {
        $this->userDAO = $dao;
    }

    public  function setImageDAO(ImageDAO $dao)
    {
        $this->imageDAO = $dao;
    }

    public function findByImage($image_id)
    {
        $sql = 'SELECT * FROM t_image_com WHERE img_id=? ORDER BY com_created DESC';
        $data = $this->getDb()->fetchAll($sql, array($image_id));

        $return = array();
        foreach ($data as $k => $row)
            $return[$row['com_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function save(Comment $comment)
    {
        $data = array(
            'com_id'        => $comment->getId(),
            'com_content'   => $comment->getContent(),
            'usr_id'        => $comment->getUser()->getId(),
            'img_id'        => $comment->getImage()->getId(),
        );

        if ($comment->getId())
            $this->getDb()->update('t_image_com', $data, array('com_id' => $comment->getId()));
        else
        {
            $this->getDb()->insert('t_image_com', $data);
            $comment->setId($this->getDb()->lastInsertId());
        }
    }

    public function buildDomainObject($row)
    {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('usr_id', $row))
        {
            $user = $this->userDAO->find($row['usr_id']);
            $comment->setUser($user);
        }

        if (array_key_exists('img_id', $row))
        {
            $image = $this->imageDAO->find($row['img_id']);
            $comment->setImage($image);
        }

        return $comment;
    }
}