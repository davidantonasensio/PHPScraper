<?php
/**
 * Idanas - PHP Search Engine Scraper, Linux bash Version
 * PHP Version 7.0.
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
require '../conf/idanas.php';
$idanasConf = $IDANAS_CONFIGURATION;
require $IDANAS_CONFIGURATION["LIBPATH"] . 'main.php';
$mainBasicFunctionsIdanas = new Idanas\Common\main($idanasConf);

require_once $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'db.php';
require_once $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'dbaccess.php';

require $mainBasicFunctionsIdanas->conf['LIBPATH'] . 'validator.php';
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'auth.php';
require $mainBasicFunctionsIdanas->conf['LIBPATH'] . 'search.php';


$module = $mainBasicFunctionsIdanas->getConfig("config_url", "module");


if ($module != "") {
    print('<a href="index.php">Back to start</a><br>');
    //print('<a href="index.php?module=posspy">PosSpay</a><br>');
    //print('<a href="index.php?module=sitespy">SiteSpay</a><br><br>');

}

if (isset($_SERVER['PHP_AUTH_USER']) || isset($_SERVER['PHP_AUTH_PW'])) {
    $validate = new Idanas\Common\Validator();
    $user = $validate->basicClean($_SERVER['PHP_AUTH_USER']);
    $password = $validate->basicClean($_SERVER['PHP_AUTH_PW']);
}

$auth = new Idanas\Common\Auth($module, $mainBasicFunctionsIdanas, $user, $password);
/**
 * $DBPass = $auth->pass; // Hashed Password from the DB
 * $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing Password
 * if(password_verify($password, $DBPass)) { // Verify Password
 *    echo 'Password correct <br>';
 * } else {
 *    echo 'Password incorrect <br>';
 * }
 */






switch ($module){
case "posspy":
    if ($mainBasicFunctionsIdanas->getConfig("config_url", "submited") != 1) {
        include($mainBasicFunctionsIdanas->conf["TEMPLATES"] . "form.php");
    } else {        
        print '<a href="index.php?module=posspy">Back to Posspy</a><br><br>';        
        include($mainBasicFunctionsIdanas->conf["TEMPLATES"] . "positions.php");
    }
    break;        
    
case "sitespy":
    if ($mainBasicFunctionsIdanas->getConfig("config_url", "submited") != 1) {
        include($mainBasicFunctionsIdanas->conf["TEMPLATES"] . "formsite.php");
    } else {
        print '<a href="index.php?module=sitespy">Back to sitespy</a><br><br>';
        include($mainBasicFunctionsIdanas->conf["TEMPLATES"] . "sites.php");
    }
    break;        
    
case "idanasdb":
    break;    

default:
    print('<a href="index.php?module=posspy">PosSpy</a><br>');
    //print('<a href="index.php?module=sitespy">SiteSpy</a><br>');
    //print('<a href="index.php?module=idanasdb">idanas DB</a><br>');
}






?>

