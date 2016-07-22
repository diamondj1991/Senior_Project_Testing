<?php
class DatabaseConnect {
    
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $dbconnect;
    private $query;
	
	public function __construct($host, $user, $pass, $database)
	{
		$this->servername = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->dbname = $database;
		
		$this->connect();
	}
	private function connect()
	{
		$this->dbconnect = @new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        if ($this->dbconnect->connect_errno) {
             echo "Failed to connect to MySQL: " . $this->dbconnect->connect_error;
        }
	}
	public function query($query)
	{
		if(isset($this->dbconnect))
		{
			$this->query = $query;
			return @mysqli_query($this->dbconnect, $this->query);
		}
	}
    public function getDbConnect() {
        return $this->dbconnect;
    }
}


//$c = new DatabaseConnect('localhost','root', '', 'senior_project');
//$c->query("INSERT INTO client (company_name, company_phone, company_email) VALUES ('DDD', 4444444444, 's@c.com')");

?>