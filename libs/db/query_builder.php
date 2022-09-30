<?php
require_once("../utility/error_report.php");
// Select Query Builder 
class SelectQueryBuilder {

    private $fields = [];
    private $conditions = [];
    private $from = [];
    private $limit = 0;
    private $off_set = 0;
    private $col_list = [];

    public function __toString(): string{
        $where = $this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions);
        if($this->limit){
            return 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . $where
            .' ORDER BY '.implode(', ', $this->col_list)
            .' LIMIT '. $this->limit
            .' OFFSET '. $this->off_set;
        } else{
            return 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . $where
            .' ORDER BY '.implode(', ', $this->col_list);
        }
    }

    public function select(string ...$select){
        $this->fields = $select;
        return $this;
    }

    public function where(string ...$where){
        foreach ($where as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }

    public function from(string $table){
        
         $this->from[] = $table;
        return $this;
    }
    public function orderBy(string ...$orderBy){
        
        $this->col_list = $orderBy;
        return $this;
    }
    public function limit(int $limit){
        
         $this->limit = $limit;
        return $this;
    }
    public function offSet(int $offSet)
    {
        
         $this->off_set = $offSet;
        return $this;
    }
}
// Insert Query Builder
class InsertQueryBuilder {

    private $table;
    private $columns = [];
    private $values = [];

    public function __toString(): string{
        return 'INSERT INTO ' . $this->table
            . ' (' . implode(', ',$this->columns) . ') VALUES (' . implode(', ',$this->values) . ')';
    }

    public function insert(string $table){
        $this->table = $table;
        return $this;
    }

    public function columns(string ...$columns){
        $this->columns = $columns;
        foreach ($columns as $column) {
            $this->values[] = ":$column";
        }
        return $this;
    }
}
//Update Query Builder 
class UpdateQueryBuilder {

    private $table;
    private $conditions = [];
    private $columns = [];


    public function __toString(): string{
        return 'UPDATE ' . $this->table
            . ' SET ' . implode(', ', $this->columns)
            . ($this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions));
    }

    public function update(string $table){
        $this->table = $table;
        return $this;
    }

    public function where(string ...$where){
        foreach ($where as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }

    public function set(string ...$columns){
        foreach ($columns as $column) {
            $this->columns[] = "$column = :$column";
        }
        return $this;
    }

}
?>