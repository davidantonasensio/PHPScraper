<?php
/**
 * Idanas - Commom Clases Auth
 * PHP Version 7.3
 * 
 * @category Idanas.common
 * @package  Idanas.common.classes
 *
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2006 - 2019 David Anton
 * @license   http://www.gnu.org/copyleft/lesser.html 
 * GNU Lesser General Public License 
 * 
 * @link https://github.com/ The Idanas Scraper GitHub project
 * 
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */
namespace Idanas\Common;


/**
 * Idanas class for authentification
 * 
 * @category Idanas.common
 * @package  Idanas.common.classes
 *
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2005 - 2019 David Anton  * 
 * @license   http://www.gnu.org/copyleft/lesser.html 
 * GNU Lesser General Public License 
 * 
 * @link https://github.com/ The Idanas Scraper GitHub project
 * 
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */
class Auth
{
    /**
     * Password of the user to authenticate
     *
     * @var    string
     * @access public
     */
    public $pass;
    
    /**
     * Module to chech authorisation for
     *
     * @var    string
     * @access public
     */
    public $module;
    
    /**
     * Constructor Auth Class
     * This Class is used to authentificate users and check their rights
     *
     * @param string $module   Name of the module to access
     * @param object $main     All the main Functions
     * @param string $user     User Name
     * @param string $password User Password
     * 
     * @return void
     * @access public
     */
    public function __construct($module, $main, $user = '', $password = '')
    {
        $this->main = $main;
        $this->module = $module;
        
        /**
         * First we check if the global variable already exist. 
         * If not we ask to authenticate
         */
        //if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        if (!isset($user) || !isset($password)) {
            Auth::_headerSend("Idanas PHPScraper - Please authenticate yourself");
                
            /**
             * If variable exist then check if the user exist in DB
             */
        } elseif (Auth::_checkUser($user) == false) {
            Auth::_headerSend(
                "Sorry but your email, password or IP are wrong. Please try again 1"
            );
                
            /**
             * If the name is a valid then we check the password
             * 
             * To hash a password
             * $hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT)
             * 
             * To verify a password
             * password_verify($loginPassword, $hashedPassword)
             */
        } elseif (!password_verify($password, $this->pass)) {
            Auth::_headerSend(
                "Sorry but your email, password or IP are wrong. Please try again 2"
            );
                
            /**
             * Ok, user and passwords are correct 
             */
        } 
    }   
    
    
    /**
     * Send a 401 header to provoque a login window to apear
     *
     * @param string $text to show in login window
     * 
     * @return void
     * @access private
     */
    private function _headerSend($text)
    {
        Header("WWW-Authenticate: Basic realm=\"$text\"");
        Header("HTTP/1.0 401 Unauthorized");
        echo "Sorry but you must Authenticate<br>Please contact the system 
             administrator to get your password<br> 
             or to check out of there is a problem with your ip: " 
             . $_SERVER["REMOTE_ADDR"];
        exit;
    }
    
    
    /**
     * After the user loged in, this function look in the DB for the user and 
     * his pass and put all his parameters in an array
     *
     * @param string $name User name
     * 
     * @return boolean false if not found and true if found
     * @access private
     */
    private function _checkUser($name)
    {    
        $DB = new DBaccess("ro",  "idanasSEO", $this->main);
        
        $sql = " SELECT name, pass, ip, sitespy, posspy, start, idanasdb 
                  FROM users ";
        $_where = " WHERE name = " . "'$name'";   
        if ($DB->query($sql . $_where)) {            
            if ($DB->nextRecord()) {
                $this->pass = $DB->record("pass");
                $this->ip = $DB->record("ip");
                $this->moduleaccess = $DB->record($this->module);
            }
        }
                
        if (isset($this->pass)) {            
            /**
            *   If there is an ip address in the DB check if it is correct
            */
            if (isset($this->ip) && $this->ip != "") {
                if ($this->ip == $_SERVER["REMOTE_ADDR"]) {
                    return true;
                } else {
                    return false;
                    exit;
                }
            } else {
                return true;
            }            
        } else {
            return false;
        }
    }
    
    
}

?>
