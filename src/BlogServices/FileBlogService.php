<?php


namespace Revonia\BlogHub\BlogServices;


use Revonia\BlogHub\Application;
use Revonia\BlogHub\Interfaces\BlogService;
use Revonia\BlogHub\ServiceNeedReadEnv;

use function Revonia\BlogHub\required;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FileBlogService implements BlogService
{
    use ServiceNeedReadEnv;

    private $env;

    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app, $env)
    {
        $this->app = $app;
        $this->env = self::selectEnv([
            'DIST_PATH' => required(),
        ]);
    }

    public function create($data)
    {
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

    public static function boot(ContainerBuilder $container)
    {
        $container->autowire(self::class);
    }
}