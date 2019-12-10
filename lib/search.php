<?php
/**
 * Idanas - PHP Search Engine Scraper, PHPScraper Class
 * PHP Version 7.3
 *
 * @category Idanas_SEO_Scripts
 * @package  Idanas_SEO_PHPScraper
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
namespace Idanas\PHPScraper\Seo;

/**
 * Class to search keywords of a Domain by Google
 * 
 * @category Idanas_SEO_Scripts
 * @package  Idanas_SEO_PHPScraper
 *
 * @author    David Anton (idanas) <d.anton@idanas.de>
 * @copyright 2006 - 2019 David Anton  * 
 * @license   http://www.gnu.org/copyleft/lesser.html 
 * GNU Lesser General Public License 
 * 
 * @link https://github.com/ The Idanas Scraper GitHub project
 * 
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */
class PHPScraper
{
    /**
     * String with result of the search
     *
     * @var
     * @access private
     */
    //private $postions;
    
    /**
     * Amount of words to check
     *
     * @var    int
     * @access private
     */
    private $_allwords;
    
    /**
     * Array with all the keywords to check
     *
     * @var    array
     * @access private
     */
    private $_keywordsList;    
    
    /**
     * Language abreviation, de, es, all, etc
     *
     * @var    string
     * @access public
     */
    private $_lang;

    /**
     * Google parameter for language
     *
     * @var    string
     * @access private
     */
    private $_language;
    
    /**
     * Google parameter for country
     *
     * @var    string
     * @access private
     */
    private $_land;

    /**
     * Google parameter for word search. Correct traduction
     *
     * @var    string
     * @access private
     */
    private $_search;

    /**
     * Site name without www. f.e. idanas.de
     *
     * @var    string
     * @access private
     */
    private $_site;    
    
    /**
     * Number of pages from Google to check
     *
     * @var    integer
     * @access private
     */
    private $_pages;
    
    /**
     * Prozent of top positions from the total of words
     *
     * @var    integer
     * @access public
     */
    public $prozent;
    
    /**
     * String to search for the snippets
     *
     * @var    string
     * @access public
     */
    private $_snippets1, $_snippets21, $_snippets22, $_snippets3;

    /**
     * Constructor   
     *
     * @param $snippets1 Begining of group of SERPs 
     * @param $snippets2 End of the group of SERPs 
     * @param $snippets3 Begining of each Snippet 
     * 
     * @return array
     * @access public
     */
    public function __construct(
        $snippets1='', 
        $snippets2='',
        $snippets3=''
    ) {
        $this->_snippets1 = $snippets1;
        $this->_snippets2 = $snippets2;
        $this->_snippets3 = $snippets3;
        //echo 'snippets1aaa: ' . $_snippets1 . '';
        //exit;
    }


    /**       
     * Funtion build the URL Query
     *      
     * @param $site            string Domain Name to search for
     * @param $keywords        array KW List
     * @param $lang            string Language for the search
     * @param $pages           int Nummber of pages to search in Search Engine
     * @param $country         string En pais en el que vamos a buscar en Google
     * @param $queriesCounter  int Contador total de queries que se envian a Google
     * @param $starttime       string Tiempo total de ejecución de la script
     * @param $recordStartTime string Tiempo total de ejecución de la script
     * 
     * @return void
     * @access public
     */
    public function buildURL(        
        $site,
        $keywords,
        $lang="all",
        $pages=2,
        $country="en",
        //$KWCounter=0,
        $queriesCounter=0,
        $starttime=0,
        $recordStartTime=0
    ) {

        $this->_lang = $lang;
        $this->_site = $site;
        $this->_pages = $pages;
        $this->country = $country;            
        $this->queriescounter = $queriesCounter;
        $this->starttime = $starttime;
        $this->record_starttime = $recordStartTime;



        $this->keywordscounter = 0;


        $this->starttime = time();
        
        if ($this->country == 'all' || $this->country == 'de') {
            $this->country = 'en';
            $this->domain = 'com';
        } else {
            $this->domain = $this->country;           
        }
        
        //echo "keywordscounter: " . $this->keywordscounter . "\n";
        //if (!isset($this->keywordscounter)) {
            //$this->keywordscounter = 0;
        //}
        
        //$this->_lang = "all";
        switch($this->_lang) {
        case "de":
            $this->_language = '&lr=lang_de'; //paginas en Aleman
            $this->_land = '&hl=de'; // from wich country are we looking for
            $this->_search = '&btnG=Suche';
            $this->domain = 'www.google.de';
            break;
        case "es":
            $this->_language = '&lr=lang_es'; //paginas en Espanol
            $this->_land = '&hl=es';
            $this->_search = '&btnG=B%C3%BAsqueda';
            $this->domain = 'www.google.es';
            break;
        case "fr":
            $this->_language = '&lr=lang_fr'; //paginas en Francés
            $this->_land = '&hl=fr';
            $this->_search = '&btnG=Rechercher';
            $this->domain = 'www.google.fr';
            break;
        case "it":
            $this->_language = '&lr=lang_it'; //paginas en Italiano
            $this->_land = '&hl=it';
            $this->_search = '&btnG=Cerca';
            $this->domain = 'www.google.it';
            break;
        case "all":
            $this->_language = '&lr='; // Search the coplete web
            $this->_land = '&hl=en';
            $this->_search = '&btnG=Search';
            $this->domain = 'www.google.com';
            break;
        default:
            $this->_language = '&lr='; // Search the coplete web
            $this->_land = '&hl=en';
            $this->_search = '&btnG=Search';
            $this->domain = 'www.google.com';
        
        }
        //echo '_land1111111: '.$this->_land.'<br>';
    
        
        //$keywords = str_replace("%0D%0A", "<<->>", urlencode($keywords));
        $keywords = str_replace("\n", "<<->>", $keywords);
        //echo "keywords: " . $keywords . "<br>";
        $this->_keywordsList = explode("<<->>", $keywords);
        $this->_allwords = count($this->_keywordsList);

       
    }//end of fuction __constructor

