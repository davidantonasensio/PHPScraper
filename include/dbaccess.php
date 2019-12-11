<?php
/**
 * Idanas - Commom Clases DBaccess
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

require_once "../include/" . "db.php";

/**
 * Class extend DB class in db.php
 * It just use the acces information
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
class DBaccess extends DB
{
    
    /**
     * The user define the right to access the DB
     * extend DB in db.php
     *
     * @var    string
     * @access public
     */
    public $userRight;
    
    /**
     * DB host name
     *
     * @var    string
     * @access public
     */
    public $host;
    
    /**
     * DB user name
     *
     * @var    string
     * @access public
     */
    public $user;

    /**
     * DB password password
     *
     * @var    string
     * @access public
     */
    public $pass;
    
    /**
     * DB name to access to
     *
     * @var    string
     * @access public
     */
    public $database;   

    /**
     * Databese conections error message
     *
     * @var    string
     * @access public
     */
    public $errorMysqlConnection;   

    /**
     * Databese conections error Object
     *
     * @var    string
     * @access private
     */
    private $_errorHandling;   
    
    /**
     * Constructor DBaccess Class
     * Try to connect to the giben host with the giben user name and password
     *
     * @param string $userRight                access rights for user
     * @param string $database                 DB Name
     * @param Object $mainBasicFunctionsIdanas Idanas basic funtions
     * 
     * @return Object connection ID
     * @access public
     */
    function __construct($userRight="ro", $database="", $mainBasicFunctionsIdanas="")
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->main = $mainBasicFunctionsIdanas;
        $this->userRight = $userRight;
        $this->errorMysqlConnection = "";
        
        DBaccess::_accessRigths();

        try {
            $this->linkID = new \mysqli(
                $this->host, $this->user, $this->pass, $this->database
            ); 
            DBaccess::selectDB($this->database);
            return $this->linkID;

        } catch (\mysqli_sql_exception $_errorHandling) {             

            print($_errorHandling->getMessage() . "\n" );  
            print("In file: ". $_errorHandling->getFile() . "\n" );           
            switch($_errorHandling->getCode()){
            case 2002:
                print("There is a error with the HOST\n" );        
                print("ERROR: ". $_errorHandling->getCode() . "\n" );  
                break;                
            case 1045:
                print(
                    "There is a error with the user name or password\n"
                );            
                print("ERROR: ". $_errorHandling->getCode() . "\n" );  
                break;
            case 1044:
                print("There is a error with the database name\n" );            
                print("ERROR: ". $_errorHandling->getCode() . "\n" );  
                break;
            default:
                print("There is a error conecting to the dateabank\n" );            
                print("ERROR: ". $_errorHandling->getCode() . "\n" );  
                break;                    
            }
            //var_dump($_errorHandling);
            $this->errorMysqlConnection = $_errorHandling->getCode();
            //return $errorCode;
            echo "Exiting";
            exit;

        }

    }
    
    /**
     * Class to choos the access parameter depending of userRight
     *
     * @return boolean
     * @access private
     */
    private function _accessRigths()
    {    
        if (!isset($this->main->conf["DB_HOST"])) {
            $this->errno = 10001;
            $this->error = "I can't open file";
            return 0;
        }
            
        switch ($this->userRight)
        {
        case "ro":
            $this->host = $this->main->conf["DB_HOST"];
            $this->user = $this->main->conf["DB_USER"];
            $this->pass = $this->main->conf["DB_PASSWORD"];
            if ($this->database == "") {
                $this->database = $this->main->conf["DB_DBNAME"];
            } else {
                $this->database = $this->database;
            }
            break;
        case "wr":
            $this->host = $this->main->conf["DB_HOSTWR"];
            $this->user = $this->main->conf["DB_USERWR"];
            $this->pass = $this->main->conf["DB_PASSWORDWR"];
            if ($this->database == "") {
                $this->database = $this->main->conf["DB_DBNAMEWR"];
            } else {
                $this->database = $this->database;
            }
            break;
        default:
            
        }
        return 1;
        
    }
    
    

}
?>
