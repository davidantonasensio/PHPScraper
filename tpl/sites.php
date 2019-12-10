<?php
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . 'searchsites.php';
//echo "LIBPATH: " .$mainBasicFunctionsIdanas->conf["LIBPATH"]."searchsites.class". "<br>";
//include($mainBasicFunctionsIdanas->conf["LIBPATH"] . "searchsites.class");
$generator = new Idanas\PHPScraper\Seo\searchsites($_REQUEST["language"], trim ($_REQUEST["text"]));
echo $generator->results();

//require_once $mainBasicFunctionsIdanas->conf["LIBPATH"] . "dbaccess.php";
require $mainBasicFunctionsIdanas->conf["LIBPATH"] . "stats.php";
$stats = new stats($_REQUEST["language"], "", trim ($_REQUEST["text"]), 0, 0, $mainBasicFunctionsIdanas);

?>
