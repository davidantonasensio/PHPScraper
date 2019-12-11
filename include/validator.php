<?php
/**
 * Idanas - Commom Clases Auth
 * PHP Version 7.x
 * PHPScraper Version 0.6.1
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
 * Idanas class for input validation
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
class Validator
{
    /**
     * This basic clean should clean html code from
     * lot of possible malicious code for Cross Site Scripting
     * use it whereever you get external input
     * 
     * @param $string script to validate
     * 
     * @return string
     * @access public
     */
    static function basicClean($string) 
    {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        $string = str_replace(
            array("&amp;", "&lt;", "&gt;"), 
            array("&amp;amp;", "&amp;lt;", "&amp;gt;", ), $string
        );
        /** 
         * Fix &entitiy\n;
         */
        $string = preg_replace('#(&\#*\w+)[\x00-\x20]+;#U', "$1;", $string);
        $string = html_entity_decode($string, ENT_COMPAT, "UTF-8");
       
       
        /** 
         * Remove any attribute starting with "on" or xmlns
         */
        $string = preg_replace('#(<[^>]+[\x00-\x20\"\'])(on|xmlns)[^>]*>#iU', "$1>", $string);

        /** 
         * Remove javascript: and vbscript: protocol
         */
        $string = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '$1=$2nojavascript...', $string);
        $string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '$1=$2novbscript...', $string);

        /** 
         * <span style="width: expression(alert('Ping!'));"></span>
         * only works in ie...
         */
        $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*expression[\x00-\x20]*\([^>]*>#iU', "$1>", $string);
        $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*behaviour[\x00-\x20]*\([^>]*>#iU', "$1>", $string);
        $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*>#iU', "$1>", $string);

        /** 
         * Remove namespaced elements (we do not need them...)
         */
        $string = preg_replace('#</*\w+:\w[^>]*>#i', "", $string);
        /**
         * Remove really unwanted tags
         */
       
        do {
            $oldstring = $string;
            $string = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $string);
        } while ($oldstring != $string);
       
        return $string;
    }
}
?>
