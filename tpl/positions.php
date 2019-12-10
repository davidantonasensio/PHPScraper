<?php
/*
print "\n\nPositions array <pre>";
print var_dump($mainBasicFunctionsIdanas->getPost());
print "</pre>\n\n";
*/


$domainName = $validate->basicClean($mainBasicFunctionsIdanas->getConfig("config_url", "site"));
$language = $validate->basicClean($mainBasicFunctionsIdanas->getConfig("config_url", "language"));
$text = $validate->basicClean($mainBasicFunctionsIdanas->getConfig("config_url", "text"));
$pages = $validate->basicClean($mainBasicFunctionsIdanas->getConfig("config_url", "pages"));

$text = urldecode ($text);
$domainName = urldecode ($domainName);

if (!isValidDomainName($domainName)) {
    echo "Not a acepted Domain Name or it doesn't exist??: " . $domainName . "<br>";
    echo 'exiting';
    exit;   
}

//$snippets1 = 'xxxxxxxxxxx'; // This is the begining of the SERPS     
$snippets1 = '<div class="srg"'; // This is the begining of the SERPS
$snippets2 = '<div id="extrares">'; // This is the end of the SERPS        
$snippets3 = '<div data-hveid="'; // <div data-hveid="   or   <div class="g">

$generator = new \Idanas\PHPScraper\Seo\PHPScraper(
    trim($snippets1), trim($snippets2), trim($snippets3)
);
$generator->buildURL($domainName, $text, $language, $pages, $language);
$KWPositions = $generator->results();

$HTMLForm = resultHTMLBuilder($KWPositions, $domainName, $language);
echo $HTMLForm;


/**
*    This funcition construct the table with the result to send per Email
*
*    @param
*    @return String
*    @access public
*/

function resultHTMLBuilder($KWPositions, $domainName, $language) {
    $HTMLRows = '';
    $allWords = count($KWPositions);
    $counterTopPositions = 0;

    /* Build the rows */
    foreach ( $KWPositions as $KW => $positionArray ){
        foreach ( $positionArray as $query => $position ){
            $HTMLRows .= '<tr style="border: 1px solid black;"><td>';
            if( preg_match('/TOP/', $position) )
            {
                if( preg_match('/NOT/', $position) )
                {
                    //echo $KW . " - Palabra no encontrada: " . $position . "\n";
                    $HTMLRows .= $KW . "</td>";
                    $HTMLRows .= '<td><strong> ' . $position . "</strong>\n";
                } else {
                    //echo $KW . " - Palabra encontrada: " . $position . "\n";
                    //echo "Link: https://" . $query . "\n";
                    $counterTopPositions++;
                    $HTMLRows .= '<a href="https://'.$query.'"><strong>' . str_replace("+", " ", $KW) . "</strong></a></td>";
                    $HTMLRows .= '<td><strong> ' . $position . "</strong>\n";
                }                

            } elseif( $position >= 1 ) {
                //echo $KW . " - Palabra encontrada: " . $position . "\n";  
                //echo "Link: https://" . $query . "\n"; 
                $HTMLRows .= '<a href="https://'.$query.'"><strong>' . str_replace("+", " ", $KW) . "</strong></a></td>";  
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
    $HTML = '<table width="400" border="0" cellpadding="5" cellspacing="1" style="border-collapse: collapse;">';
        $HTML .= '<tr><td>';
            $HTML .= "<strong>" . $domainName . "</strong><br>";
            $HTML .= "Search language: <strong>" . $language . "</strong><br>";
            $HTML .=  date("Y-m-d H:i:s") . "<br>";
            $HTML .= 'All the Words : <strong>' . $allWords . '</strong><br>';
            $HTML .= "Top Positions: <strong>" . $counterTopPositions . "</strong><br>";
            $prozent = floor((($counterTopPositions * 100)/$allWords));
            $HTML .= "<H2>Top positions: " . $prozent . "%</H2>";
        $HTML .= '</td></tr>';
        $HTML .= $HTMLRows;
    $HTML .= "</table>";
    $HTML .= "<br />";

    return $HTML;        
}

/**
 * Adding HTTP if already not in the domain.
 * Validates only HTTP and https in a scheme.
 * Validates URL.
 * Checks A record of the domain name.
 * Validates server header response.
 * Validates the host.
 * Removes www. if you don't want it.
 */
function isValidDomainName($domainName)
{
    //$domainName = "fotograf.david-anton.com";

    $validation = FALSE;
    $domainNameparts = parse_url(filter_var($domainName, FILTER_SANITIZE_URL));
    if(!isset($domainNameparts['host'])){
        $domainNameparts['host'] = $domainNameparts['path'];
    }

    if($domainNameparts['host']!=''){
        if (!isset($domainNameparts['scheme'])){
            $domainNameparts['scheme'] = 'http';
        }
        if(checkdnsrr($domainNameparts['host'], 'A') && in_array($domainNameparts['scheme'],array('http','https')) && ip2long($domainNameparts['host']) === FALSE){ 
            $domainNameparts['host'] = preg_replace('/^www\./', '', $domainNameparts['host']);
            $domainName = $domainNameparts['scheme'].'://'.$domainNameparts['host']. "/";            
            
            if (filter_var($domainName, FILTER_VALIDATE_URL) !== false && @get_headers($domainName)) {
                $validation = TRUE;
            }
        }
    }

    return $validation;    
}

?>
