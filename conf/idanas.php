<?php
/**
 * Idanas - PHP Search Engine Scraper, Configuration File
 * PHP Version 7.3.
 *
 * @category Idanas.common
 * @package  Idanas.common.configuration
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

/**
 * Database configuration 
 */ 
$IDANAS_CONFIGURATION["DB_HOST"] = "";
$IDANAS_CONFIGURATION["DB_USER"] = ""; 
$IDANAS_CONFIGURATION["DB_PASSWORD"] = ""; 
$IDANAS_CONFIGURATION["DB_DBNAME"] = "";

/**
 * DB Tables 
 */ 
$IDANAS_CONFIGURATION["statistics"][""] = "";
$IDANAS_CONFIGURATION["users"]["table"] = "";
$IDANAS_CONFIGURATION["keywords"]["table"] = "";

/**
 * Paths configuration
 */ 
$IDANAS_CONFIGURATION["DOCUMENT_ROOT"] = $_SERVER["DOCUMENT_ROOT"] . "/";
$IDANAS_CONFIGURATION["IMGPATH"] = "images/";
$IDANAS_CONFIGURATION["CSSPATH"] = "css/";
$IDANAS_CONFIGURATION["TEMPLATES"] = "../tpl/";
$IDANAS_CONFIGURATION["LIBPATH"] = "../include/";
$IDANAS_CONFIGURATION["COMPOSER_VENDOR"] = "../vendor/";

/**
 * Get the name of file without the extension or parameters 
 * so kann we allway know where we are
 */ 
$tmpvarm = explode("/", $_SERVER['PHP_SELF']);
$module = explode("?", $tmpvarm[1]);
$module = explode(".", $module[0]);
$IDANAS_CONFIGURATION["MODULE"] = $module[0];
?>
