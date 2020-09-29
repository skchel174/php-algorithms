<?php

class File
{
    /**
     * Проверяет файл на читаемость и выводит его содержимое построчно.
     * 
     * @param string $path - путь к файлу
     * @return void
     */
    public function show($path) {
        $file = new SplFileObject($path);
        if ($file->isReadable()) {
            while (!$file->eof()) {
                echo $file->fgets();
            }
        }
    }
}