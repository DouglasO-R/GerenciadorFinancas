<?php

use Phinx\Migration\AbstractMigration;

class CreateUserAdminData extends AbstractMigration
{
    public function up()
    {
        $app = require __DIR__.'/../bootstrap.php';
        $auth = $app->getService('auth');

        $users = $this->table('users');
        $users->insert([
            'first_name' => 'admin',
            'last_name' => 'system',
            'email' => 'admin@user.com',
            'password' => $auth->hashPassword('12345'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y=m=d H:i:s')
        ])->save();
    }

    public function down()
    {
        $this->execute("DELETE FROM users WHERE email = 'admin@user.com'");
    }
}
