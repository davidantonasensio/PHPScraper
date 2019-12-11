<?php
if($_SERVER['HTTP_HOST'] == 'phpscraper.idanas.de') {
    print('<br><strong>Don\'t write more than 5 KWs here, the system will cut away everything over that</strong><br><br>');
}
?>
<table width="400" border="0" cellpadding="0" cellspacing="0" class="pad10">
    <tr><td class="normalblack10">
        <form action="index.php?module=posspy&submited=1" method="post" enctype="application/x-www-form-urlencoded" name="form1">
            <table border="0" cellspacing="5" cellpadding="0">
                    <tr>
                            <td class="boldorange12">Domain name to search for without https:// (fe. idanas.com)<br><input name="site" type="text" maxlength="50" class="inputmedium"></td>
                    </tr>
                    <tr>
                            <td class="boldorange12">Nummer of pages to check by google<br><input name="pages" type="text" maxlength="50" class="inputmedium" value="2"></td>
                    </tr>
                    <tr>
                            <td class="boldorange12">page to search without www. (fe. idanas.com)<br>
                                <table border="0" cellspacing="5" cellpadding="0" width="200">
                                    <tr>
                                        <td class="boldorange12">International Pages</td><td><input type="radio" name="language" value="all" checked></td>
                                        </tr><tr>
                                        <td class="boldorange12">German Pages</td><td><input type="radio" name="language" value="de"></td>
                                        </tr><tr>
                                        <td class="boldorange12">Spanish Pages</td><td><input type="radio" name="language" value="es"></td>
                                        </tr><tr>
                                        <td class="boldorange12">French Pages</td><td><input type="radio" name="language" value="fr"></td>
                                        </tr><tr>
                                        <td class="boldorange12">Italian Pages</td><td><input type="radio" name="language" value="it"></td>
                                    </td></tr>
                                </table>
                            </td>
                    </tr>
                    <tr>
                            <td class="boldorange12">Words to search for<br><textarea name="text" cols="40" rows="30" class="inputxlarge"></textarea></td>
                    </tr>
                    <tr><td>
                        <input type="submit" name="submit" value="SEND">
                    </td></tr>
            </table>
        </form>
    </td></tr>
</table>
