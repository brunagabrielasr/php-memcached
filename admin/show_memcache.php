<?php

$server = "localhost";
$port = 11211;

/**
 * Taken directly from memcache PECL source
 *
 * http://pecl.php.net/package/memcache
 *
 */
function sendMemcacheCommand($server, $port, $command) {
    $s = @fsockopen($server, $port);
    if (!$s) {
        die("Cant connect to:" . $server . ':' . $port);
    }
    fwrite($s, $command . "\r\n");
    $buf = '';
    while ((!feof($s))) {
        $buf .= fgets($s, 256);
        if (strpos($buf, "END\r\n") !== false) { // stat says end
            break;
        }
        if (strpos($buf, "DELETED\r\n") !== false || strpos($buf, "NOT_FOUND\r\n") !== false) { // delete says these
            break;
        }
        if (strpos($buf, "OK\r\n") !== false) { // flush_all says ok
            break;
        }
    }
    fclose($s);
    return ($buf);
}

$string = sendMemcacheCommand($server, $port, "stats items");
$lines = explode("\r\n", $string);
$slabs = array();
foreach ($lines as $line) {
    if (preg_match("/STAT items:([\d]+):number ([\d]+)/", $line, $matches)) {
        if (isset($matches[1])) {
            if (!in_array($matches[1], $slabs)) {
                $slabs[] = $matches[1];
                $string = sendMemcacheCommand($server, $port, "stats cachedump " . $matches[1] . " " . $matches[2]);
                echo "Slab # " . $matches[1] . "<br />";
                preg_match_all("/ITEM (.*?) /", $string, $matches);
                var_dump($matches[1]);
                echo "<hr />";
            }
        }
    }
}