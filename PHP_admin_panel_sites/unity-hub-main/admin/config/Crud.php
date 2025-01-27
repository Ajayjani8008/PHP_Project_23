<?php
include_once 'DbConfig.php';

class Crud extends DbConfig
{
	public function __construct()
	{
		parent::__construct();
	}


	public function getData($table, $where, $limit, $column)
	{
		// echo "SELECT * FROM $table WHERE $where LIMIT $limit";
		if (empty($where) && empty($limit) && empty($column)) {

			$query = "SELECT * FROM $table";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		} elseif (empty($limit) && !empty($where) && empty($column)) {

			$query = "SELECT * FROM $table WHERE $where ORDER BY id ASC";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		} elseif (!empty($column) && empty($where) && empty($limit)) {

			$query = "SELECT {$column} FROM $table";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		} elseif (!empty($column) && !empty($where) && empty($limit)) {

			$query = "SELECT {$column} FROM $table WHERE $where  ORDER BY id DESC";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		} elseif (empty($column) && !empty($where) && !empty($limit)) {

			$query = "SELECT * FROM $table WHERE $where LIMIT $limit";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		} else {
			$query = "SELECT * FROM $table  LIMIT $limit";

			$result = $this->connection->query($query);

			if ($result == false) {
				return false;
			}

			$rows = array();

			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		}
	}
	public function getJoinData($table, $where = '', $column = '*', $join = '', $joinCondition = '')
	{
		// Base query
		$query = "SELECT {$column} FROM {$table}";

		// If there's a join
		if (!empty($join) && !empty($joinCondition)) {
			$query .= " JOIN {$join} ON {$joinCondition}";
		}

		// If there's a WHERE condition
		if (!empty($where)) {
			$query .= " WHERE {$where}";
		}

		// Order by id ASC (optional)
		$query .= " ORDER BY {$table}.id ASC";

		// Execute the query
		$result = $this->connection->query($query);

		if ($result == false) {
			return false;
		}

		// Fetch all rows
		$rows = array();
		while ($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}

		return $rows;
	}

	public function getMJoinData($tables, $where = '', $columns = '*', $joins = [], $joinConditions = [])
	{
		// Base query
		$query = "SELECT {$columns} FROM {$tables[0]}";

		// Join tables
		foreach ($joins as $index => $joinTable) {
			$query .= " JOIN {$joinTable} ON {$joinConditions[$index]}";
		}

		// WHERE clause
		if (!empty($where)) {
			$query .= " WHERE {$where}";
		}

		// Order by id ASC (optional)
		$query .= " ORDER BY {$tables[0]}.id ASC";

		// Execute query
		$result = $this->connection->query($query);

		if ($result === false) {
			return false;
		}

		// Aggregate results
		$rows = [];
		while ($row = $result->fetch_assoc()) {
			$userId = $row['id'];

			if (!isset($rows[$userId])) {
				$rows[$userId] = [
					'id' => $row['id'],
					'name' => $row['name'],
					'username' => $row['username'],
					'email' => $row['email'],
					'role_name' => $row['role_name'],
					'permissions' => []
				];
			}

			if (!empty($row['permission_name'])) {
				$rows[$userId]['permissions'][] = $row['permission_name'];
			}
		}

		// Convert permissions array to comma-separated string
		foreach ($rows as &$row) {
			$row['permissions'] = implode(', ', $row['permissions']);
		}

		return array_values($rows);
	}



	public function execute($query, $ret = "")
	{
		$result = $this->connection->query($query);

		if ($ret != "last id") {
			if ($result == false) {
				return false;
			} else {
				return true;
			}
		} else {
			$last_id = $this->connection->insert_id;
			return $last_id;
		}

	}

	public function insert($table_name, $data)
	{
		$string = "INSERT INTO " . $table_name . " (";
		$string .= implode(",", array_keys($data)) . ') VALUES (';
		$string .= "'" . implode("','", array_values($data)) . "')";
		if (mysqli_query($this->connection, $string)) {
			return true;
		} else {
			echo mysqli_error($this->connection);
		}
	}

