<?php
namespace Core;

use Database\Database;
use PDO;

class Model
{
    public $table;
    private $querySelection = "*";
    private $whereClause;
    private $orderByClause;
    private $limitClause;
    private $limitOffset = 0;
    private $limitValue = 0;
    private $joinClause = "";

    private $params = []; // To store parameters for prepared statements

    public function where($whereData)
    {
        $where = "";
        if ($whereData) {
            $where = " WHERE ";
            foreach ($whereData as $key => $value) {
                $paramKey = ":$key";
                $where .= " $key = $paramKey AND";
                $this->params[$paramKey] = $value;
            }
            $where = substr($where, 0, -3);
        }
        $this->whereClause = $where;
        return $this;
    }

    public function orderBy($data)
    {
        $clause = "";
        if ($data) {
            $clause = " ORDER BY ";
            foreach ($data as $key => $value) {
                $clause .= " $key $value,";
            }
            $clause = substr($clause, 0, -1);
        }

        $this->orderByClause = $clause;
        return $this;
    }

    public function limit($limit)
    {
        $this->limitValue = $limit;
        $this->limitClause = "LIMIT $this->limitOffset, $this->limitValue";
        return $this;
    }

    public function offset($offset)
    {
        $this->limitOffset = $offset;
        $this->limitClause = "LIMIT $this->limitOffset, $this->limitValue";
        return $this;
    }

    public function paginate($page=1,$limit){
        $page--;
        $limit=30;
        $offset=$page*$limit;
        $this->limitValue = $limit;
        $this->limitOffset = $offset;
        $this->limitClause = "LIMIT $this->limitOffset, $this->limitValue";
        return $this;
    }

    public function join($joinTable, $joinCondition, $joinType = "INNER")
    {
        $this->joinClause .= " $joinType JOIN $joinTable ON $joinCondition ";
        return $this;
    }

    public function select($columns = ['*'])
    {
        if (is_array($columns)) {
            $this->querySelection = implode(', ', $columns);
        } else {
            $this->querySelection = $columns;
        }
        return $this;
    }

    public function get()
    {
        $query = "SELECT $this->querySelection FROM $this->table $this->joinClause $this->whereClause $this->orderByClause $this->limitClause";

        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $query = "SELECT * FROM $this->table";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $DB = new Database();
        $stmt = $DB->prepare($query);

        // Execute the insert query
        if ($stmt->execute($data)) {
            // Get the ID of the newly inserted record
            $lastId = $DB->lastInsertId();

            // Fetch the newly inserted record by ID
            $selectQuery = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $selectStmt = $DB->prepare($selectQuery);
            $selectStmt->execute(['id' => $lastId]);

            // Return the newly added record
            return $selectStmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;  // In case of failure
    }

    public function update($whereData, $data)
    {
        $where = "";
        foreach ($whereData as $key => $value) {
            $paramKey = ":where_$key";
            $where .= " $key = $paramKey AND";
            $this->params[$paramKey] = $value;
        }
        $where = substr($where, 0, -3);

        $set = "";
        foreach ($data as $key => $value) {
            $paramKey = ":set_$key";
            $set .= " $key = $paramKey,";
            $this->params[$paramKey] = $value;
        }
        $set = substr($set, 0, -1);

        $query = "UPDATE $this->table SET $set WHERE $where";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        return $stmt->execute($this->params);
    }

    public function delete($whereData)
    {
        $where = " WHERE ";
        foreach ($whereData as $key => $value) {
            $paramKey = ":$key";
            $where .= " $key = $paramKey AND";
            $this->params[$paramKey] = $value;
        }
        $where = substr($where, 0, -3);

        $query = "DELETE FROM $this->table $where";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        return $stmt->execute($this->params);
    }

    // For table relationship
    public function getTable()
    {
        return $this->table;
    }

    // Define One-to-One relationship
    public function hasOne($relatedModel, $foreignKey, $id)
    {
        $relatedTable = $relatedModel->getTable();
        $query = "SELECT * FROM " . $relatedTable . " WHERE " . $foreignKey . " = :id LIMIT 1";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function belongsTo($relatedModel, $id)
    {
        $relatedTable = $relatedModel->getTable();
        $query = "SELECT * FROM " . $relatedTable . " WHERE id = :id LIMIT 1";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Define One-to-Many relationship
    public function hasMany($relatedModel, $foreignKey, $id)
    {
        $relatedTable = $relatedModel->getTable();
        $query = "SELECT * FROM " . $relatedTable . " WHERE " . $foreignKey . " = :id";
        $DB = new Database();
        $stmt = $DB->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
