<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param array $array
	 */
	public function form_validation(array $array)
	{
		$this->form_validation->set_rules($array);
	}

	/**
	 * @param string $select
	 * @param $table
	 * @param string $where
	 * @param string $order
	 * @param string $by
	 * @param int $start
	 * @param int $limit
	 * @param bool $return_array
	 * @return mixed
	 */
	public function fetch(
		$select = "*",
		$table,
		$where = "",
		$order = "",
		$by = "DESC",
		$start = -1,
		$limit = 0,
		$return_array = false
	) {
		$this->db->select($select);
		if ($where != "") {
			$this->db->where($where);
		}

		if ($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc")) {
			if ($order == 'rand') {
				$this->db->order_by('rand()');
			} else {
				$this->db->order_by($order, $by);
			}
		}

		if ((int) $start >= 0 && (int) $limit > 0) {
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get($table);

		if ($return_array) {
			$result = $query->result_array();
		} else {
			$result = $query->result();
		}

		$query->free_result();

		return $result;
	}

	/**
	 * @param string $select
	 * @param $table
	 * @param string $where
	 * @param string $order
	 * @param string $by
	 * @param bool $return_array
	 * @return mixed
	 */
	public function get($select = "*", $table, $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$this->db->select($select);
		if ($where != "") {
			$this->db->where($where);
		}
		if ($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc")) {
			if ($order == 'rand') {
				$this->db->order_by('rand()');
			} else {
				$this->db->order_by($order, $by);
			}
		}

		$query = $this->db->get($table);

		if ($return_array) {
			$result = $query->row_array();
		} else {
			$result = $query->row();
		}

		$query->free_result();

		return $result;
	}

	/**
	 * @param string $select
	 * @param [type] $table
	 * @param $group_by
	 * @param [type] $join_table
	 * @param [type] $join_where
	 * @param string $where
	 * @param string $order
	 * @param string $by
	 * @param integer $start
	 * @param integer $limit
	 * @return void
	 */
	public function select_join($select = '*', $table, $group_by = "", $join_table, $join_where, $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0)
	{
		$this->db->select($select);

		if ($group_by != "") {
			$this->db->group_by($group_by);
		}

		$this->db->join($join_table, $join_where);
		if ($where != "") {
			$this->db->where($where);
		}
		$this->db->order_by($order, $by);

		if ((int) $start >= 0 && (int) $limit > 0) {
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get($table);

		return $query->result();
	}

	/**
	 * @param $table
	 * @param string $order
	 * @param string $by
	 * @param $like
	 * @param string $or_like
	 * @param string $where
	 * @return mixed
	 */
	public function search($table, $order = "", $by = "DESC", $like, $or_like = "", $where = "")
	{
		if ($where != "") {
			$this->db->where($where);
		}

		$this->db->group_start();
		$this->db->like($like);

		if ($or_like != "") {
			$this->db->or_like($or_like);
		}
		$this->db->group_end();

		if ($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc")) {
			$this->db->order_by($order, $by);
		}
		$query = $this->db->get($table);
		return $query->result();
	}

	/**
	 * @param $table
	 * @param string $where
	 * @return mixed
	 */
	public function counts($table, $where = "")
	{
		if ($where != "") {
			$this->db->where($where);
		}

		$query = $this->db->get($table);
		return $query->num_rows();
	}

	/**
	 *
	 * @param string $select
	 * @param [type] $table
	 * @param [type] $join_table
	 * @param [type] $join_where
	 * @param string $where
	 * @return void
	 */
	public function counts_join($select = '*', $table, $join_table, $join_where, $where = "")
	{
		if ($where != "") $this->db->where($where);
		$this->db->select($select);
		$this->db->join($join_table, $join_where);
		$query = $this->db->get($table);
		return $query->num_rows();
	}

	/**
	 * @param $table
	 * @param $where
	 * @param $select_sum
	 * @return int
	 */
	public function sum_results($table, $where = "", $select_sum)
	{
		if ($where != "") {
			$this->db->where($where);
		}

		$this->db->select_sum($select_sum);
		$query = $this->db->get($table);
		$result = $query->result();

		if ($result[0]->$select_sum > 0) {
			return $result[0]->$select_sum;
		} else {
			return 0;
		}
	}

	/**
	 * @param $table
	 * @param $data
	 */
	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
	}

	/**
	 * @param $table
	 * @param $data
	 */
	public function insert_in_batch($table, $data)
	{
		$this->db->insert_batch($table, $data);
	}

	/**
	 * @param $table
	 * @param $where
	 * @param $data
	 * @param bool $balance_add
	 * @param bool $balance_withdraw
	 * @param string $value_balance
	 * @return mixed
	 */
	public function update($table, $where, $data, $balance_add = false, $balance_withdraw = false, $value_balance = "")
	{
		if ($balance_add) {
			$this->db->set('balance', "balance+{$value_balance}", false);
		}

		if ($balance_withdraw) {
			$this->db->set('balance', "balance-{$value_balance}", false);
		}
		$this->db->where($where);

		if ($balance_add || $balance_withdraw && $value_balance != "") {
			return $this->db->update($table);
		} else {
			return $this->db->update($table, $data);
		}
	}

	/**
	 * @param $table
	 * @param $where
	 * @param $value
	 * @param $set
	 * @return mixed
	 */
	public function one_update($table, $where, $value, $set)
	{
		$this->db->set($set);
		$this->db->where($where, $value);
		return $this->db->update($table);
	}

	/**
	 * @param $table
	 * @param $where
	 */
	public function delete($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}

	/**
	 *
	 * @param [type] $query
	 * @return void
	 */
	public function query($query)
	{
		$this->db->query($query);
	}
}
