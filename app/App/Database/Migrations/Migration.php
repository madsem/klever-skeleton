<?php

namespace Klever\App\Database\Migrations;


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Migration extends AbstractMigration
{

    protected $schema;

    function init()
    {
        $this->schema = (new Capsule)->schema();
    }
}