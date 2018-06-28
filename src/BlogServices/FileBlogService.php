<?php


namespace Revonia\BlogHub\BlogServices;


use Revonia\BlogHub\Application;
use Revonia\BlogHub\Env;
use Revonia\BlogHub\HasServicePrefix;
use Revonia\BlogHub\Interfaces\BlogService;
use function Revonia\BlogHub\required;
use Symfony\Component\Filesystem\Filesystem;

class FileBlogService implements BlogService
{
    use HasServicePrefix;
    /**
     * @var array
     */
    private $env;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $distPath;

    public function __construct(Application $app, Filesystem $fs, Env $env)
    {
        $this->app = $app;
        $this->fs = $fs;
        $this->env = $env->setPerfix(static::getPrefix())
            ->setDefaults([
                'DIST_PATH' => required(),
                'POST_FILE_NAME' => 'index.html',
                'GENERATE_INDEX' => true,
                'INDEX_FILE_NAME' => 'index.html',
            ]);

        $distPathVal = $this->env['DIST_PATH'];

        $this->distPath = $fs->isAbsolutePath($distPathVal)
            ? $distPathVal
            : $this->app->getWorkingDir() . DIRECTORY_SEPARATOR . $distPathVal;

        if (is_file($this->distPath)) {
            throw new \RuntimeException($env->envNameWithPrefix('DIST_PATH') . ' must point to a directory, file ' . $this->distPath . ' given.');
        }
    }

    public function create($data)
    {
        $this->fs->mkdir($this->distPath);

        return $data['title'];
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
}
