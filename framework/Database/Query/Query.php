<?php

namespace Framework\Database\Query;

use Framework\Database\Pdo;

class Query
{
    private $table;
    private $compiler;
    private $bindings = [];
    private $components = [
        'columns' => [],
        'distinct' => false,
        'join' => [],
        'where' => [],
        'group' => [],
        'order' => [],
        'limit' => null,
        'offset' => null,
    ];

    public function __construct(string $table, Pdo $connection)
    {
        $this->table = $table;
        $this->connection = $connection;
    }

    public function insert(array $data)
    {
        $compiler = new Compiler($this);
        $this->bindings = array_values($data);
        $query = $compiler->compileInsert(array_keys($data));
        $this->connection->query($query, $this->bindings);
        $this->resetQuery();
    }

    public function update(array $where, array $data)
    {
        $compiler = new Compiler($this);
        $this->bindings = array_values($data);
        $query = $compiler->compileUpdate($where, array_keys($data));
        $this->connection->query($query, $this->bindings);
        $this->resetQuery();
    }

    public function delete(array $where)
    {
        $compiler = new Compiler($this);
        $query = $compiler->compileDelete($where);
        $this->connection->query($query, $this->bindings);
        $this->resetQuery();
    }

    public function select(array $columns = []): self
    {
        $this->components['columns'] = $columns;
        return $this;
    }

    public function distinct(): self
    {
        $this->components['distinct'] = true;
        return $this;
    }

    public function where(string $column, string $operator, string $value, string $joiner = 'AND'): self
    {
        $this->components['where'][] = compact('column', 'operator', 'value', 'joiner');
        $this->bindings[] = $value;
        return $this;
    }

    public function andWhere(string $column, string $operator, string $value): self
    {
        $this->where($column, $operator, $value);
        return $this;
    }

    public function orWhere(string $column, string $operator, string $value): self
    {
        $this->where($column, $operator, $value, 'OR');
        return $this;
    }

    public function whereIn(string $column, array $values, string $joiner = 'AND', $negate = false): self
    {
        $operator = $negate ? 'NOT IN' : 'IN';
        $this->components['where'][] = compact('column', 'operator', 'values', 'joiner');
        $this->bindings[] = array_merge($this->bindings, $values);
        return $this;
    }

    public function orWhereIn(string $column, array $values): self
    {
        $this->whereIn($column, $values, 'OR');
        return $this;
    }

    public function whereNotIn(string $column, array $values): self
    {
        $this->whereIn($column, $values, 'AND', true);
        return $this;
    }

    public function orWhereNotIn(string $column, array $values): self
    {
        $this->whereIn($column, $values, 'OR', true);
        return $this;
    }

    public function join(string $table, string $column1, string $column2, $type = 'INNER')
    {
        $this->components['join'][] = compact('table', 'column1', 'column2', 'type');
        return $this;
    }

    public function leftJoin(string $table, string $column1, string $column2)
    {
        $type = 'LEFT';
        $this->components['join'][] = compact('table', 'column1', 'column2', 'type');
        return $this;
    }

    public function rightJoin(string $table, string $column1, string $column2)
    {
        $type = 'RIGHT';
        $this->components['join'][] = compact('table', 'column1', 'column2', 'type');
        return $this;
    }

    public function groupBy(array $columns)
    {
        $this->components['group'] = $columns;
        return $this;
    }
    
    public function orderBy(string $column, $sort = 'ASC')
    {
        $this->components['order'][] = compact('column', 'sort');
        return $this;
    }

    public function limit(int $limit)
    {
        $this->components['limit'] = $limit;
        return $this;
    }

    public function offset(int $offset)
	{
		$this->components['offset'] = $offset;
		return $this;
    }   

    public function paginate(int $limit, int $page = null) {
        $page = $page ?? app('request')->get('page');
        $page = (int) $page;

        $this->components['limit'] = $limit;
        $this->components['offset'] = $limit * ($page - 1);

        return $this;
    }

    public function count()
    {
        $this->columns = ['count(*) AS num'];
        $query = $this->getCompiledSelect();
        $result = $this->connection->query($query, $this->bindings)->fetch(\PDO::FETCH_OBJ);
        $this->resetQuery();
        
        return $result->num;
    }

    public function __get(string $key)
    {
        if($key === 'bindings') {
            return $this->bindings;
        }

        if($key === 'table') {
            return $this->table;
        }

        return $this->components[$key] ?? null;
    }

    public function fetchAll()
    {
        $query = $this->getCompiledSelect();
        $result = $this->connection->query($query, $this->bindings)->fetchAll(\PDO::FETCH_OBJ);
        $this->resetQuery();
        return $result;
    }

    public function fetchOne()
    {
        $compiler = new Compiler($this);
        $query = $compiler->compileSelect();
        $result = $this->connection->query($query, $this->bindings)->fetch(\PDO::FETCH_OBJ);
        $this->resetQuery();
        return $result;
    }

    public function getCompiledSelect()
    {
        $compiler = new Compiler($this);
        return $compiler->compileSelect();
    }

    public function resetQuery()
    {
        $this->components['columns'] = [];
        $this->components['distinct'] = false;
        $this->components['where'] = [];
        $this->components['join'] = [];
        $this->components['group'] = [];
        $this->components['order'] = [];
        $this->components['limit'] = null;
        $this->components['offset'] = null;
        $this->bindings = [];
    }
}