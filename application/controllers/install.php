<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

	// Email and Password gets displayed at the end of the installer
	var $admin_password = '';
	var $admin_email = '';

	// Some database defaults and information that needs tracking throughout the process
	var $db_driver = 'mysql';
	var $db_prefix = 'nab_';

	// Figured out in the constructor and needed for the install process
	var $base_url = '';

	/**
	 * Constructor
	 * 
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
		
		// Only show install page if there is no lock
		if ($this->config->item('installed') != 'no')
		{
			show_404();
		}
		// used for redirect
		$this->load->helper('url');				
		// used to track the status of db connection
		$this->load->library('session');
	}

	/**
	 * First Step
	 *
	 * We check if the server can be used to install NAB
	 *
	 * @access	public
	 * @param	bool
	 * @return	mixed
	 */
	function index()
	{
	
		$data['install_warnings'] = array();

		// is PHP version ok?
		if (!is_php('5.1.6'))
		{
			$data['install_warnings'][] = 'PHP version too old';
		}

		// is config file writable? we need to be sure of this before start
		if (is_really_writable($this->config->config_path) && ! @chmod($this->config->config_path, FILE_WRITE_MODE))
		{
			$data['install_warnings'][] = 'config.php file is not writable';
		}

		// Is there a database.php file?
		if (@include($this->config->database_path))
		{
			// let's test if the data contained in the file was setup before
			if ($this->_test_mysql_connection($db[$active_group]))
			{	
				// we have a connection here! save that info in the session
				$this->session->set_userdata('database_connected', TRUE);
			}
			else
			{
				// Ensure the session isn't remembered from a previous test
				$this->session->set_userdata('database_connected', FALSE);

				// We couldn't connect to the database, we need to check if we can write the database.php file.
				@chmod($this->config->database_path, FILE_WRITE_MODE);

				if (is_really_writable($this->config->database_path) === FALSE)
				{
					$data['install_warnings'][] = 'database file unwritable';
				}
			}
		}
		else
		{
			// There is no database.php file :(
			$data['install_warnings'][] = 'database.php file not found';
		}

		// No errors? let's move to the next step
		if (count($data['install_warnings']) == 0)
		{
			redirect('install/start');
		}
		else
		{
			$data['title'] = 'Install problems';		
			$data['content'] = 	$this->load->view('install/warnings', $data , TRUE);
			$this->load->view('template',$data);
		}
	}


	/**
	 * Start (firts and only step)
	 *
	 * The install process unique step
	 *
	 * @access	public
	 * @return	string
	 */
	function start()
	{		
		$this->load->helper('form');
		$this->load->library('form_validation');
				
		$this->form_validation->set_message('required', '%s field is required');		

		if ($this->session->userdata('database_connected') === FALSE)
		{
			$data['database_connected'] = FALSE; 
			// to keep forms in the form_validation.php file we set here the validation rules to use

			$this->form_validation->set_rules('host', 'host', 'required');
			$this->form_validation->set_rules('database', 'database', 'required');
			$this->form_validation->set_rules('user', 'user', 'required');
			$this->form_validation->set_rules('password', 'Password', 'callback__test_mysql_connection');
			$this->form_validation->set_rules('admin_email', 'admin', 'required|max_length[60]|valid_email');
			$this->form_validation->set_rules('admin_password', 'admin password', 'required');			

			$this->form_validation->set_message('_test_mysql_connection', 'unable to connect to mysql' );		
		}
		else
		{
			$data['database_connected'] = TRUE;
			$this->form_validation->set_rules('admin_email', 'admin', 'required|max_length[60]|valid_email');
			$this->form_validation->set_rules('admin_password', 'admin password', 'required');						
		}
		
		if ($this->form_validation->run() == TRUE)
		{
			$data = $this->_install_database();
			$this->session->set_userdata('messages', $data);			
			$data['title'] = 'Installed!';
			$data['content'] = $this->load->view('install/success', $data , TRUE);
			$this->load->view('template',$data);
		}
		else
		{
			$data['msg'] = validation_errors('<h3>', '</h3>');			
			$data['title'] = 'Welcome to our installer';
			$data['content'] = 	$this->load->view('install/index', $data , TRUE);
			$this->load->view('template',$data);
		}
	}


	// --------------------------------------------------------------------

	/**
	 * DB Driver Test
	 *
	 * Test a given driver to ensure the server can use it. We'll also create the
	 * database here if we need to.
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */
	function _test_mysql_connection($config_db)
	{
		if(!is_array($config_db))
		{
			$config_db = array();
			$config_db['hostname'] = $this->input->post('host');
			$config_db['username'] = $this->input->post('user');
			$config_db['password'] = $this->input->post('password');
			$config_db['database'] = $this->input->post('database');
			$config_db['dbdriver'] = 'mysql';	
			
		}
		// Unset any existing DB information
		unset($this->db);

		// Explicitly set debugging to FALSE to avoid CI throwing errors if its wrong
		$config_db['db_debug'] = FALSE;

		// load based on custom passed information
		$this->load->database($config_db);

		if (is_resource($this->db->conn_id) OR is_object($this->db->conn_id))
		{
			// There is a connection

			$this->load->dbutil();

			// Now then, does the DB exist?
			if ($this->dbutil->database_exists($this->db->database))
			{
				// Connected and found the db. Happy days are here again!
				return TRUE;
			}
			else
			{
				$this->load->dbforge();

				if ($this->dbforge->create_database($this->db->database))
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Do Install
	 *
	 * What it says on the tin! Installs the software
	 *
	 * @access	private
	 * @return	mixed
	 */
	function _install_database()
	{
		// Set up a variable to hold user notices.
		$data['messages'] = array();

		if($this->input->post('db_prefix'))
		{
			$this->db_prefix = $this->input->post('db_prefix');			
		}


		if ( ! $this->_setup_database())
		{
				show_error('database file is not writable');
		}


		unset($this->db);
		$this->load->database();
		$this->load->dbforge();

		$this->load->model('install_model');
		//$this->setup_model->add_tables();
		$this->install_model->use_sql_string();		

		if ( ! $this->config->config_update(array('encryption_key'=>'nab_'.random_string('alnum'), 'base_url'=>$this->base_url)))
		{
			log_message('debug', 'Not able to write config file in _install_database');
		}


		if ( ! $this->config->config_update(array('installed'=>"yes")))
		{
			$data['messages'][] = 'installed and locked';
		}

		@chmod($this->config->config_path, FILE_READ_MODE);
		@chmod($this->config->database_path, FILE_READ_MODE);

		$this->admin_password = $this->input->post('admin_password');
		$this->admin_email = $this->input->post('admin_email');

		$data['messages'][] = ' we are done! login using email: ('.$this->admin_email.') and password: (<strong>'.$this->admin_password.'</strong>)';

		return $data;
	}


	/**
	 * Setup Database (only MySQL supported now)
	 *
	 *
	 * @access	private
	 * @return	bool
	 */
	function _setup_database() 
	{
		if ($this->session->userdata('database_connected'))
		{
			return TRUE;
		}
		else
		{
			if (@include($this->config->database_path))
			{
				if ($this->_test_mysql_connection($db[$active_group]))
				{
					return TRUE;
				}
			}
		}

		$_db['hostname'] = $this->input->post('host');
		$_db['username'] = $this->input->post('user');
		$_db['password'] = $this->input->post('password');
		$_db['database'] = $this->input->post('database');
		$_db['dbdriver'] = $this->db_driver;
		$_db['dbprefix'] = $this->db_prefix;

		// Update config/database.php file
		return $this->config->db_config_update($_db); 
	}


}

/* End of file install.php */
/* Location: ./application/controllers/install.php */