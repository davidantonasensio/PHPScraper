## About
PHPScriper is a class to check search engine positions for a KeyWord (KW) or list of them for a given domain name. Actually just for Google

## Class Features
- Validates Domain Name
- 5 Languages search English, German, Spanish, French, Italian
- Several User-Agents randomly chosen to avoid being blocked
- After each search, PHPScreper weit randomly between 15 and 30 Seconds to avoid being blocked. There is a way to go over this problem using the debugger tool      from Facebook like s0md3v describes in is goop https://github.com/s0md3v/goop
  anyway, waiting longer that 15 seconds for each request looks to be actually safe 20191201
- includes actual delimiters for Google search result page 20191201
     * @param $snippets1 Beginning of group of SERPs '<div class="srg"'
     * @param $snippets2 End of the group of SERPs  '<div id="extrares">'
     * @param $snippets3 Beginning of each Snippet   '<div data-hveid="' 
- It searches for several KW at once, just gib the list of Keywords with a KW per line

## Repository
In the folder includes there are several files that I include as examples to build your own application or help to integrate PHPScriper in yours. They are my common classes
- main.php      This class in reading the configuration file in ../conf/idanas.php, read the POST and GET variables with simple validation and do some more things
- auth.php      to allow just users of the system to get to the tool. You need to hash your passwords with 
                    password_hash($passwordToHash, PASSWORD_DEFAULT)
                and verify them with:
                    password_verify($loginPassword, $hashedPassword)
- db.php        Class with some MySQL methods
- dbacces.php   it extends dp.php and includes DB acces information
- validator.php Used to validate input. I use this class since several years and sincerely, I don't remember any more if it is completely made by me or not
and of course, the PHPScriper
- phpscriper.php

You can find two more great tools in this repository installed with composer https://github.com/composer/composer
- PHPMailer       https://github.com/composer/composer
- PHP_CodeSniffer https://github.com/squizlabs/PHP_CodeSniffer

## Why you might need it
If you are traying to positioning your Homepage in the search engines for some Keywords, you will have to check regularly how is the evolution of the positions

## Examples
There are to examples in the repository, one for the command line and another one for the web
- positionsDB   It is gut to cron a search for your regular to search KWs   
    It get domain Name, number of search engines pages to look in and KWs from a DB, email address to send the results to and some other information
    It Composes a HTML table with the results... yes I know, why still a HTML table? I will change it in the future
    It Sends an email to the given email address or addresses and send the result table. I use to send the email the great PHPMailer a fantastic tool used for several CMS like for example WordPress. https://github.com/PHPMailer/PHPMailer
    You can find a dump of the DB structure in /idanasSEO.sql
    cron example to run PHPScraper every day at 1:15 am and write a new log with the output:
        15 1 * * * /usr/bin/php /YOUR-PATH/php/positionsDB.php > YOUR-PATH/logs/positionsDB.log


- index.php, loading the template positions.php in folder ../tpl
    It shows a very elementary form where you can give for parameters to send to PHPScriper
        Domain Name
        Number of pages to look in
        Language
        KWs to look for

## Localization
The PHPScraper is just made in English, well, better said, bad english. Is it possible that you find some spanish someware
The seaches cann be made in English, German, Spanish, French and Italian

## Security
PHPScraper itself doesn't make any validation, except to check if the Domamin Name is a real and valid Domain Name
Be careful programing your tools to access PHPScraper and validate any input made by possible users
Thought that this tool should be used for personal use and not to be of public domain, i thing security here shouldn't be a great problem and should be easy to control

## History
I did the first version of this tool back in 2005 but never thought it could be interesting for someone else

## Warranties
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
