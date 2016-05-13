<?php
namespace Core;

class MyPDO
{
    /** @var  \PDO */
    private $pdo;

    private $bind_index = 0;

    public function __construct($DB_DSN, $DB_USER, $DB_PASSWORD)
    {
        // TODO : Construct
        try {
            $this->pdo = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        }
        catch (\PDOException $e)
        {
            echo 'Error PDO: '.$e->getMessage();
        }
    }

    private function select($sql, $values = array(), $fetchType, $multiple)
    {
        $this->bind_index = 0;

        $nbValues = preg_match_all('#\?#', $sql);
        if ($nbValues != count($values))
        {
            echo 'Error select';
            //TODO : Throw new MyPDOException
            return NULL;
        }

        $req = $this->pdo->prepare($sql);
        $req->setFetchMode($fetchType);
        $this->bindValues($req, $values);
        $req->execute();
        if ($multiple)
            return $req->fetchAll();
        return $req->fetch();
    }

    public function fetchAssoc($sql, $values = array())
    {
        return $this->select($sql, $values, \PDO::FETCH_ASSOC, FALSE);
    }

    public function fetchAll($sql, $values = array())
    {
        return $this->select($sql, $values, \PDO::FETCH_ASSOC, TRUE);
    }

    public function update($table, $data, $where)
    {
        $this->bind_index = 0;

        $sql = "UPDATE $table SET";

        $first = TRUE;
        foreach ($data as $k => $v)
        {
            if ($first)
                $first = FALSE;
            else
                $sql .= ",";
            $sql .= " $k=?";
        }

        $first = TRUE;
        $sql .= ' WHERE';
        foreach ($where as $k => $v)
        {
            if ($first)
                $first = FALSE;
            else
                $sql .= " AND";
            $sql .= " $k=?";
        }

        $req = $this->pdo->prepare($sql);
        $this->bindValues($req, $data);
        $this->bindValues($req, $where);
        return $req->execute();

    }

    public function insert($table, $data)
    {
        $this->bind_index = 0;

        $sql = "INSERT INTO $table (";

        $first = TRUE;
        foreach ($data as $k => $v)
        {
            if ($first)
                $first = FALSE;
            else
                $sql .= ",";
            $sql  .= " $k";
        }
        $sql .= ") VALUES (";

        $first = TRUE;
        foreach ($data as $v)
        {
            if ($first)
                $first = FALSE;
            else
                $sql .= ",";
            $sql .= " ?";
        }
        $sql .= ")";

        $req = $this->pdo->prepare($sql);
        $this->bindValues($req, $data);
        return $req->execute();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    public function delete($table, $where = array())
    {
        $this->bind_index = 0;

        $sql = "DELETE FROM $table WHERE";
        
        $first = TRUE;
        foreach ($where as $k => $v)
        {
            if ($first)
                $first = FALSE;
            else
                $sql .= " AND";
            $sql .= " $k=?";
        }

        $req = $this->pdo->prepare($sql);
        $this->bindValues($req, $where);
        return $req->execute();
    }

    private function bindValues(\PDOStatement $req, $values)
    {
        foreach ($values as $k => $value)
        {
            $this->bind_index++;
            switch (gettype($value))
            {
                case 'string':
                    $req->bindValue($this->bind_index, $value, \PDO::PARAM_STR);
                    break;
                case 'integer':
                    $req->bindValue($this->bind_index, $value, \PDO::PARAM_INT);
                    break;
                case 'boolean':
                    $req->bindValue($this->bind_index, $value, \PDO::PARAM_BOOL);
                    break;
                case 'NULL':
                    $req->bindValue($this->bind_index, $value, \PDO::PARAM_NULL);
                    break;
                default:
                    echo 'Error bindValues';
                // TODO : Throw new MyPDOException
            }
        }
    }

}