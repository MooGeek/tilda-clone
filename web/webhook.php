<?php

namespace Moogeek\TildaClone;

require_once '../vendor/autoload.php';

use Pheanstalk\Pheanstalk;

$dotenv = \Dotenv\Dotenv::create(__DIR__.'/..');
$dotenv->load();

$pheanstalk = Pheanstalk::create('queue-server');

if ($_GET['publickey'] === getenv('PUBLIC_KEY')) {
    $data = new \stdClass();
    $data->projectId = (int) $_GET['projectid'];
    $data->pageId = (int) $_GET['pageid'];

    $pheanstalk
        ->useTube('page_update')
        ->put(json_encode($data));

    echo 'ok';
}
