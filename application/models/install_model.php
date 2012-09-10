<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class install_model extends CI_Model{

	/**
	 * Constructor
	 * 
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$this->sql_path		= APPPATH.'models/install.sql';

		// load the file helper
		$this->load->helper('file');

		$this->load->helper('url');				
		// used to track the status of db connection
		$this->load->library('session');
	}

	
	/*
	* Adds the basic table structure to the database
	*/
	function add_tables()
	{
		if ($this->db->table_exists('test'))
		{
			$this->dbforge->drop_table('test');
		}

		/* Start the attempts table */
		if ( !$this->db->table_exists('test') ){
			$fields =  array(
				'id' =>array(
					'type' => 'MEDIUMINT',
					'constraint' => '8',
					'auto_increment' => TRUE
				),
				'name' => array(
					'type' => 'VARCHAR',
					'constraint' => '20',
					'null' => FALSE
				),
				'value' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'null' => FALSE
				)
			);
		
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('test');
		}
	}
	
	public function use_sql_string()
	{
		$sql = read_file($this->sql_path);
		// Trim it
		$sql = trim($sql);
	   	$sql = $this->db->_prep_query($sql);
		$link = @mysqli_connect($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);		
		mysqli_multi_query($link, $sql);		
	}
}	
?>