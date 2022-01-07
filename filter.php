<?php
$content = file('/tmp/output.txt');

global $ownIp;
$ownIp = file_get_contents('https://ipinfo.io/ip');

function checkIP($domain) {
    global $ownIp;
    return gethostbyname($domain) == $ownIp;
}
function checkAvailability($domain) {
    $url = 'http://' . $domain . '/index.php';
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
    // Will catch http -> https  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
    // Is ignored, because a wrong SSL certificate don't stop a attack
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  
    return $code == 200;
}

// Define the list of safe wordpress versions. Currently: CVE-2022-21664

$wpVersionsIgnore = [
 '5.8.3',
 '5.7.5',
 '5.6.7',
 '5.5.8',
 '5.4.9',
 '5.3.11',
 '5.2.14',
 '5.1.12',
 '5.0.15',
];

$reports = array();
foreach($content as $line) {
        preg_match("/'(\d+\.\d+(\.\d+)?)';/", $line, $matches);

        if(in_array($matches[1], $wpVersionsIgnore) === false) {
                preg_match('/vhosts\/([^\/]+?)\/httpdocs/', $line, $domainMatch);
                if(empty($domainMatch)) {
                        preg_match('/vhosts\/(?:[^\/]+?)\/([^\/]+?)\/wp-includes/', $line, $domainMatch);
                }
                if(checkIP($domainMatch[1]) && checkAvailability($domainMatch[1])) {
                        $reports[] = $line;
                }
        }
}

var_dump($reports);
