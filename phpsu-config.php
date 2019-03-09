<?php
declare(strict_types=1);

use PHPSu\Config\GlobalConfig;
use PHPSu\Config\SshConnection;

$config = new GlobalConfig();
$config->addFilesystem('Image Uploads', 'var/storage');

$config->addSshConnectionObject((new SshConnection())->setHost('production')->setUrl('ssh://user@production')->setFrom(['proxy']));
$config->addSshConnectionObject((new SshConnection())->setHost('proxy')->setUrl('ssh://user@proxy'));
$config->addSshConnectionObject((new SshConnection())->setHost('testing')->setUrl('ssh://user@testing'));

$config->setDefaultSshConfig([
    'IdentityFile' => './id_rsa',
    'ForwardAgent' => 'yes',
    'StrictHostKeyChecking' => 'no',
    'UserKnownHostsFile' => '/tmp/hosts_file',
]);

$config->addAppInstance('production', 'production', '/var/www/production')
    ->addDatabase('app', 'mysql://root:productionPw@production-db/sequelmovie');
$config->addAppInstance('staging', 'production', '/var/www/staging')
    ->addDatabase('app', 'mysql://root:stagingPw@staging-db/sequelmovie');
$config->addAppInstance('testing', 'testing', '/var/www/testing')
    ->addDatabase('app', 'mysql://root:testingPw@testing-db/sequelmovie');
$config->addAppInstance('local', '', 'tmp/localInstance')
    ->addDatabase('app', 'mysql://root:localPw@local-db/sequelmovie');

return $config;
