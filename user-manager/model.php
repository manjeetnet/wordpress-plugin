<?php
	

/**
 * Model Class
 *
 */
class Model{
	
	private $db;
	private $table_name;
	private $columns;
	private $primary_key;
	private $pk_is_int;
	
	/**
     * Constructor
     *
     */
	public function __construct($table_name) {
		$this->init($table_name);
	}
	
	/**
     * Sets table name, column info and primary key
     *
     */
	public function init($table_name) {
		
		// set schema & table
		global $wpdb;
		$this->db = $wpdb;
		$this->table_name = $table_name;
	}
	
	/**
     * Returns primary key
     *
     */
	public function get_primary_key() {
		return $this->primary_key;
	}
	
	/**
     * Returns table name
     * 
     */
	public function get_table_name() {
		return $this->table_name;
	}
	
	/**
     * Returns array of column names & types
     * 
     */
	public function get_columns() {
		//return $this->columns;
		//return $columns;
	}
	
	/**
     * Returns candidate id for new record
     * 
     */
	public function get_new_candidate_id() {
		$new_id = "";

		// autoincrement if pk is integer
		/*if ($this->pk_is_int) {
			$new_id = $this->db->get_var("SELECT MAX(`$this->primary_key`)+1 FROM `$this->table_name`");
		} else {
			$new_id = $this->db->get_var("SELECT MAX(`$this->primary_key`) FROM `$this->table_name`");
			$new_id .= NEW_ID_HINT;
		}
		if ($new_id == "")	$new_id = "1";
		return $new_id;*/
	}
	
	/**
     * Select all data
     * 
     */
	public function select_all() {
		//return $this->db->get_results("");
	}

	
	
	/**
     * Select certain data
     * 
     */
	public function select($key_word, $order_by, $order, $begin_row, $end_row) {}
	
	public function selectDateTime() {}

	public function updateDateTime($vals) {}
	
	/**
     * Returns total row count
     * 
     */
	public function count_rows($key_word = "") {}
	
	/**
     * Generates where sql query
     * 
     */
     private function generate_where_query($key_word) { }
	 
	/**
     * Generates order by sql query
     * 
     */
     private function generate_order_query($order_by, $order) 
	 {}
	 
	/**
     * Returns single row
     *
     */
	public function get_row($id) 
	{}

	/**
     * Adds new record
     *
     */
	public function insert($vals) {}
	
	/**
     * Updates record
     *
     */
	public function update($vals) {}
	

	/// Move Recod
	public function moverecord($vals) {}
	/**
     * Deletes record
     *
     */
	public function delete($id) {}


	public function changeStatus($id,$p=0) {}


	
	
	/**
     * Checks validity of a table
     *
     */
	public function validate($table_name) {}
	
	/**
     * Get list of available tables in schema
     *
     */
    public function get_table_options() {}
}
?>