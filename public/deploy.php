<?php

$payload = json_decode($_POST['payload'], true);

if ($payload['action']=='closed') {
    echo exec('whoami') . PHP_EOL;
    echo exec('cd /var/www/lara && git stash && git pull origin master') . PHP_EOL;
    $f = fopen("autoupdate.log", 'a+');
    fwrite($f, "---------------" . PHP_EOL);
    fwrite($f, date('c') . PHP_EOL);
    fwrite($f, "Sender: ". $payload['sender']['login']. PHP_EOL);
    fwrite($f, "Pull request URL: ". $payload['pull_request']['url']. PHP_EOL);
    fwrite($f, "Pull request Title: ". $payload['pull_request']['title']. PHP_EOL);
    fwrite($f, "Pull request user: ". $payload['pull_request']['user']['login']. PHP_EOL. PHP_EOL);
}

fclose($f);

