<?php

namespace Moogeek\TildaClone;

require_once 'vendor/autoload.php';

use Moogeek\Tilda\Account as TildaAccount;
use Moogeek\TildaClone\LocalProject as TildaLocalProject;
use Moogeek\TildaClone\Cloner as TildaCloner;

$dotenv = \Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$account = new TildaAccount(getenv('PUBLIC_KEY'), getenv('SECRET_KEY'));

$projects = explode(',', getenv('PROJECTS'));

foreach ($projects as $projectId) {
    $remoteProject = $account->getProject($projectId);
    $localProject = new TildaLocalProject($projectId, getenv('BASE_PATH'));

    $cloner = new TildaCloner($remoteProject, $localProject);
    $cloner->clone(getenv('PER_PAGE_DELAY'), getenv('PER_FILE_DELAY'));
}