    /**       
     * Destructor     *      
     * 
     * @return void
     * @access public
     */
    public function __destruct()
    {

    }

    /**
     * Builds the final Query to send to the search engines
     *
     * @param $searchQuery URL query 
     * @param $pageNumber  Page nummber of the search engine
     * 
     * @return string
     * @access private
     */
    private function _searchQuery($searchQuery, $pageNumber)
    {

        //echo 'KW: '.$searchQuery.'<br>';
        //echo 'pageNumber: '.$pageNumber.'<br>';
        //echo '_land: '.$this->_land.'<br>';

        if ($pageNumber == 1) {

            $_querylink = '/search?' . $this->_land . $searchQuery 
                . $this->_search . $this->_language;

        } else {

            $_querylink = '/search?' . $searchQuery. $this->_land 
                . $this->_language . '&start=' . $pageNumber . '&sa=N';

        }

        return  $_querylink;

    }
    
    /**
     * This funtion look in the search engine the given words 
     * returns a array with the results and links to Search engine
     * if wor ist found
     *
     * @return array
     * @access private
     */
    public function results()
    {        
        //$_positions = "";        
        $counterTopPositions = 0;
        $this->baneocounter = 0;
        $postionsArray = array();

        foreach ($this->_keywordsList as $_key => $_value) {
            //echo "wordsAAAAAA: " . $_value . "<br>";

            $_value = trim($_value);
            if ($_value == "") {
                $this->_allwords--;
                continue;
            }
            $this->palabraclave = $_value;    
            //echo "value: " . str_replace("\n", "", $value) . "<br>";
            
            $_value = str_replace("\\", "", $_value);
            //echo "value: " . $_value . "<br>";
            
            //$this->_searchQuery($_value);
            //exit;

            $searchQuery = "&q=" . urlencode($_value);
            
            //$_found = 0;
            //$_readpage = false;
            
            for ($i=1; $i<=$this->_pages; $i++) {
                $pageNumber = $i * 10;
                $i > 1 ? $pageNumber = ($i-1) * 10 : $pageNumber = 1;
                //echo "pagenumber: " . $pagenumber . "<br>";
                //$this->pagenumber = $pageNumber;
                $_querylink = PHPScraper::_searchQuery($searchQuery, $pageNumber);
               
                $_handle = "";
                $line = "";
                

                // Alguna información de la evolución del proceso
                $actualtime = time();
                $tiempoejecucion = PHPScraper::timeDifference(
                    $actualtime - $this->starttime
                );
                
                /**
                 * Stops script execution to avoid beeing baned
                 */                 
                $segundos = mt_rand(15, 30);
                //sleep($segundos);
                
                $i2 = $i;

                /**
                 * Si la palabra esta en la página de Google entonces ponemos el 
                 * registro en la tabla If word found in search engine we 
                 * put the KW in the array
                 */
                $this->position = "0";
                $absolutPosition = array();
                $foundKW = PHPScraper::_httpRequest(
                    'GET', $this->domain, 80, $_querylink, $pageNumber
                );
                
                if ($foundKW) {
                    if ($this->position > 0) {
                        if ($i > 1) {
                            $absolutPosition[$this->domain.$_querylink] 
                                = $this->position+(( $i-1 ) * 10);
                        } else {
                            $absolutPosition[$this->domain.$_querylink] 
                                = $this->position;
                        }                        
                    } else {
                        $absolutPosition[$this->domain.$_querylink] 
                            = 'TOP ' . $i * 10;
                    }
                    $postionsArray[$_value] = $absolutPosition;
                   
                    $i=100; // to avoid continue searching
                    
                    $counterTopPositions++;
                    break;
                } else {
                    $absolutPosition[$this->domain.$_querylink] 
                        = "NOT TOP: " . ($i2 * 10);
                }   
            }

            $postionsArray[$_value] = $absolutPosition;
            
        }

        /*
        print "\n\nPositions array <pre>";
        print var_dump($postionsArray);
        print "</pre>\n\n";
        */
        
        $this->prozent = floor((($counterTopPositions * 100)/$this->_allwords));
        return $postionsArray;

    }//End of function results

