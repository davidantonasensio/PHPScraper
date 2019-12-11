<?php
/**
 * Idanas - Commom Clases DB
 * PHP Version 7.3
 *
 * @category Idanas.common
 * @package  Idanas.common.classes
 * 
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2006 - 2019 David Anton
 * @license   http://www.gnu.org/copyleft/lesser.html 
 *            GNU Lesser General Public License
 * 
 * @link https://github.com/ The Idanas Scraper GitHub project
 * 
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */
namespace Idanas\Common;


/**
 * Class to generate SQL questions
 * Basics methods to access to the Database, connect, next_record, 
 * select database etc 
 * This class is made for MySQL and MariaDB databases 
 * Working fine on MariaDB 10.3.x and PHP 7.x
 *
 * @category Idanas.common
 * @package  Idanas.common.classes
 *
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2005 - 2019 David Anton
 * 
 * @license http://www.gnu.org/copyleft/lesser.html 
 * GNU Lesser General Public License 
 * 
 * @link https://github.com/ The Idanas Scraper GitHub project
 * 
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */
class DB
{
    /**
     * Get the connection ID nummber
     *
     * @var    Object $linkID conection ID
     * @access protected
     */
    protected $linkID;

    
    /**
     * Get the ID from the send query
     *
     * @var    Object
     * @access protected
     */
    protected $queryID;
    
    
    /**
     * Actual row in the query result
     *
     * @var    int
     * @access public
     */
    public $row;
    
    
    /**
     * Get a complet record from the Database
     *
     * @var    array
     * @access public
     */
    public $record;
    
    /**
     * Error description if any in the conexion to DB
     *
     * @var    string
     * @access public
     */
    public $error;


    /**
     * Error number if any in the conexion to DB
     *
     * @var    int
     * @access public
     */
    public $errno;
    

    /**
     * Constructor
     *
     * @access public
     */
    //function __construct()
    //{
    //}

    /**
     * Destructor
     * 
     * @return void
     */
    function __destruct()
    {
        if ($this->linkID) {
            mysqli_close($this->linkID);
        }
        
    }
    
    /**
     * Select the Database
     *
     * {@source}
     *
     * @param string $dataBase DB Name
     * 
     * @return boolean
     * @access protected
     */
    protected function selectDB($dataBase)
    {
        $select = mysqli_select_db($this->linkID, $dataBase);
        return $select;
    }
    
    
    /**
     * Send the query to database
     *
     * @param string $sql query to be sent
     * 
     * @return boolean Return true if ok or false if there are problems
     * @access public
     */
    public function query($sql)
    {
        
        if (!is_string($sql)) {
            $this->error = "Query is not a string";
            return false;
        }
        try {
            $this->queryID = $this->linkID->query($sql);
            if ($this->queryID) {
                $this->row = 0;
                return true;
            }
   
        } catch (\mysqli_sql_exception $errorHandling) {
            echo "There is a Problem with your SQL Query: \n" . $errorHandling->getMessage() . "\nExiting\n";
            return false;
            exit;
        }
        //exit;
    }
    
    
    /**
     * It goes through the records one to one
     *
     * @param int $free indicate if there are more records
     * 
     * @return bool
     * @access public
     */
    public function nextRecord($free=0)
    {
        if ($this->queryID && !$this->record = $this->queryID->fetch_assoc()) {
            DB::error();
            return false;
        }
        $this->row += 1;
        $stat = is_array($this->record);

        if (!$free) {
            if (!$stat) {
                DB::freeResult($this->queryID);
            }
        }
        return $stat;
    }
    
    
    /**
     * Return the nummber of register
     *    
     * @return integer
     * @access public
     */
    public function numRows()
    {
        $num = mysql_num_rows($this->queryID);
        return $num;
    }


    /**
     * Return the nummber of register afected
     *    
     * @return integer
     * @access public
     */
    public function numRowsAffected()
    {
        $_affected = mysql_affected_rows($this->linkID);
        return $_affected;
    }
    
    
    /**
     * Put the database pointer to the giben position
     *
     * @param integer $pos Position in the results
     * 
     * @return void
     * @access public
     */
    public function seek($pos)
    {
            $status = mysql_data_seek($this->queryID, $pos);
        if ($status) {
            $this->row = $pos;
        }
    }
    
    
    /**
     * Get the value froma field from the generate SQL result
     *
     * @param string $Name name of the field
     * 
     * @return string gibe back the value or empty
     * @access public
     */
    public function record($Name)
    {
        if (isset($this->record[$Name])) {
            return $this->record[$Name];
        } else {
            return false;
        }
    }

    
    /**
     * Will free all memory associated with the result identifier
     *
     * @return void
     * @access public
     */
    protected function freeResult()
    {
        if ($this->queryID) {
            mysql_free_result($this->queryID);
            $this->queryID = 0;
        }

    }
    
    
    /**
     * Get the error name and comment from the Database and 
     * write them to the variables
     *
     * @return string
     * @access protected
     */
    public function error()
    {
            $this->errno = mysqli_errno($this->linkID); //Depreciate
            $this->error = mysqli_error($this->linkID); //Depreciate

            return $this->error;
    }
    
    
    /**
     * Return query result Row as an object
     *
     * @return object
     * @access public
     */
    public function fetchObject()
    {
        $_row = mysql_fetch_object($this->linkID);
        return $_row;
    }
    
    
    /**
     * Return query result Row as an indexed array
     *
     * @return array
     * @access public
     */
    public function fetchRow()
    {
        $_row = mysql_fetch_row($this->linkID);
        return $_row;
    }


    /**
     * Return query result Row as an associative array
     *
     * @return array
     * @access public
     */
    public function fetchArray()
    {
        $_row = mysql_fetch_array($this->linkID);
        return $_row;
    }
    
}// End of class DB

?>
