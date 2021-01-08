<?php

namespace Lightpack\Database\Query;

class Compiler
{
    private $query;

    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    public function compileSelect()
    {
        $sql[] = $this->select();
        $sql[] = $this->columns();
        $sql[] = $this->from();
        $sql[] = $this->join();
        $sql[] = $this->where();
        $sql[] = $this->groupBy();
        $sql[] = $this->orderBy();
        $sql[] = $this->limit();
        $sql[] = $this->offset();

        $sql = array_filter($sql, function($v) { return empty($v) === false; });
        return trim(implode(' ', $sql));
    }

    public function compileInsert(array $columns)
    {
        $parameters = $this->parameterize(count($columns));
        $columns = implode(', ', $columns);
        
        return "INSERT INTO {$this->query->table} ($columns) VALUES ($parameters)";
    }

    public function compileUpdate(array $where, array $columns)
    {
        $where = $where[0] . ' = ' . (int) $where[1];

        foreach ($columns as $column)
		{
			$columnValuePairs[] = $column . ' = ?';
        }
        
        $columnValuePairs = implode(', ', $columnValuePairs);

        return "UPDATE {$this->query->table} SET {$columnValuePairs} WHERE {$where}";
    }

    public function compileDelete(array $where)
    {
        $where = $where[0] . ' = ' . (int) $where[1];
        
        return "DELETE FROM {$this->query->table} WHERE {$where}";
    }

    private function select(): string
    {
        return $this->query->distinct ? 'SELECT DISTINCT' : 'SELECT';
    }

    private function columns(): string
    {
        if(!$this->query->columns) {
            return '*';
        }

        return implode(', ', $this->query->columns);
    }

    private function from(): string
    {
        return 'FROM ' . $this->query->table;
    }

    private function join()
    {
        if(!$this->query->join) {
            return '';
        }

        $joins = [];

        foreach($this->query->join as $join) {
            $joins[] = strtoupper($join['type']) . ' JOIN ' . $join['table'] . ' ON ' . $join['column1'] . ' = ' . $join['column2'];
        }

        return implode(' ', $joins);
    }

    private function where(): string
    {
        if(!$this->query->where) {
            return '';
        }

        $wheres[] = 'WHERE 1=1';
        $parameters = $this->parameterize(1);

        foreach($this->query->where as $where) {
            if(isset($where['values'])) {
                $parameters = $this->parameterize(count($where['values']));
            }

            $wheres[] = strtoupper($where['joiner']) . ' ' . $where['column'] . ' ' . $where['operator'] . ' ' . $parameters;
        }

        return implode(' ', $wheres);
    }

    private function groupBy()
    {
        if(!$this->query->group) {
            return '';
        }

        return 'GROUP BY ' . implode(', ', $this->query->group);
    }
    
    private function orderBy()
    {
        if(!$this->query->order) {
            return '';
        }

        $orders = [];

        foreach($this->query->order as $order) {
            $orders[] = $order['column'] . ' ' . $order['sort'];
        }

        return 'ORDER BY ' . implode(', ', $orders);
    }

    private function limit()
    {
        if(!$this->query->limit) {
            return '';
        }

        return 'LIMIT ' . $this->query->limit;
    }

    private function offset()
    {
        if(!$this->query->offset) {
            return '';
        }

        return 'OFFSET ' . $this->query->offset;
    }

    private function parameterize(int $count)
    {
        $parameters = array_fill(0, $count, '?');
        $parameters = implode(', ', $parameters); 

        return $parameters;
    }
}