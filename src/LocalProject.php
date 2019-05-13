<?php

namespace Moogeek\TildaClone;

/**
 * Filesystem helper class.
 */
class LocalProject
{
    /**
     * @var string project name
     */
    private $_name;

    /**
     * @var string root project directory
     */
    private $_basePath;

    /**
     * @var string css files directory
     */
    private $_cssPath;

    /**
     * @var string js files directory
     */
    private $_jsPath;

    /**
     * @var string images directory
     */
    private $_imgPath;

    /**
     * @param string $name     project name
     * @param string $basePath project directory
     */
    public function __construct(string $name, string $basePath = '.')
    {
        $this->setName($name);
        $this->setBasePath($basePath);
    }

    /**
     * @param string $name project name
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $directory project directory
     */
    public function setBasePath(string $directory): void
    {
        $this->_basePath = $directory;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->_basePath;
    }

    /**
     * @return string
     */
    public function getProjectPath(): string
    {
        return $this->_basePath.DIRECTORY_SEPARATOR.$this->_name;
    }

    /**
     * @param string $directory css folder path
     */
    public function setCssPath(string $directory): void
    {
        $this->createDirectory($directory);
        $this->_cssPath = $directory;
    }

    /**
     * @param string $directory js folder path
     */
    public function setJsPath(string $directory): void
    {
        $this->createDirectory($directory);
        $this->_jsPath = $directory;
    }

    /**
     * @param string $directory images folder path
     */
    public function setImagesPath(string $directory): void
    {
        $this->createDirectory($directory);
        $this->_imgPath = $directory;
    }

    /**
     * @param string $source file contents source
     * @param string $target target file name
     */
    public function cloneCssFile(string $source, string $target): void
    {
        $content = file_get_contents($source);
        $filePath = $this->_cssPath.DIRECTORY_SEPARATOR.$target;

        $this->createFile($filePath, $content);
    }

    /**
     * @param string $source file contents source
     * @param string $target target file name
     */
    public function cloneJsFile(string $source, string $target): void
    {
        $content = file_get_contents($source);
        $filePath = $this->_jsPath.DIRECTORY_SEPARATOR.$target;

        $this->createFile($filePath, $content);
    }

    /**
     * @param string $source file contents source
     * @param string $target target file name
     */
    public function cloneImageFile(string $source, string $target): void
    {
        $content = file_get_contents($source);
        $filePath = $this->_imgPath.DIRECTORY_SEPARATOR.$target;

        $this->createFile($filePath, $content);
    }

    /**
     * @param string $directory directory name
     */
    public function createDirectory(string $directory): void
    {
        $dir = $this->_cleanPath($this->getProjectPath().DIRECTORY_SEPARATOR.$directory);

        if (!file_exists($dir)) {
            if (!mkdir($dir, 0775, true)) {
                throw new \Exception('Could not create directory "'.$dir.'"');
            }
        }
    }

    /**
     * @param string $filename filename
     * @param string $content  file contents
     */
    public function createFile(string $filename, ?string $content): void
    {
        $fullPath = $this->_cleanPath($this->getProjectPath().DIRECTORY_SEPARATOR.$filename);

        if (file_put_contents($fullPath, $content) === false) {
            throw new \Exception('Could not create file "'.$filename.'"');
        }
    }

    /**
     * @param string $path path
     */
    private function _cleanPath(?string $path)
    {
        return preg_replace('#/+#', '/', $path);
    }
}
