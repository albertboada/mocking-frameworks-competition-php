<?php

class Filesystem
{
    public function isDir(string $dir): bool
    {
        return false;
    }

    public function createFile(string $filePath)
    {
    }

    public function write(string $filePath, string $data)
    {
    }

    public function list(string $dir): array
    {
        return ['lol'];
    }
}