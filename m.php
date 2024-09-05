<?php
    namespace Core;
    
    use Database\Database;

    Class Model{

        public  $table;
        private $querySelection="*";
        private $whereClause;
        private $orderByClause;
        private $limitClause;
        private $limitOffset =0;
        private $limitValue =0;
        private $joinClause = "";

        public function where($whereData){
            $where="";
            if($whereData){
                $where=" WHERE ";
                foreach($whereData as $key=>$value){
                    if(is_numeric($value)){
                        $where.=" $key = $value and";
                    }else{
                        $where.=" $key = '$value' and";
                    } 
                }
                $where = substr($where,0,-3);
            }
            $this->whereClause= $where;
            return $this;
        }

        public function orderBy($data){
            $clause = "";
            if($data){
                $clause=" ORDER BY ";
                foreach($data as $key=>$value){
                    $clause.=" $key  $value,";
                }
                $clause = substr($clause,0,-1);
            }

            $this->orderByClause = $clause;
            return $this;
        }

        public function limit($limit){
            $this->limitValue = $limit;
            $this->limitClause = "LIMIT $this->limitOffset,$this->limitValue";
            return $this;
        }

        public function offset($offset){
            $this->limitOffset = $offset;
            $this->limitClause = "LIMIT $this->limitOffset,$this->limitValue";
            return $this;
        }

        public function join($joinTable, $joinCondition, $joinType = "INNER") {
            $this->joinClause .= " $joinType JOIN $joinTable ON $joinCondition ";
            return $this;
        }

        public function select($columns = ['*']) {
            if (is_array($columns)) {
                $this->querySelection = implode(', ', $columns);
            } else {
                $this->querySelection = $columns;
            }
            return $this;
        }

        public function get(){

            $query = "SELECT $this->querySelection FROM  $this->table $this->joinClause $this->whereClause $this->orderByClause $this->limitClause";

            $DB = new Database();
            $result = $DB->read($query);
           
            return $result;
        }
        
        public function all(){

            $query = "SELECT * FROM $this->table";
            $DB = new Database();
            $result = $DB->read($query);
            return $result;
        }

        public function find($id){

            $query = "SELECT * FROM $this->table WHERE id=$id";
            $DB = new Database();
            $result = $DB->read($query);
            if($result){
                return $result[0];
            }else{
                return false;
            }
        }

        public function create($data) {
            $columns = implode(", ", array_keys($data));
            
            $values = "";
            foreach($data as $key=>$value){
                if(is_numeric($value)){
                    $values.="$value,";
                }else{
                    $values.="'$value',";
                } 
            }
            $values = substr($values,0,-1);

            $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($values)";
            $DB = new Database();
          
            return $DB->save($query);
        }

        public function update($whereData,$data){

            $where="";
            if(is_numeric($whereData)){
                $where = "id = $whereData";
            }else{
                foreach($whereData as $key=>$value){
                    if(is_numeric($value)){
                        $where.=" $key = $value and";
                    }else{
                        $where.=" $key = '$value' and";
                    } 
                }
                $where = substr($where,0,-3);
            }

            $set = "";
            foreach($data as $key=>$value){
                if(is_numeric($value)){
                    $set.=" $key = $value and";
                }else{
                    $set.=" $key = '$value' and";
                } 
            }
            $set = substr($set,0,-3);

            $query = "UPDATE $this->table SET $set WHERE $where";
            return $query;
        }

        public function delete($whereData){
            $where=" WHERE ";
            if(is_numeric($whereData)){
                $where .= "id = $whereData";
            }else{
                foreach($whereData as $key=>$value){
                    if(is_numeric($value)){
                        $where.=" $key = $value and";
                    }else{
                        $where.=" $key = '$value' and";
                    } 
                }
                $where = substr($where,0,-3);
            }

            $query = "DELETE FROM $this->table $where";
           
            $DB = new Database();
            $result = $DB->save($query);
            return $result;
        }


        //  for table relationship
        public function getTable() {
            return $this->table;
        }

         // Define One-to-One relationship
        public function hasOne($relatedModel, $foreignKey, $id) {
            $relatedTable = $relatedModel->getTable();
            $query = "SELECT * FROM " . $relatedTable . " WHERE " . $foreignKey . " = $id LIMIT 1";
            $DB = new Database();
            $result = $DB->read($query);
            return $result[0];
        }

        public function belongsTo($relatedModel, $id) {
            $relatedTable = $relatedModel->getTable();
            $query = "SELECT * FROM " . $relatedTable . " WHERE id" . " = $id LIMIT 1";
            $DB = new Database();
            $result = $DB->read($query);
            return $result[0];
        }

        // Define One-to-Many relationship
        public function hasMany($relatedModel, $foreignKey,$id) {
            $relatedTable = $relatedModel->getTable();
            $query = "SELECT * FROM " . $relatedTable . " WHERE " . $foreignKey . " = $id";
            $DB = new Database();
            $result = $DB->read($query);
            return $result;
        }

    }
?>