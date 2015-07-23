<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Database class
 */ 
class Database {

	/**
	 * PDO
	 *
	 * @access private 
	 */
	private static $PDO;

	/**
	 * Config
	 *
	 * @access private 
	 */	
	private static $config;

	/**
	 * Where statements
	 *
	 * @access protected 
	 */	
	protected $where;

	/**
	 * Constructor
	 * 
	 * @access public 
	 */	   		
	public function __construct() {
	
		if (!extension_loaded('pdo'))
			die('The PDO extension is required.');

		self::$config = config_load('database');
		
		self::connect();
		
	}

	/**
     * Connect
     * 
     * @access public
     */		
	public function connect() {
		
		if (empty(self::$config['driver'])) 
			die('Please set a valid database driver from database.php');
				
		$driver = strtoupper(self::$config['driver']);
		
		switch ($driver) {

			case 'MYSQL':

				try {
				
					self::$PDO = new PDO('mysql:host=' . self::$config['hostname'] . ';dbname=' . self::$config['dbname'], self::$config['username'], self::$config['password']);
					self::$PDO->query('SET NAMES ' . self::$config['char_set']);

					} catch (PDOException $exception) {

						die($exception->getMessage());
						
					}
				
				return self::$PDO;
				
			break;

			default:
				die('This database driver does not support: ' . self::$config['driver']);
				
		}
					
	}
        
	/**
	 * Executes an sql statement
	 *
	 * @access public 
	 */
     public function query($statement) {
		
		try {
			
			return self::$PDO->query($statement);
		
		} catch (PDOException $exception) {
			
			die($exception->getMessage());
			
		}
		
	}

	/**
	 * Returns the number of rows affected
	 *
	 * @access public 
	 */	
    public function row_count($statement) {
	
		try {
		
			return self::$PDO->query($statement)->rowCount();

		} catch (PDOException $exception) {

			die($exception->getMessage());
			
		}
				
    }

	/**
	 * Execute query and return all rows in assoc array
	 *
	 * @access public 
	 */	
	public function fetch_all($statement, $fetch_style = PDO::FETCH_ASSOC) {
		
		try {
			
			return self::$PDO->query($statement)->fetchAll($fetch_style);

		} catch (PDOException $exception) {

			die($exception->getMessage());
			
		}
		
	}
	
	/**
	 * Execute query and return one row in assoc array
	 *
	 * @access public 
	 */
    public function fetch_row_assoc($statement) {
	
		try {
			
			return self::$PDO->query($statement)->fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $exception) {

			die($exception->getMessage());
			
		}
		
    }

	/**
	 * Returns the id of the last inserted row
	 *
	 * @access public
	 */
	public function last_insert_id() {
		
		return self::$PDO->lastInsertId();
	
	}

	/**
	 * Builds the where statements to a sql query
	 * 
	 * @access public
	 */ 	
	public function where($value) {

		$this->where = $value;
		
		return $this;
		
	}
	
	/**
	 * Insert a value into a table
	 * 
	 * @access public
	 */ 
	public function insert($table, $values) {			
	
		try {
		
			foreach ($values as $key => $value)
				$field_names[] = $key . ' = :' . $key;

			$sql = "INSERT INTO " . $table . " SET " . implode(', ', $field_names);

			$stmt = self::$PDO->prepare($sql);
			
			foreach ($values as $key => $value)
				$stmt->bindValue(':' . $key, $value);

			$stmt->execute();

		} catch (PDOException $exception) {

			die($exception->getMessage());
			
		}
					
	}
	
	/**
	 * Update a value in a table
	 * 
	 * @access public
	 */ 
	public function update($table, $values) {

		try {
					
			foreach ($values as $key => $value)
				$field_names[] = $key . ' = :' . $key;

			$sql  = "UPDATE " . $table . " SET " . implode(', ', $field_names) . " ";
			
			$counter = 0;
			
			foreach ($this->where as $key => $value) {

				if ($counter == 0) {
					
					$sql .= "WHERE {$key} = :{$key} ";
					
				} else {
					
					$sql .= "AND {$key} = :{$key} ";
					
				}
										
				$counter++;
				
			}
			
			$stmt = self::$PDO->prepare($sql);
			
			foreach ($values as $key => $value)
				$stmt->bindValue(':' . $key, $value);
				
			foreach ($this->where as $key => $value)
				$stmt->bindValue(':' . $key, $value);

			$stmt->execute();

		} catch (PDOException $exception) {

			die($exception->getMessage());
			
		}
				
	}

	/**
	 * Delete a record
	 * 
	 * @access public
	 */ 
	public function delete($table) {
            
		$sql = "DELETE FROM " . $table . " ";
		
		$counter = 0;
		
		foreach ($this->where as $key => $value) {

			if ($counter == 0) {
				
				$sql .= "WHERE {$key} = :{$key} ";
				
			} else {
				
				$sql .= "AND {$key} = :{$key} ";
				
			}
									
			$counter++;
			
		}
		
		$stmt = self::$PDO->prepare($sql);
		
		foreach ($this->where as $key => $value)
			$stmt->bindValue(':' . $key, $value);
					
		$stmt->execute();
            
    }
        	        	
}

?>