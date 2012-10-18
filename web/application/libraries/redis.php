<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Redis
 *
 * A CodeIgniter library to interact with Redis
 *
 * @package        	CodeIgniter
 * @category    	Libraries
 * @author        	Joël Cox
 * @link 			https://github.com/joelcox/codeigniter-redis
 * @link			http://joelcox.nl		
 * @license         http://www.opensource.org/licenses/mit-license.html
 */
class Redis {
	
	/**
	 * CI
	 *
	 * CodeIgniter instance
	 * @var 	object
	 */
	private $_ci;
	
	/**
	 * Connection
	 *
	 * Socket handle to the Redis server
	 * @var		handle
	 */
	private $_connection;

	/**
	 * Debug
	 *
	 * Whether we're in debug mode
	 * @var		bool
	 */
	public $debug = FALSE;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		
		log_message('debug', 'Redis Class Initialized');
		
		$this->_ci = get_instance();
		$this->_ci->load->config('redis');
		
		// Connect to Redis
		$this->_connection = @fsockopen($this->_ci->config->item('redis_host'), $this->_ci->config->item('redis_port'), $errno, $errstr, 3);
		
		// Display an error message if connection failed
		if ( ! $this->_connection)
		{
			show_error('Could not connect to Redis at ' . $this->_ci->config->item('redis_host') . ':' . $this->_ci->config->item('redis_port'));	
		}
	
