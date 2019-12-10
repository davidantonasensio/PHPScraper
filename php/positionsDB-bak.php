#!/usr/bin/php
<?php
/**
 * Idanas - PHP Search Engine Scraper, Linux bash Version
 * PHP Version 3.0.
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

//error_reporting( E_ALL );
//error_reporting(0);

require '../conf/idanas.php';
$idanasConf = $IDANAS_CONFIGURATION;

require $IDANAS_CONFIGURATION["LIBPATH"] . 'main.php';
$mainBasicFunctionsIdanas = new Idanas\Common\main($idanasConf);

require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'search.php';
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'db.php';
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'dbaccess.php';
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'phpmailer/class.phpmailer.php';
//require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'phpmailer-20191201/src/PHPMailer.php';

//require '../lib/search.php';
//require '../lib/phpmailer/class.phpmailer.php';
//require '../lib/dbaccess.php';
//require '../lib/db.php';



/** 
 * Esto es un comentario
 * 
 * @param $mainBasicFunctionsIdanas Content all the common methods and Statics
 */
$mainBasicFunctionsIdanas = new Idanas\Common\main($idanasConf);




getData($mainBasicFunctionsIdanas);

/**
 * Get all the information from the database and check the given keywords by Google 
 * calling the class search 
 * 
 * @param $mainBasicFunctionsIdanas Content all the common methods and Statics
 * 
 * @return Void
 */
function getData($mainBasicFunctionsIdanas) 
{
    $dbr = new Idanas\Common\DBaccess('ro', '', $mainBasicFunctionsIdanas);

    //echo "ERRORAAAAAAAA: ". $this->errorMysqlConnection . "\n";
    //echo $dbr;
    //var_dump($dbr);
    //exit;


    $todayDay = date('d');
            
                
    /** 
     * SQL Queries, few options 
     */

    /**
     * Active Domains in DB 
     */
    /*
    $sqlDomainInfoKW= ' SELECT  id, contrat_nr, domain, language, playdaymonth, 
    pages, keywords, contracter, emails 
    FROM keywords 
    WHERE ( playdaymonth=0 OR playdaymonth='. $todayDay .' ) 
        AND playdaymonth <> "-1" 
        ORDER BY domain DESC ';
    */
    
    
    /** 
     * Manual Queries
     */
    
    $sqlDomainInfoKW = ' SELECT  id, contrat_nr, domain, language, playdaymonth, 
    pages, keywords, contracter, emails 
    FROM keywords
    WHERE id = "212" ';
    // WHERE id = "212" OR id= "213" OR  id= "201"';
    // WHERE id='212' OR id='201' ';


    /**
     * Queries Photography pages David Anton 
     */
    /*
    $sqlDomainInfoKW= ' SELECT  id, contrat_nr, domain, language, playdaymonth, 
    pages, keywords, contracter, emails FROM keywords
    WHERE id = "202" OR id = "201" OR id = "210" OR id = "211"  ';
    */
     /** 
      * END of SQL Queries, few options 
      */                
                
    
    //echo 'sql: ' . $sqlDomainInfoKW. "<br>\n"; 


    
    
    /**
     * Portions of code to delimite the Google SERPs
     * 
     * @param $snippets1 is the begining of the SERPs Section
     * @param $snippets2 is the end of SERPs Sectiopn
     * @param $snippets3 is the begining of the individual SERP
     */
    //$snippets1 = 'xxxxxxxxxxx';
    $snippets1 = '<div class="srg"';
    $snippets2 = '<div id="extrares">';
    $snippets3 = '<div data-hveid="';

    $search = new \Idanas\PHPScraper\Seo\PHPScraper(
        trim($snippets1), trim($snippets2), trim($snippets3)
    );

    $startTime = time(); // Count the execution time of all the process
    if ($dbr->query($sqlDomainInfoKW)) {        
        $queriescounter = 0;
        //$KWCounter = 0;
        while ($dbr->nextRecord()) {

            /** 
             * Count the execution time of actual record, domain
             * 
             * @param $recordStarttime
             */
            $recordStarttime= time(); 
            
            /** 
             * Getting all the needed database fields 
             */
            $numberContract= $dbr->record('contrat_nr');
            $domainName = $dbr->record('domain');
            $langSearch = $dbr->record('language');
            $keyWords = $dbr->record('keywords');
            $pagesSearchEngine = $dbr->record('pages');
            $contracter = $dbr->record('contracter');
            $toEmails = $dbr->record('emails');
            $playDayMonth = $dbr->record('playdaymonth');
            
            //echo 'keyWordsCounter: ' . $KWCounter . "<br>\n";

            // First pass look in the specific country pages
            $search->buildURL(
                $domainName, $keyWords, $langSearch, $pagesSearchEngine, $langSearch,
                $queriescounter, $startTime, $recordStarttime
            );

            $KWPositions = $search->results();
            //echo "\n resultado: " .$KWPositions. "\n";
            
            /*
            print "\n\nResult Array <pre>";
            print var_dump($KWPositions);
            print "</pre>\n\n";
            */

            $result = resultHTMLBuilder($KWPositions, $domainName, $langSearch);
            //exit;

            $ober20 = ober20($search->prozent);
            $prozent1 = $search->prozent;
            //$result .= '<br><br><br>';
            $KWCounter = $search->keywordscounter;
            $queriescounter = $search->queriescounter;
            
            
    
            /**
             * Calculate the execution time
             */
            $endtime = time();
            $result .= '<br>Execution time: ' 
                . $search->timeDifference($endtime - $recordStarttime) . '<br>';
            
            
            /** 
             * Look if there are more than 20% top positions, if yes send the email
             * to everybody in the list, otherweis sendet just to the specified 
             * email address
             */
            //echo "\n\nprozent: " . $prozent2 . "<br>\n\n";
            $contrat = $numberContract. "_" .$domainName;
            if ($playDayMonth != 0) {
                send($contrat, $toEmails, $result, $prozent1, $contracter);
            } elseif ($ober20 == 1) {
                send($contrat, $toEmails, $result, $prozent1, $contracter);
            } else {
                // if not ober 20% just send the email to the given address
                send($contrat, 'd.anton@idanas.de', $result, $prozent1, $contracter);
            }
            echo ' Contrat number: ' . $contrat . "<br>\n\n\n";            
            
            
            // sleep(120);
        }

            
    } else {
        $result = 0;
    }
    $endtime = time();
    $difference = $endtime - $startTime;
    echo "Total Execution time: " 
        . $search->timeDifference($difference) . "\n";

}
// }// End of function GetData


