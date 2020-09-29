<?php

class Breadcrumbs
{
    /**
     * Проходит в цикле массив, созданный из пути к текущей директории или файлу,
     * передает в метод render имя вложенной папки или файла и путь к ним.
     * 
     * @param string $path путь к директории или файлу
     * @return void
     */
    public function show($path): void 
    {
        $breadcrumbs = explode('/', $path);
        array_shift($breadcrumbs);

        foreach ($breadcrumbs as $link) {
            $href .= "/$link";
            $breadcrumb = !empty($link) ? $link : 'main';
            
            $this->render($href, $breadcrumb);
        }
    }

    /**
     * Выводит разметку.
     * 
     * @param string $filePath
     * @param string $fileName
     * @return void
     */
    protected function render(string $filePath, string $fileName): void
    {
        echo "<a href='/index.php?path=$filePath'>$fileName</a> | ";
    }
}