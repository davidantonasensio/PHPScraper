<?php


$lang = $_REQUEST["language"];
//echo "lang: " . $lang . "<br>";
switch($lang) {
    case "de":
        $language = '&lr=lang_de'; //paginas en Aleman
        $land = '&hl=de'; // from wich country are we looking for
        $search = '&btnG=Suche';
        break;
    case "es":
        $language = '&lr=lang_es'; //paginas en Espanol
        $land = '&hl=es';
        $search = '&btnG=B%C3%BAsqueda';
        break;
    case "all":
        $language = '&lr='; // Search the coplete web
        $land = '&hl=en';
        $search = '&btnG=Search';
        break;
    default:
        $language = '&lr='; // Search the coplete web
        $land = '&hl=en';
        $search = '&btnG=Search';

}

echo "site: " . $_REQUEST["site"] . "<br>";


$site = trim($_REQUEST["site"]);
$text = trim ($_REQUEST["text"]);
$pages = trim($_REQUEST["pages"]) - 1;



$_words = explode("\n",$_REQUEST["text"]);
$googlepage = "";
$countertop = 0;
$allwords = count($_words);
$positions = '<table border=1 cellpadding="5" cellspacing="1">';
foreach($_words as $key => $value){
    //echo "words: " . $value . "<br>";
    $value = trim($value);
    if($value == ""){
       $allwords--;
       continue;
    }
        
    //echo "value: " . str_replace("\n", "", $value) . "<br>";
    //echo "value: " . $value . "<br>";    
    $searchtext = str_replace("%0D", "", urlencode($value));
    $searchquery = "&q=" . $searchtext; 
    
    $found = 0;
    for ($i=0;$i<=$pages;$i++) {
        
        $pagenumber = $i * 10;
        //echo "pagenumber: " . $pagenumber . "<br>";
        //$query = 'http://www.google.com/search?' . $searchquery . '&start=' . $pagenumber . $language;
        if ($pagenumber == 0) {
            $query = 'http://www.google.com/search?' . $land . $searchquery . $search . $language;
        } else {
            $query = 'http://www.google.com/search?' . $searchquery. $land . $language . '&start=' . $pagenumber . '&sa=N';
        }
        //echo "query: " . $query . "<br>";
        
        
        $handle = "";
        $line = "";
        if($handle = fopen($query, "r")){
            $googlepage = "";
            while (!feof($handle))
            {
               $line = fgets($handle, 1024);
               
               $googlepage .= $line;
               
          
               if(eregi($site, $line)){
                   //if(eregi("<span dir=ltr>", $line)){
                   //if(eregi("dir=ltr", $line)){
                       if(eregi("cach", $line)){
                       $positions .= '<tr><td>';
                       $positions .= '<a href="'.$query.'"><strong>' . str_replace("+", " ", $value) . "</strong></a></td><td>";
                       $positions .= '<strong>TOP ' . (($i+1)*10) . "</strong></td></tr>";
                       //echo "<strong>" . str_replace("+", " ", $value) . " - TOP: " . (($i+1)*10) . "</strong><br>";
                       $i=100; // to avoid continue searching
                       $found = 1;
                       $countertop++;
                       break;
                   }
               } 
               
               
            }
        }
        fclose($handle);
    }
    if ($found == 0) {
        $positions .= '<tr><td>';
        $positions .= str_replace("+", " ", $value) . "</td><td>";
        $positions .= "NOT TOP: " . (($i+1)*10);
        $positions .= "</td></tr>";
        //echo str_replace("+", " ", $value) . " - NOT TOP: " . (($i+1)*10) . "<br>";
    }
    
}
$positions .= "</table>";

echo 'All the Words : ' . $allwords . '<br>';
echo "Top Positions: " . $countertop . "<br>";
echo "<H2>Top positionen: " . abs((($countertop * 100)/$allwords)) . "%</H2><br>";
echo $positions;
//if(ereg("sawa-shop.de", $googlepage)) echo "AAAAAAAAAAAAAAAAA";
//echo $googlepage;
                                       
?>