/**
 * This funcition construct the table with the result to send per Email
 *
 * @param array  $KWPositions All the information about positions in Search Engines
 * @param String $domainName  Name of Domain to search for
 * @param String $langSearch  Languange of the serach
 * 
 * @return String
 */
function resultHTMLBuilder($KWPositions, $domainName, $langSearch) 
{

    $HTMLRows = '';
    $allWords = count($KWPositions);
    $counterTopPositions = 0;

    /* Build the rows */
    foreach ($KWPositions as $KW => $positionArray) {
        foreach ($positionArray as $URLQuery => $position) {
            $HTMLRows .= '<tr><td>';
            if (preg_match('/TOP/', $position)) {
                if (preg_match('/NOT/', $position)) {
                    //echo $KW . " - Palabra no encontrada: " . $position . "\n";
                    $HTMLRows .= $KW . "</td>";
                    $HTMLRows .= '<td> ' . $position . "\n";
                } else {
                    //echo $KW . " - Palabra encontrada: " . $position . "\n";
                    //echo "Link: https://" . $URLQuery . "\n";
                    $counterTopPositions++;
                    $HTMLRows .= '<a href="https://'.$URLQuery.'"><strong>' 
                        . str_replace("+", " ", $KW) . "</strong></a></td>";
                    $HTMLRows .= '<td><strong> ' . $position . "</strong>\n";
                }                

            } elseif ($position >= 1) {
                //echo $KW . " - Palabra encontrada: " . $position . "\n";  
                //echo "Link: https://" . $URLQuery . "\n"; 
                $HTMLRows .= '<a href="https://'. $URLQuery .'"><strong>' 
                    . str_replace("+", " ", $KW) . "</strong></a></td>";  
                $HTMLRows .= '<td><strong> ' . $position . "</strong>\n";     
                $counterTopPositions++;      
            } else {
                //echo $KW . " - Palabra no encontrada " . $position . "\n";
                $HTMLRows .= $KW . "</td>";
                $HTMLRows .= '<td><strong> ' . $position . "</strong>\n";
            }
            $HTMLRows .= '</td></tr>';
        }
        
    }

    /* Build the body */
    $HTML = '<table width="450" border="0" cellpadding="5" cellspacing="1">';
        $HTML .= '<tr><td>';
            $HTML .= "<strong>" . $domainName . "</strong><br>";
            $HTML .= "Search language: <strong>" . $langSearch . "</strong><br>";
            $HTML .=  date("Y-m-d H:i:s") . "<br>";
            $HTML .= 'All the Words : <strong>' . $allWords . '</strong><br>';
            $HTML .= "Top Positions: <strong>".$counterTopPositions."</strong><br>";
            $prozent = floor((($counterTopPositions * 100)/$allWords));
            $HTML .= "<H2>Top positions: " . $prozent . "%</H2>";
        $HTML .= '</td></tr>';
        $HTML .= $HTMLRows;
    $HTML .= "</table>";
    $HTML .= "<br />";

    return $HTML;        
}


