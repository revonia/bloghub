<?php


namespace Revonia\BlogHub\BlogServices;


use Revonia\BlogHub\Application;
use Revonia\BlogHub\Env;
use Revonia\BlogHub\Interfaces\BlogService;
use Revonia\BlogHub\ServiceNeedReadEnv;

use function Revonia\BlogHub\required;

class FileBlogService implements BlogService
{
    use ServiceNeedReadEnv;

    private static $env;

    /**
     * @var Application
     */
    private static $app;

    public function create($data)
    {
        $distPath = self::$env[self::envNameWithPerfix('DIST_PATH')];
        $dist = realpath(is_dir($distPath) ? $distPath : self::$app->getWorkingDir());
        if (!$dist) {
            throw new \RuntimeException();
        }
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public static function boot(Application $app)
    {
        self::$app = $app;
        self::$env = self::selectEnv([
            'DIST_PATH' => required(),
        ]);
    }
}