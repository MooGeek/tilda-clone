<?php

namespace Moogeek\TildaClone;

require_once 'vendor/autoload.php';

use Pheanstalk\Pheanstalk;
use Pheanstalk\Job;
use Moogeek\PheanstalkWorker\Worker;
use Moogeek\Tilda\Account as TildaAccount;
use Moogeek\TildaClone\LocalProject as TildaLocalProject;
use Moogeek\TildaClone\Cloner as TildaCloner;

$dotenv = \Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$pheanstalk = Pheanstalk::create('queue-server');
$worker = new Worker($pheanstalk);

$worker->register(
    'page_update',
    function (Job $job) {
        $data = json_decode($job->getData());
        echo sprintf("Received job to update page %s\n", $data->pageId);

        try {
            $account = new TildaAccount(getenv('PUBLIC_KEY'), getenv('SECRET_KEY'));
            $remoteProject = $account->getProject($data->projectId);
            $remotePage = $remoteProject->getPage($data->pageId);

            $localProject = new TildaLocalProject((string) $data->projectId, getenv('BASE_PATH'));

            $cloner = new TildaCloner($remoteProject, $localProject);
            $cloner->cloneProject(getenv('PER_FILE_DELAY'));
            $cloner->clonePage($remotePage, getenv('PER_FILE_DELAY'));

            echo sprintf("Page %s updated\n", $data->pageId);
        } catch (\Exception $e) {
            echo sprintf('Encountered error processing page %s: %s', $data->pageId, $e->getMessage());
        }
    }
);

$worker->process();
