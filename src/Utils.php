<?php

class Utils
{
    /** @var Filesystem */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /*public function aList()
    {
        $dir = 'a/';
        return $this->list($dir);
    }*/

    /*protected function list($dir)
    {
        if (!$this->filesystem->isDir($dir)) {
            $this->filesystem->createFile($dir);
        }

        $this->filesystem->createFile('b');
        return $this->filesystem->write($dir);
    }*/

    public function log($data)
    {
        $dir = 'logs/';
        if (!$this->filesystem->isDir($dir)) {
            $this->filesystem->createFile($dir);
        }

        $this->filesystem->createFile($dir.'log_1.json');
        $this->filesystem->write($dir.'log_1.json', $data);
    }
}