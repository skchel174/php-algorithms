<?php

class Dir
{
    /**
     * Получает и передает в цикле в метод render список директорий и фалов по пути, 
     * указанном во входном параметре.
     * Игнорирует идентификаторы текущей и родительской директорий.
     * 
     * @param string $path путь к директории
     * @return void
     */
    public function show($path): void
    {
        $iterator = new DirectoryIterator($path);
        $files = new SplQueue();
       
        while ($iterator->valid()) {
            $current = $iterator->current();
    
            if ($current->isDot()) {
                $iterator->next();
                continue;
            }
    
            if (!$current->isDir()) {
                $files->enqueue(clone $current);
                $iterator->next();
                continue;
            }
            
            $this->render($current->getPathname(), $current->getFilename());
            $iterator->next();
        }
    
        $files->rewind();
        while ($files->valid()) {
            $file = $files->current();
            $this->render($file->getPathname(), $file->getFilename());
            $files->next();
        }
    }

    /**
     * Выводит разметку для текущих фала или директории.
     * 
     * @param string $filePath
     * @param string $fileName
     * @return void
     */
    protected function render(string $filePath, string $fileName): void
    {
        echo "<br><a href='/index.php?path=$filePath'>$fileName</a>";
    }
}