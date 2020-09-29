<?php

class Explorer
{
    private $path,
            $breadcrumbs,
            $directory,
            $file;

    public function __construct() {
        $this->path = !empty($_GET['path']) ? $_GET['path'] : '/';
        $this->breadcrumbs = new Breadcrumbs();
        $this->directory = new Dir();
        $this->file = new File();
    }        

    /**
     * Формирует контент.
     * В зависимости от типа пути вызывает показывает содержимое директории или файла.
     * 
     * @return void
     */
    public function run(): void
    {
        $this->breadcrumbs->show($this->path);

        if (is_dir($this->path)) {
            $this->directory->show($this->path);
        } else {
            $this->file->show($this->path);
        }
    }
}