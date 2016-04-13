<?php

if (empty($argv[2]) || in_array($argv[1], array('help', '-h', '--help')))
{
    echo 'usage: php package.php /source/dir /dest/dir <Module_Name>'."\n";
    exit;
}

class Package
{
    public static $_allowedExtensions = array('css', 'js', 'php', 'phtml', 'xml');

    protected $_basePath;
    protected $_composerBasePath;

    public function __construct()
    {
        $this->_basePath         = dirname(__FILE__).DIRECTORY_SEPARATOR;
        $this->_composerBasePath = dirname(dirname(dirname(dirname(dirname(__FILE__))))).DIRECTORY_SEPARATOR;
    }

    public function getComposerBasePath()
    {
        return $this->_composerBasePath;
    }

    public function addLicense($path, $module)
    {
        if (file_exists($path) && !is_dir($path))
        {
            $nameParts = explode('.', basename($path));
            $extension = array_pop($nameParts);

            if (in_array($extension, self::$_allowedExtensions))
            {
                $license = str_replace(
                    array('{$module}', '{$year}'),
                    array($module, date('Y')),
                    file_get_contents($this->_basePath.'license/license.txt'));

                $license = str_replace('{$license}', $license,
                    file_get_contents($this->_basePath.'license/license.'.$extension));

                $newContent = file_get_contents($path);
                $newContent = str_replace(array('<?xml version="1.0" encoding="UTF-8"?>', '<?xml version="1.0"?>'), '', $newContent);

                file_put_contents($path, $license.$newContent);
            }

            return true;
        }

        $dir = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);

        foreach ($dir as $child)
        {
            /* @var $child SplFileInfo */
            if ($dir->isDot())
            {
                continue;
            }

            $this->addLicense($child->getRealPath(), $module);
        }
    }

    public function copy($sourcePath, $destPath)
    {
        $destPath = $this->getComposerBasePath().$destPath;

        system('rm -rf '.$destPath);
        system('mkdir -p '.$destPath);
        system('cp -R '.$this->getComposerBasePath().$sourcePath.' '.$destPath);
    }
}

$package = new Package();

$sourcePath = $argv[1];
$destPath   = $argv[2].'/'.date('YmdHis').'/';
$moduleName = isset($argv[3]) ? $argv[3] : '';

$package->copy($sourcePath, $destPath);
$package->addLicense($package->getComposerBasePath().$destPath, $moduleName);

