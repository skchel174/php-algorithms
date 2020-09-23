<?php

class BracketsFilter extends FilterIterator
{
    private $brackets;
    private $uncloseQuotes;
    private $quotes = ['\'', '"', '`'];
    
    /**
     * @param Iterator $iterator - фильтруемые символы
     * @param array $brackets - шаблонам для фильтрации
     */
    public function __construct(Iterator $iterator, array $brackets) 
    {
        parent::__construct($iterator);
        $this->brackets = $brackets;
        $this->uncloseQuotes = new SplStack();
    }

    /**
     * Возвращает элементы, хранящиеся в ключах или значениях $this->brackets.
     * Игнорирует любые символы, находяящиеся внутри кавычек.
     * 
     * @return bool
     */
    public function accept(): bool
    {
        $symbol = $this->getInnerIterator()->current();

        if (in_array($symbol, $this->quotes)) {
            $this->setUncloseQuotes($symbol);
        }    

        if (!$this->uncloseQuotes->isEmpty()) {
            return false;
        }

        if (!array_key_exists($symbol, $this->brackets) && !in_array($symbol, $this->brackets)) {
            return false;
        }

        return true;
    }

    /**
     * Сохраняет незакрытые кавыски
     * 
     * @param string $symbol - кавычки
     */
    protected function setUncloseQuotes(string $symbol): void
    {
        if (!$this->uncloseQuotes->isEmpty() && $this->uncloseQuotes->top() == $symbol) {
            $this->uncloseQuotes->pop();
        } else {
            $this->uncloseQuotes->push($symbol);
        }
    }
}

/**
 * Извлекает из строки скобки, игнорируя символы, содержащиеся внутри кавычек.
 * В цикле проходит по массиву с отфильтрованными скобками, сохраняя их в стек с незакрыми скобками.
 * Дойдя до закрывающейся скобки, проверяет верхний элемент стека на родственность.
 * В случае успешной проверки удаляет его, исключая из проверки парные скобки. 
 * По завершении цикла возвращает результат проверки на пустоту стека с незакрытыми скобками.
 * 
 * @param string $str
 * @return bool
 */
function bracketsBalance(string $str): bool
{
    $brackets = ['[' => ']', '{' => '}', '(' => ')'];
    $symbolsArray = new ArrayObject(mb_str_split($str));
    $filtered = new BracketsFilter($symbolsArray->getIterator(), $brackets);
    $unclose = new SplStack();

    foreach ($filtered as $v) {
        if (array_key_exists($v, $brackets)) {
            $unclose->push($v);
            continue;
        }
    
        if (!$unclose->isEmpty() && $brackets[$unclose->top()] == $v) {
            $unclose->pop();
        } else {
            $unclose->push($v);
        }
    }
    
    return $unclose->isEmpty();
}

var_dump(bracketsBalance('Это тестовый вариант проверки (задачи со скобками). И вот еще скобки: {[][()]}'));
var_dump(bracketsBalance('((a + b)/ c) - 2'));
var_dump(bracketsBalance('([ошибка)'));
var_dump(bracketsBalance('"(")'));
