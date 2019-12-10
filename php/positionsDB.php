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

/** Idanas common classes */
require $IDANAS_CONFIGURATION["LIBPATH"] . 'main.php';
$mainBasicFunctionsIdanas = new Idanas\Common\main($idanasConf);
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'db.php';
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'dbaccess.php';

/** Idanas PHPScraper */
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'search.php'; 

/** Import PHPMailer classes into the global namespace */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/** 
 * Loading Composer's autoloader for PHPMailer 
 */
require $mainBasicFunctionsIdanas->conf["COMPOSER_VENDOR"] . 'autoload.php';


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
    
    /**
     * Portions of code to delimite the Google SERPs
     * 
     * @param $snippets1 is the begining of the SERPs Section
     *        20191109 '<div class="srg"'
     * @param $snippets2 is the end of SERPs Sectiopn
     *        20191109 '<div id="extrares">'
     * @param $snippets3 is the begining of the individual SERP
     *        20191109 '<div data-hveid="'
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


            // First pass look in the specific country pages
            $search->buildURL(
                $domainName, $keyWords, $langSearch, $pagesSearchEngine, $langSearch,
                $queriescounter, $startTime, $recordStarttime
            );

            $KWPositions = $search->results();            

            $result = resultHTMLBuilder($KWPositions, $domainName, $langSearch);

            $ober20 = ober20($search->prozent);
            $prozent1 = $search->prozent;
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
// End of function GetData


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
 * it Uses PHPMailer 6.1 - 20191209
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
    //$mailer = new PHPMailer(true);
    $mailer = new PHPMailer(true);
    //$mailer = new \PHPMailer\PHPMailer\PHPMailer();

    try {

        /** 
         * You can allow insecure connections via the SMTPOptions property 
         * introduced in PHPMailer 5.2.10 (it's possible to do this by subclassing 
         * the SMTP class in earlier versions), though this is not recommended as 
         * it defeats much of the point of using a secure transport at all:
         * https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting#php-56-certificate-verification-failure
        */
        $mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //Server settings
        //$mailer->SMTPDebug = SMTP::DEBUG_SERVER;                    // Enable verbose debug output
        $mailer->isSMTP();                                            // Send using SMTP
        $mailer->Host       = 'smtp.idanas.com';                     // Set the SMTP server to send through
        $mailer->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mailer->Username   = '';                  // SMTP username
        $mailer->Password   = '';                        // SMTP password
        $mailer->From       = '';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mailer->Port       = 25;                                     // TCP port to connect to
    
        //Recipients
        $mailer->setFrom('register@idanas.com', 'David Anton');
        $mailer->addAddress('blog.idanas.es@gmail.com', 'David Anton');// Add a recipient, Name is optional
        $mailer->addReplyTo('register@idanas.com', 'David Anton');

        //$mailer->addCC('cc@example.com');
        //$mailer->addBCC('bcc@example.com');
    
        // Attachments
        //$mailer->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mailer->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        $mailer->ContentType = 'text/plain';
        $mailer->CharSet = 'utf-8';
    
        // Content
        $mailer->isHTML(true);                                  // Set email format to HTML
        $mailer->Subject = $contrat . '=> LOCAL=' . $prozent1 
            . '% - TOP POSICIONES - Oberkirch - Fotografia Nuevo desde 20191130';;
        $mailer->Body    = $result;
        //$mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mailer->send();
        echo 'Message has been sent ';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}";
    }

    $mailer->ClearAddresses();
    $mailer->ClearAttachments();    
    $mailer->SmtpClose();
}   
?>
