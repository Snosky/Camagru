<?php
namespace Camagru\DAO;

use Camagru\Domain\User;
use Core\MyPDO;

abstract class DAO
{
    /** @var MyPDO  */
    private $db;

    public function __construct(MyPDO $db)
    {
        $this->db = $db;
    }
    
    public function getDb()
    {
        return $this->db;
    }
    
    abstract protected function buildDomainObject($row);
}