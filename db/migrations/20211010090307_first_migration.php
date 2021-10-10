<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FirstMigration extends AbstractMigration
{
    public $modx;

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->modx();

        if ($resource = $this->modx->getObject('modResource', ['pagetitle' => 'Home'])) {
            $resource->set('pagetitle', 'Главная страница');
            $resource->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }

    public function modx()
    {
        if (!$this->modx) {
            // Include MODX class
            define('MODX_API_MODE', true);
            require dirname(dirname(__DIR__)) . '/www/index.php';
            $this->modx =& $modx;
        }
        return $this->modx;
    }

    public function log($message = '')
    {
        echo $message . PHP_EOL;
    }

}
