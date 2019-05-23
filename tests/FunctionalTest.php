<?php
declare(strict_types=1);

namespace PhpsuTestcase\FunctionalTest;

use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testSshProduction()
    {
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
', `vendor/bin/phpsu ssh prod "ls -lRh --time-style=long-iso" --no-ansi`);
    }

    public function testSyncProductionLocal()
    {
        `rm -rf tmp/localInstance/var/*`;
        `echo 'DROP DATABASE IF EXISTS sequelmovie;' | mysql -uroot -plocalPw -h local-db`;
        $this->assertSame('tmp/localInstance:
var

tmp/localInstance/var:
', `ls -R tmp/localInstance`);
        $this->assertSame('Database
information_schema
mysql
performance_schema
sys
', `echo 'SHOW DATABASES;' | mysql -uroot -plocalPw -h local-db`);
        $result = `vendor/bin/phpsu sync prod local --no-ansi`;
        $this->assertStringContainsString('filesystem:Image Uploads: ✔', $result);
        $this->assertStringContainsString('database:app: ✔', $result);
        $this->assertSame('tmp/localInstance:
var

tmp/localInstance/var:
storage

tmp/localInstance/var/storage:
44134427.png
test.log
', `ls -R tmp/localInstance`);
        $this->assertSame('6d6f917c92b34c7d187ea62d156c1b10
', `mysqldump -uroot -plocalPw -h local-db sequelmovie --skip-comments | md5sum | cut -f1 -d" "`);
    }
}