		// Authenticate when needed
		$this->_auth();
		
	}
	
	/**
	 * Call
	 *
	 * Catches all undefined methods
	 * @param	string	command to be run
	 * @param	mixed	arguments to be passed
	 * @return 	mixed
	 */
	public function __call($method, $arguments)
	{
		if ( ! isset($arguments[0]))
		{
		    return $this->command(strtoupper($method));
		}
		if (is_array($arguments[0]))
		{
		    return $this->command(strtoupper($method), $arguments[0]);
		}
		if (isset($arguments[1])) 
		{
		    return $this->command(strtoupper($method) . ' ' . $arguments[0], $arguments[1]);            
		}
		return $this->command(strtoupper($method) . ' ' . $arguments[0]);
	}
	
	/**
	 * Command
	 *
	 * Generic command function, just like redis-cli
	 * @param	string	$cmd  command to be executed
	 * @param   mixed   $data Extra data to send (string, array)
	 * @return 	mixed
	 */
	public function command($cmd, $data = NULL)
	{
		$request = $this->_encode_request($cmd, $data);
		return $this->_write_request($request);
	}

	/**
	 * Auth
	 *
	 * Runs the AUTH command when password is set
	 * @return 	void
	 */
	private function _auth()
	{
		
		$password = $this->_ci->config->item('redis_password');
		
		// Authenticate when password is set
		if ( ! empty($password))
		{
				
			// Sent auth command to the server
			$request = $this->_encode_request('AUTH ' . $password);
			 
			// See if we authenticated successfully
			if ( ! $this->_write_request($request))
			{
				show_error('Could not connect to Redis, invalid password');
			}
			
		}
		
	}
	
	/**
	 * Write request
	 *
	 * Write the formatted request to the socket
	 * @param	string 	request to be written
	 * @return 	mixed
	 */
	private function _write_request($request)
	{
		
		if ($this->debug === TRUE)
		{
			log_message('debug', 'Redis unified request: ' . $request);
		}
		
		fwrite($this->_connection, $request);
		return $this->_read_request();
		
	}
	
	/**
	 * Read request
	 *
	 * Route each response to the appropriate interpreter
	 * @return 	mixed
	 */
	private function _read_request()
	{
		
		$type = fgetc($this->_connection);

		if ($this->debug === TRUE)
		{
			log_message('debug', 'Redis response type: ' . $type);
		}
		
		switch ($type)
		{
			case '+':
				return $this->_single_line_reply();
				break;
			case '-':
				return $this->_error_reply();
				break;
			case ':':
				return $this->_integer_reply();
				break;
			case '$':
				return $this->_bulk_reply();
				break;
			case '*':
				return $this->_multi_bulk_reply();
				break;
			default:
				return FALSE;
		}
		
	}
	
	/**
	 * Single line reply
	 *
	 * Reads the reply before the EOF
	 * @return 	mixed
	 */	
	private function _single_line_reply()
	{
		$value = trim(fgets($this->_connection));
	
		return $value;
		
	}
	
	/**
	 * Error reply
	 *
	 * Write error to log and return false
	 * @return 	bool
	 */
	private function _error_reply()
	{
		
		// Extract the error message
		$error = substr(fgets($this->_connection), 4);
		log_message('error', 'Redis server returned an error: ' . $error);
		
		return FALSE;
		
	}
	
	/**
	 * Integer reply
	 *
	 * Returns an integer reply
	 * @return 	int
	 */
	private function _integer_reply()
	{
		return (int) fgets($this->_connection);
	}
	
	/**
	 * Bulk reply
	 *
	 * Reads to amount of bits to be read and returns value within the pointer and the ending delimiter
	 * @return 	string
	 */
	private function _bulk_reply()
	{
		
		// Get the amount of bits to be read
		$value_length = (int) fgets($this->_connection);
		if ($value_length <= 0) return NULL;
		
		$response = rtrim(fread($this->_connection, $value_length + 1));
		fgets($this->_connection);			// Get rid of the \n\r
				
		return isset($response) ? $response : FALSE;
		
	}
	
	/**
	 * Multi bulk reply
	 *
	 * Reads an n amount of bulk replies and return them as an array
	 * @return 	array
	 */
	private function _multi_bulk_reply()
	{

		// Get the amount of values in the response
		$total_values = (int) fgets($this->_connection);
				
		// Loop all values and add them to the response array
		for ($i = 0; $i < $total_values; $i++)
		{
			// Move the pointer to correct for the \n\r
			fgets($this->_connection, 2);
			$response[] = $this->_bulk_reply();
		}
		
		return isset($response) ? $response : FALSE;
		
	}

	/**
	 * Encode request
	 *
	 * Encode plain-text request to Redis protocol format
	 * @link 	http://redis.io/topics/protocol
	 * @param 	string 	request in plain-text
	 * @param   string  additional data (string or array, depending on the request)
	 * @return 	string 	encoded according to Redis protocol
	 */
	private function _encode_request($request, $data = NULL)
	{
		$slices = explode(' ', rtrim($request, ' '));
		$arguments = count($slices);

		if ($data !== NULL)
		{
			if (is_array($data))
			{
				$arguments += (count($data) * 2);
			} 
			else 
			{
				$arguments ++;
			}
		}
		
		$request = '*' . $arguments . "\r\n";
		foreach ($slices as $slice)
		{
			$request .= '$' . strlen($slice) . "\r\n" . $slice ."\r\n";
		}
		
		if ($data !== NULL)
		{
			if (is_array($data)) 
			{
				foreach ($data as $key => $value)
				{
					$request .= '$' . strlen($key) . "\r\n" . $key . "\r\n";
					$request .= '$' . strlen($value) . "\r\n" . $value . "\r\n";
				}
			}
			else 
			{
				$request .= '$' . strlen($data) . "\r\n" . $data . "\r\n";
			}
		}
		return $request;
	}
	
	/**
	 * Info
	 *
	 * Overrides the default Redis response,
	 * so we return a nice array instead of a nasty string.
	 * @return 	array
	 */
	public function info()
	{
		$response = $this->command('INFO');
		$data = array();
		$lines = explode("\r\n", $response);

		// Extract the key and value
		foreach ($lines as $line)
		{
			$parts = explode(':', $line);
			if (isset($parts[1])) $data[$parts[0]] = $parts[1];
		}
		
		return $data;
	}
		
	/**
	 * Debug
	 *
	 * Set debug mode
	 * @param	bool 	set the debug mode on or off
	 * @return 	void
	 */
	public function debug($boolean)
	{
		$this->debug = (bool) $boolean;
	}
	
	/**
	 * Destructor
	 *
	 * Kill the connection
	 * @return 	void
	 */
	function __destruct()
	{
		fclose($this->_connection);
	}
	
}
