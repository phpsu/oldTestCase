<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testSshProduction()
    {
        $result = `vendor/bin/phpsu ssh prod "ls -lRh --time-style=long-iso"`;
        $this->assertSame('.:
total 8.0K
-rw-r--r-- 1 user user  137 2019-01-11 08:28 index.php
drwxrwxr-x 3 user user 4.0K 2019-03-08 17:03 var

./var:
total 4.0K
drwxrwxr-x 2 user user 4.0K 2019-03-09 15:28 storage

./var/storage:
total 36K
-rw-rw-r-- 1 user user 29K 2019-03-09 15:28 44134427.png
-rw-rw-r-- 1 user user 306 2019-03-09 15:26 test.log
', $result);
        $this->markTestIncomplete('Need better assertions if ssh was successfully.');
    }

    public function testSyncProductionLocal()
    {
        $result = `vendor/bin/phpsu sync prod local`;
        $this->assertStringContainsString('filesystem:Image Uploads: ✔', $result);
        $this->assertStringContainsString('database:app: ✔', $result);
        $this->markTestIncomplete('Need more assertions if sync was successfully. ls or mysql command ...');
    }
}
