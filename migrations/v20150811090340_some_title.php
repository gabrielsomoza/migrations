<?php

namespace Migrations;

use Baleen\Migrations\Migration\SimpleMigration;

class v20150811090340_some_title extends SimpleMigration
{

    public function up()
    {
        sleep(rand(1,3));
    }

    public function down()
    {
        sleep(rand(1,3));
    }


}

