<?php
namespace Camagru\DAO;

use Camagru\Domain\Image;

class ImageDAO extends DAO
{
    /* @var \Camagru\DAO\UserDAO */
    private $userDAO;

    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM t_image WHERE img_id=?';
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        return NULL;
    }

    public function findByUser($user_id)
    {
        $sql = 'SELECT * FROM t_image WHERE usr_id=?';
        $result = $this->getDb()->fetchAll($sql, array($user_id));

        $return = array();
        foreach ($result as $row)
            $return[$row['img_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function findAllPagination($page)
    {
        $sql = 'SELECT * FROM t_image ORDER BY img_created DESC LIMIT ?, 9';
        $result = $this->getDb()->fetchAll($sql, array($page * 9));

        $return = array();
        foreach ($result as $row)
            $return[$row['img_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function findLast($nb)
    {
        $sql = 'SELECT * FROM t_image ORDER BY img_created DESC LIMIT ?';
        $result = $this->getDb()->fetchAll($sql, array($nb));

        $return = array();
        foreach ($result as $row)
            $return[$row['img_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function count()
    {
        $sql = 'SELECT COUNT(*) AS count FROM t_image';
        $row = $this->getDb()->fetchAssoc($sql);

        return $row['count'];
    }

    public function save(Image $image)
    {
        $data = array(
            'img_id'        => $image->getId(),
            'usr_id'        => $image->getUser()->getId(),
        );

        if ($image->getId())
            $this->getDb()->update('t_image', $data, array('img_id' => $image->getId()));
        else
        {
            $this->getDb()->insert('t_image', $data);
            $image->setId($this->getDb()->lastInsertId());
        }
    }

    public function delete($image_id)
    {
        $this->getDb()->delete('t_image_com', array('img_id' => $image_id));
        $this->getDb()->delete('t_image_like', array('img_id' => $image_id));
        $this->getDb()->delete('t_image', array('img_id' => $image_id));
    }


    protected function buildDomainObject($row)
    {
        $image = new Image();
        $image->setId($row['img_id']);
        $image->setCreated($row['img_created']);

        if (array_key_exists('usr_id', $row))
        {
            $user = $this->userDAO->find($row['usr_id']);
            $image->setUser($user);
        }

        return $image;
    }
}