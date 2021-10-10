<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InstallPackages extends AbstractMigration
{
    public $modx;

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->modx();

        $packages = [
            'Ace' => [
                'version'     => '1.9.2-pl',
                'service_url' => 'modx.com',
            ]
        ];

        foreach ($packages as $key => $data) {
            $installed = $this->modx->getIterator('transport.modTransportPackage', ['package_name' => $key]);
            foreach ($installed as $package) {
                if ($package->compareVersion($data['version'], '<=')) {
                    if (!$package->installed) {
                        $package->install();
                    }

                    continue(2);
                }
            }

            if ($this->installPackage($key, $data)) {
                $this->log('Installed package: ' . $key);
            } else {
                $this->log('Failed to install package: ' . $key, 'ERROR');
            }
        }
    }

    public function installPackage($packageName, array $data = [])
    {
        $_SESSION = [];
        $this->modx->initialize('mgr');

        $provider = null;
        if (!empty($data['service_url'])) {
            $provider = $this->modx->getObject('transport.modTransportProvider', [
                'service_url:LIKE' => '%' . $data['service_url'] . '%',
            ]);
        }

        if (!$provider) {
            $provider = $this->modx->getObject('transport.modTransportProvider', 1);
        }

        $version = $this->modx->getVersionData();
        $response = $provider->request('package', 'GET', [
            'supports' => $version['code_name'] . '-' . $version['full_version'],
            'query'    => $packageName,
        ]);

        if (!empty($response)) {
            $foundPackages = simplexml_load_string($response->response);
            foreach ($foundPackages as $foundPackage) {
                if (preg_match('#^' . $packageName . '\b#i', (string) $foundPackage->name)) {
                    if ($package = $provider->transfer((string) $foundPackage->signature, null, ['location' => (string) $foundPackage->location])) {
                        return $package->install();
                    }

                    break;
                }
            }
        } else {
            return false;
        }

        return true;
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
