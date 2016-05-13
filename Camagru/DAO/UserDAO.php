<?php
namespace Camagru\DAO;

use Camagru\Domain\User;

class UserDAO extends DAO
{
    public function find($id)
    {
        $sql = 'SELECT * FROM t_user WHERE usr_id=?';
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        return NULL;
    }

    public function findByUsername($username)
    {
        $sql = 'SELECT * FROM t_user WHERE usr_username=?';
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        return NULL;
    }

    public function findByEmail($email)
    {
        $sql = 'SELECT * FROM t_user WHERE usr_email=?';
        $row = $this->getDb()->fetchAssoc($sql, array($email));

        if ($row)
            return $this->buildDomainObject($row);
        return NULL;
    }


    public function findAll()
    {
        $sql = 'SELECT * FROM t_user';
        $result = $this->getDb()->fetchAll($sql);

        $return = array();
        foreach ($result as $row)
            $return[$row['usr_id']] = $this->buildDomainObject($row);
        return $return;
    }

    public function save(User $user)
    {
        $data = array(
            'usr_id'        => $user->getId(),
            'usr_username'   => $user->getUsername(),
            'usr_email'     => $user->getEmail(),
            'usr_password'  => $user->getPassword(),
            'usr_salt'      => $user->getSalt(),
            'usr_role'      => $user->getRole(),
        );

        if ($user->getId())
        {
            $this->getDb()->update('t_user', $data, array('usr_id' => $user->getId()));
        }
        else
        {
            $this->getDb()->insert('t_user', $data);
            $user->setId($this->getDb()->lastInsertId());
        }

    }

    public function delete($id)
    {
        $this->getDb()->delete('t_user', array('usr_id' => $id));
    }


    protected function buildDomainObject($row)
    {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_username']);
        $user->setEmail($row['usr_email']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
        return $user;
    }
}