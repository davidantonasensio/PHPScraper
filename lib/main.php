<?php
/**
 * Idanas - Commom Clases main
 * PHP Version 7.3
 * 
 * @category Idanas.common
 * @package  Idanas.common.classes
 *
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2005 - 2019 David Anton
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
 * Idanas class of main basic functions
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
class Main
{
 
    /**
     * This Array take temporal values that the aplication need for some reason
     * We can write to this variable using the method writeVariable on this class
     *
     * @var    array
     * @access private
     */
    static private $_variableArray = array();

    
    /**
     * This array take all the configuration variables for the application
     * It has two dimensions. The first ist the type of variabel. Look 
     * $_typesArray The second is the name of the variable.
     *
     * @var    array
     * @access private
     */
    static private $_config = array();

    
    /**
     *    Array with the valid types for the configuration array
     *    config_file -> configuration parameters wich come from a file
     *    config_DB -> Configuration parameters wich come from a Database
     *    config_live -> Configuration parameters wich are giben live while the 
     *      aplication is runing. In Code, or coming from the user
     *    config_url -> Parameters wich come thrugh the url post or get
     *    config_other -> All the configuration parameters wich not fit in any of 
     *      the other categories
     *
     * @var    array
     * @access private
     */
    static private $_typesArray = array(1 => "config_file", "config_DB", "config_live", "config_other", "config_url");
        
    
    /**
     * Constructor main Class
     *
     *    {@source}
     *
     * @param array $idanasConf All the config variables in conf/idanas.php
     * 
     * @return void
     * @access public
     */
    public function __construct($idanasConf)
    {
        $this->conf = $idanasConf; 
        Main::getPost();   

    }

    
    /**
     * This function get in an array all the variables that come from
     * POST or GET transmisions
     *
     * @return void
     * @access private
     */
    public function getPost()
    {
        /**
         * GET
         * write al the variables wich come through GET mode inside of the 
         * configuration array _config[] First look on the global array _GET, 
         * newer versions of PHP. Then look on the global array HTTP_GET_VARS
         */
        if (isset($_GET) && count($_GET) > 0) {
            foreach ($_GET as $_name=>$_value) {
                $filteredValue = filter_input(INPUT_GET, $_name, FILTER_SANITIZE_ENCODED);
                self::$_config[self::$_typesArray[5]][$_name]=$filteredValue;
            }
        
        } elseif (isset($HTTP_GET_VARS) && count($HTTP_GET_VARS) > 0) {
            foreach ($HTTP_GET_VARS as $_name=>$_value) {
                $filteredValue = filter_input(INPUT_GET, $_name, FILTER_SANITIZE_ENCODED);
                self::$_config[self::$_typesArray[5]][$_name] =  $filteredValue;
            }
        }
        
        /**
         * POST
         * write al the variables wich come through POST mode inside of the 
         * configuration array _config[] First look on the global array _POST, 
         * newer versions of PHP. Then look on the global array HTTP_POST_VARS
         */
        if (isset($_POST) && count($_POST)>0) {
            foreach ($_POST as $_name=>$_value) {
                $filteredValue = filter_input(INPUT_POST, $_name, FILTER_SANITIZE_ENCODED);
                self::$_config[self::$_typesArray[5]][$_name] = $filteredValue;
            }
        
        } elseif (isset($HTTP_POST_VARS) && count($HTTP_POST_VARS) > 0) {
            foreach ($HTTP_POST_VARS as $_name=>$_value) {
                $filteredValue = filter_input(INPUT_POST, $_name, FILTER_SANITIZE_ENCODED);
                self::$_config[self::$_typesArray[5]][$_name] = $filteredValue;
            }
        }

        return self::$_config;
    }
    
    
    /**
     * This method write variable and its values to a array to reuse it 
     * somewhere on the application
     *
     * @param string $name  of the varible to store in the array
     * @param string $value for the variable name
     * 
     * @return void
     * @access public
     */
    static public function writeVariable($name, $value)
    {
            self::$_variableArray[$name] = $value;
    }

    
    
    
    /**
     * Return the value of a variable writen in the array for method writeVariable
     *
     * @param string $name of the varible to search for
     * 
     * @return the value for the giben name or 0 if not found
     * @access public
     */
    static public function readVariable($name)
    {
    
        if (isset(self::$_variableArray[$name])) {
            return self::$_variableArray[$name];
        } else {
            return false;
        }    
    }
    
    
    
    /**
     * Load the template especificated for $template
     *
     * @param string $template Name of the template file to include
     * 
     * @return 1 if file ist found and 0 if isn't
     * @access public
     */
    static public function template($template)
    {
    
        $_file = self::getConfig("config_live", "tplroot") . $template . ".tpl";
        
        if (file_exists($_file)) { 
            return $_file;
            
        } else {
            $_file = self::getConfig("config_live", "tplroot") . "default" . ".tpl";
            if (file_exists($_file)) {
                return $_file;
            } else {
                echo "<br>Error loading the template: <b>" 
                    . $template . ".tpl" . "</b><br>";
                exit;
            }
            
            return false;
        }
    }
    
    
    
    /**
     * The methode gibe back the value of the giben config variable. 
     * If type or variable doesn't exist then give a error
     *
     * @param string $type Type of the configuration data
     * @param string $name Variable name
     * 
     * @return string variable value or error string
     * @access public
     */
    static public function getConfig($type, $name)
    {
        
        if (array_search($type, self::$_typesArray) >= 1) {
            
            if (array_key_exists($type, self::$_config)) {
                
                if (array_key_exists($name, self::$_config[$type])) {
                    
                    return self::$_config[$type][$name];
                    
                } else {
                    
                    return false;
                    
                }
                
            } else {
                //return "Error: Name \"$name\" not found in \"$type\" ";
                return false;
            }
        } else {
            //return "Error: Type \"$type\" isn't a correct type";
            return false;
        }
        
    }


    /**
     * With this function we can check if the config parameter exist
     *
     * @param string $name false or true
     * 
     * @return boolean
     * @access public|private|protected
     */
    static public function isConfig($name)
    {

        $type = self::$_typesArray;
        $found = 0;
        foreach ($type as $value) {
            if (array_key_exists($name, self::$config[$value])) {
                return $value;
            }
        }
        return false; 
        
    }
    


    /**
     * Variables with have some configuration meaning.
     * The TYPE/origin of the variable may be
     * Type config_file => For configuration variables witch come from a configuration file 
     * Type config_DB => For configuration variables witch come from a Database 
     * Type config_live => For konfiguration variables witch come from a script in execution 
     * Type config_other => For configuration variables witch come from another origin not over writen
     *
     * @param string $type to differenciate from section of configuration
     * @param string $name variable name
     * @param string $data value of the variable
     * 
     * @return string
     * @access public
     */
    static public function setConfig($type, $name, $data)
    {
        if (array_search($type, self::$_typesArray) >= 1) {
            self::$config[$type][$name] = $data;
            return 1;
        } else {
            return "The given type doesn't exist \"$type\" ";
        }
        
    }




    /**
     * Here we can have a look to the complet array
     *
     * @param string $type to choose witch of the 4 array we wish to see 
     *                     "config_file", "config_DB", "config_live", "config_other" 
     *                     , "all" to see everything
     * 
     * @return string
     * @access public
     */
    static public function getConfigArray($type)
    {        
        if ($type == "all") {
            return self::$config;
        } elseif (array_search($type, self::$_typesArray) >= 1) {
            return self::$config[$type];
        }
        return "Error: false type or type still don't exist \"$type\" ";
    }
    
}