    /**
     * This funtion select a random User Agent
     *
     * @return string
     * @access private
     */
    private function _getUserAgent()
    {
        $useragent = mt_rand(1, 7);

        switch ($useragent) {
        case 1:
            $agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko';
            break;
        case 2:
            $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0';
            break;
        case 3:
            $agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_1_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.1 Mobile/15E148 Safari/604.1';
            break;
        case 4:
            $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
            break;
        case 5:
            $agent = 'Mozilla/5.0 (Linux; U; Android 4.3; de-de; GT-I9300 Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
            break;
        case 6:
            $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
            break;
        default:
            $agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Mobile/15E148 Safari/604.1';
        }

        return $agent;

    }

    /**
     * This funtion send the query to searchengine and get the page
     *
     * @param $verb           string HTTP Request Method (GET and POST supported)
     * @param $ip             string Target IP/Hostname
     * @param $port           int Target TCP port
     * @param $uri            string Target URI
     * @param $_pageNumber    int Actual Search Engine Page
     * @param $getdata        array HTTP GET Data
     * @param $postdata       array HTTP POST Data
     * @param $cookie         array HTTP Cookie Data
     * @param $custom_headers array Custom HTTP headers
     * @param $timeout        int Socket timeout in milliseconds
     * @param $req_hdr        boolean Include HTTP request headers
     * @param $res_hdr        boolean Include HTTP response headers
     * 
     * @return boolean
     * @access private
     */
    private function _httpRequest(
        $verb = 'GET / HTTP/1.1\r\n', 
        $ip = 'Host: www.google.com\r\n',
        $port = 80,
        $uri = '/',
        $_pageNumber = 1,
        $getdata = array(),
        $postdata = array(),
        $cookie = array(),
        $custom_headers = array(),
        $timeout = 1000,
        $req_hdr = false,
        $res_hdr = false
    ) {       
        $ret = '';
        $verb = strtoupper($verb);
        $cookie_str = '';
        $getdata_str = count($getdata) ? '?' : '';
        $postdata_str = '';
    
        foreach ($getdata as $k => $v) {
            $getdata_str .= urlencode($k) .'='. urlencode($v);
        }
    
        foreach ($postdata as $k => $v) {
            $postdata_str .= urlencode($k) .'='. urlencode($v) .'&';
        }
    
        foreach ($cookie as $k => $v) {
            $cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';
        }
    
        $crlf = "\r\n";
        $req = $verb . ' ' . $uri . $getdata_str . ' HTTP/1.1' . $crlf;
        $req .= 'Host: '. $ip . $crlf;


        $_agent = PHPScraper::_getUserAgent();        

        $req .= $_agent;
        $req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf;
        $req .= 'Accept-Language: es-ES,es;q=0.5' . $crlf;
        $req .= 'Accept-Encoding: deflate' . $crlf;
        $req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf;
       
        foreach ($custom_headers as $k => $v) {
            $req .= $k .': '. $v . $crlf;
        }
           
        if (!empty($cookie_str)) {
            $req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf;
        }
           
        if ($verb == 'POST' && !empty($postdata_str)) {
            $postdata_str = substr($postdata_str, 0, -1);
            $req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf;
            $req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf;
            $req .= $postdata_str;
        } else {
            $req .= $crlf;
        }
       
        if ($req_hdr) {
            $ret .= $req;
        }       

        $_querylink = $ip . $uri;
        $_querylink = 'https://' . $ip . $uri;    
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_querylink);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if (($httpcode>=200 && $httpcode<300) 
            && preg_match('/' . $this->_site . '/', $data)
        ) {
            /** 
             * I recomed not to delete the next comment
             * It is useful to check if the delimitations string are in the
             * search engine page
             */

            /*
            if(preg_match('/'.$this->_snippets1.'/', $data)){
                echo "<br>snippets1 está <br>\n";
            }else{
                echo "<br>snippets1 no está <br>\n";
            }

            //echo 'snippets2: ' . '/'.$this->_snippets21.'/' . '<br>\n';
            //if(stristr ($data, $this->_snippets21) !== FALSE){
            if(preg_match('/'.$this->_snippets21.'/', $data)){
                echo "snippets2 está <br>\n";
            }else{
                echo "snippets2 no está <br>\n";
            }

            //echo 'snippets3: ' . '/'.$this->_snippets3.'/' . '<br>\n';
            //if(stristr ($data, $this->_snippets3) !== FALSE){
            if(preg_match('/'.$this->_snippets3.'/', $data)){
                echo "snippets3 está <br>\n";
            }else{
                echo "snippets3 no está <br>\n";
            }

            if(preg_match('/Esto es una mierda/', $data)){
                echo 'Mierda está <br>\n';
            }else{
                echo 'Mierda no está <br>\n';
            }
            */

            /**
             * Online enter the condition if all the three delimitations string
             * are found in the page
             */
            if (preg_match('/' . $this->_snippets1 . '/', $data) 
                && preg_match('/' . $this->_snippets2 . '/', $data) 
                && preg_match('/' . $this->_snippets3 . '/', $data)
            ) { 

                $_snippers1 = explode($this->_snippets1, $data);
                $_snippers2 = explode($this->_snippets2, $_snippers1[1]);        
                $_snippers3 = explode($this->_snippets3, $_snippers2[0]);

                foreach ($_snippers3 as $_key=>$_snipper) {
              
                    if (preg_match('/'.$this->_site.'/', $_snipper)) {

                        echo "<br><br>\n\nKeyWord: \"" . $this->palabraclave 
                            . "\" !!!FOUND!!! for the site " 
                            . $this->_site . " <br>\n";

                        $_pageNumber > 1 ? $_page = ($_pageNumber + 10) /10 : $_page = 1;
                        
                        echo 'Position: ' . $_key . ' on page ' .$_page. "<br><br>\n"; 
                        $this->position = $_key;

                        return true;
                        break;
                    }             

                }
                return false;

            } else {
                /**
                 * Domain is found on the page but we cann not find 
                 * the exact position
                 */
                if (preg_match('/'.$this->_site.'/', $data)) {

                    echo "<br><br>\n\nKeyWord: \"" . $this->palabraclave 
                    . "\" !!!FOUND!!! for the site " 
                    . $this->_site . " \n\n<br><br>";

                    /*echo "<br>It is found on the page but we cann't get the exact position\n\n";*/
                    $this->position = "0"; 
                    return true;

                } else {
                    echo "<br><br>KeyWord: \"" . $this->palabraclave 
                        . "\" !!!NOT FOUND!!!\n\n\n<br><br>";
                    return false;
                }
            }        
            
        } else {            
            //echo "<br>\n\nAlgo ha fallado en la conexión con la web del buscador o la palabra no está en la página\n\n";
            echo "\n\nCodigo HTTP:" . $httpcode . "<br>\n\n";
            $_pageNumber > 1 ? $_page = ($_pageNumber + 10) /10 : $_page = 1;
            echo "The KW \"" . $this->palabraclave . "\" isn't in this page: " 
                . $_page . " in the searchengine for the site " . $this->_site . " <br>\n\n";
            return false;
        }

        return false;
    } 
    
    /**
     * This funtion send format the execution time
     *
     * @param $difference int Time by the execution end
     * 
     * @return string
     * @access public
     */
    public function timeDifference(
        $difference
    ) {
        if ($difference == 0) {
            return $difference;
        }
        $hours = date("G", $difference - 3601);
        $mins = date("i", $difference);
        $secs = date("s", $difference); 
        //$diff="'day': ".$days.",'month': ".$months.",'year': "
        // .$years.",'hour': ".$hours.",'min': ".$mins.",'sec': ".$secs;
        $diff = $hours . ":" . $mins . ":" . $secs;
        
        return $diff;
    } 

}
/**
 * End of Class PHPScraper
 */
?>