	public function update_json_data($table, $column, $json_data, $where)
	{
		$json_data = $this->escape_string($json_data);
		$where_clause = $this->build_where_clause($where);

		$query = "UPDATE $table SET $column = '$json_data' $where_clause";

		$result = $this->connection->query($query);

		if ($result == false) {
			echo 'Error: cannot update data in table ' . $table;
			return false;
		} else {
			return true;
		}
	}

	private function build_where_clause($where)
	{
		$where_clause = 'WHERE ';
		$conditions = [];
		foreach ($where as $key => $value) {
			$conditions[] = "$key = " . $this->escape_string($value);
		}
		$where_clause .= implode(' AND ', $conditions);
		return $where_clause;
	}

	// Existing delete method
	public function delete($table, $id)
	{
		$query = "DELETE FROM $table WHERE id = $id";

		$result = $this->connection->query($query);

		if ($result == false) {
			echo 'Error: cannot delete id ' . $id . ' from table ' . $table;
			return false;
		} else {
			return true;
		}
	}

	public function record_delete($table_name, $record_no)
	{
		$query = "DELETE FROM " . $table_name . " WHERE record_no='{$record_no}'";
		if (mysqli_query($this->connection, $query)) {
			return mysqli_affected_rows($this->connection);
			  
		}
	}


	public function update($table_name, $fields, $where_condition)
	{
		$query = '';
		foreach ($fields as $key => $value) {
			$escaped_value = $this->escape_string($value);
			$query .= $key . "='" . $escaped_value . "', ";
		}
		$query = rtrim($query, ', ');

		$condition = '';
		foreach ($where_condition as $key => $value) {
			$escaped_condition_value = $this->escape_string($value);
			$condition .= $key . "='" . $escaped_condition_value . "' AND ";
		}
		$condition = rtrim($condition, ' AND ');

		$query = "UPDATE " . $table_name . " SET " . $query . " WHERE " . $condition;

		if (mysqli_query($this->connection, $query)) {
			return true;
		} else {
			return "Error: " . mysqli_error($this->connection);
		}
	}

	public function update_testimony($table_name, $fields, $where_condition)
	{
		$where_clause = $this->build_where_clause($where_condition);

		$json_data = isset($fields['testimonial']) ? $fields['testimonial'] : '';
		$json_data = $this->escape_string($json_data);

		$query = "UPDATE $table_name SET testimonial = '$json_data' $where_clause";

		if (mysqli_query($this->connection, $query)) {
			return true;
		} else {
			return "Error: " . mysqli_error($this->connection);
		}
	}



	public function escape_string($value)
	{
		return $this->connection->real_escape_string($value);
	}
	
	public function last_id()
	{
		return $this->connection->insert_id;
	}



	public function getbyid($table, $id)
	{
		$query = "SELECT * FROM $table WHERE id = ?";
		$statement = $this->connection->prepare($query);
		$statement->bind_param('i', $id);
		$statement->execute();
		$result = $statement->get_result();

		if ($result === false) {
			// Log error here
			return [
				'status' => 500,
				'message' => 'Something went wrong: ' . $this->connection->error,
			];
		}

		if ($result->num_rows === 1) {
			$row = $result->fetch_assoc();
			return [
				'status' => 200,
				'message' => 'Fetch data successfully',
				'data' => $row,
			];
		} else {
			return [
				'status' => 404,
				'message' => 'Record not found',
			];
		}
	}


	public function get_database_data_by_column($table, $id, $column)
	{

		$query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
		$result = $this->connection->query($query);

		if ($result->num_rows === 1) {
			$row = $result->fetch_assoc();
			$raw_data = [
				'status' => 200,
				'message' => 'Fetch data successfully',
				'data' => $row,
			];
		}

		if ($raw_data['status'] == 200) {
			return $raw_data['data'][$column];
		}
	}
}
