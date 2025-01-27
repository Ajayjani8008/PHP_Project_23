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

		// 		$rows = array();

		// 		while ($row = $result->fetch_assoc()) {
		// 			$rows[] = $row;
		// 		}

		// 		return $rows;
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
			// return true;  
		}
	}


	// public function update($table_name, $fields, $where_condition)
	// {
	// 	$query = '';
	// 	foreach ($fields as $key => $value) {
	// 		$query .= $key . "='" . $value . "', ";
	// 	}
	// 	$query = rtrim($query, ', ');

	// 	$condition = '';
	// 	foreach ($where_condition as $key => $value) {
	// 		$condition .= $key . "='" . $value . "' AND ";
	// 	}
	// 	$condition = rtrim($condition, ' AND ');

	// 	$query = "UPDATE " . $table_name . " SET " . $query . " WHERE " . $condition;

	// 	if (mysqli_query($this->connection, $query)) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }
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