/**
 * This funcition just return 1 or 0 depending of the prezent value
 *
 * @param int $prozent Porcentual amount of KW placed in first or second page
 * 
 * @return 1 if procent ober 20% or 0 if it is under
 */
function ober20($prozent) 
{
    if ($prozent >= 20) {
        echo "ober the 20% - ";
        return 1;
    } else {
        echo "unter the 20% - ";
        return 0;
    }
}




/**
 * This function send the result email to the given addresses
 *
 * @param $contrat    Contrat number
 * @param $toEmails   Emails Adresses to send the results to
 * @param $result     HTML with the result of the search
 * @param $prozent1   Procent of positions in the searched pages
 * @param $contracter For which client is made the serche * 
 * 
 * @return void
 * @access protected
 */
function send($contrat, $toEmails, $result, $prozent1, $contracter)
{
    // instantiate the class
    $mailer = new PHPMailer();
    //$mailer = new \PHPMailer\PHPMailer\PHPMailer();

    $mailer->IsSMTP();
    $mailer->Timeout = 60;
    $mailer->Host = 'idanas.com';
    $mailer->Hostname = 'idanas.com';
    $mailer->SMTPAuth = true;     // SMTP mit Authentifizierung benutzen
    $mailer->Username = 'register@idanas.com';  // SMTP-Benutzername
    $mailer->Password = 'r$5U9os2Ty&6f'; // SMTP-Passwort
    $mailer->From = 'register@idanas.com';
    $mailer->Sender = 'register@idanas.com';
    $mailer->FromName = 'David';
    $mailer->ContentType = 'text/plain';
    $mailer->CharSet = 'utf-8';

    // Set the subject        
    $mailer->Subject = $contrat . '=> LOCAL=' . $prozent1 
        . '% - TOP POSICIONES - Oberkirch - Fotografia Nuevo desde 20191130';

    // Body
    $mailer->Body = $result;

    /**
     * If we wish to hardcore add a extra address to send to just add here
     * $mailer->AddAddress ( 'foo@host.com', 'Eric Rosebrock' );
     * fe. $mailer->AddAddress ( 'test@test.de', 'David Anton' );
     */

    $toEmails = trim($toEmails);
    $toEmails = str_replace("\n", "<<->>", $toEmails);
    $toEmails = explode("<<->>", trim($toEmails));

    foreach ($toEmails as $value) {
        $mailer->AddAddress($value);
    }

    $mailer->isHTML(true);

    $send = $mailer->Send();

    if ($send) {
        echo 'Mail sent! - ';
        $sent = 1;
    } else {
        echo "There was a problem sending this mail!\n";
        echo 'error: ' . $mailer->ErrorInfo . "\n";
        $sent = 0;
    }

    $mailer->ClearAddresses();
    $mailer->ClearAttachments();    
    $mailer->SmtpClose();
}   
?>
