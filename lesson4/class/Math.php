<?php

class Math
{
    // допустимые цифры в числах, возможно дробные
    private array $numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
    private array $brackets = ['open' => '(', 'close' => ')'];
    // массив с операторами, в котором ключ - оператор, значение - приоритет
    private array $operators = [
        '^' => 3, 
        '*' => 2, 
        '/' => 2,
        '+' => 1,
        '-' => 1,
    ];
    // массив, в кторый складываем операнды и операторы
    private array $operands = [];
    // стек для временного хранения скобок и операторов
    private SplStack $expressionStack;
    // стек для временного хранения значений в процессе их вычисления при обходе дерева
    private SplStack $traverseStack;
    private Tree $tree;

    /**
     * инициализируем хранилища данных
     */
    public function __construct()
    {
        $this->expressionStack = new SplStack();
        $this->traverseStack = new SplStack();
        $this->tree = new Tree();
    }

    /**
     * Преобразуем выражение в постфиксную форму, на основе которой строим дерево.
     * Обходим дерево в обратной последовательности помещая значения узлов в стек.
     * Если встречаем оператор, вычисляем результат выражения с двумя последними добавленными операндами.
     * Выводим результат вычислений. Очищаем данные, хранящиеся в свойствах объекта.
     * 
     * @param string $expression - математическое выражение
     * @return int $result - результат вычисления 
     */
    public function calculate(string $expression): int
    {
        $this->convert($expression);

        $this->buildTree($this->operands, $this->tree->root);

        // метод traverse принимает тип обхода и анонимную функцию,
        // рекурсивно вызывая ее для каждого узла
        $this->tree->traverse('LRN', function($node) {
            $value = $node->value;
            
            // если занчение - оператор и в стеке больше одного элемента
            // вытаскиваем последние два и выполняем операцию,
            // в противном случае помещяем значение в стек
            if (array_key_exists($value, $this->operators) &&
                $this->traverseStack->count() > 1
            ) {
                $b = $this->traverseStack->pop();
                $a = $this->traverseStack->pop();
                $res = $this->execute($value, $a, $b);
                
                $this->traverseStack->push($res);
            } elseif (!is_null($value)) {
                $this->traverseStack->push($value);
            }
        });

        // в стеке должно остаться одно значение - результат всех вычислений
        // сохраняем его в переменную
        $result = $this->traverseStack->pop();

        // print_r($this->tree);
        // print_r($this->operands);

        // очищаем все свойства объекта, которые могут хранить в себе какие-либо данные
        // для повторного их использования
        $this->clearData();

        return $result;
    }

    /**
     * Проходим построке с выражением,
     * сохраняя операторы и операнды в массив в порядке постфиксной записи.
     * 
     * @param string $expression - математическое выражение
     * @return void
     */
    protected function convert(string $expression): void
    {
        for ($i = 0; $i < strlen($expression); $i++) {
            $value = $expression[$i];

            // если пустая строка - пропускаем цикл
            if ($value == ' ') {
                continue;
            }
            
            // обрабатываем случай с оператором
            if (array_key_exists($value, $this->operators)) {
                $this->setOperator($value);
            // обрабатываем случай с открывающей скобкой
            } elseif ($value == $this->brackets['open']) {
                $this->expressionStack->push($value);
            // обрабатываем случай с закрывающей скобкой
            } elseif ($value == $this->brackets['close']) {
                $this->setBracketsOperands();
            // обрабатываем случай с операндом   
            } else {
                $i = $this->setOperands($i, $expression);
            }
        }
        
        // помещаем в массив оставшиеся в стеке операторы
        while (!$this->expressionStack->isEmpty()) {
            $this->operands[] = $this->expressionStack->pop();
        }
    }

    /**
     * Если в стеке есть операторы, сохраняем их в массив в порядке увеличения приоритета,
     * в противном случае сохраняем оператор в стек.
     * 
     * @param string $operator - математическое выражение
     * @return void
     */
    protected function setOperator(string $operator): void
    {
        while (
            !$this->expressionStack->isEmpty() && 
            array_key_exists($this->expressionStack->top(), $this->operators) &&
            $this->operators[$this->expressionStack->top()] >= $this->operators[$operator]
        ) {
            $this->operands[] = $this->expressionStack->pop();
        }
        $this->expressionStack->push($operator);
    }

    /**
     * Передаем из стека в массив все операторы, пока не встретится открывающая скобка.
     * Скобку удаляем.
     * 
     * @return void
     */
    protected function setBracketsOperands(): void
    {
        while ($this->expressionStack->top() != $this->brackets['open']) {
            $this->operands[] = $this->expressionStack->pop();
        }
        $this->expressionStack->pop();
    }

    /**
     * Все последующие значения строки, являющиеся цифрами или точкой (для дроби),
     * записываем в качестве единого числа и сохраняем его в массив.
     * 
     * @param int $index - индекс текущего значения строки
     * @param string $expression - строка с выражением
     * @return int $index - индекс элемента, не являющегося цифрой или точкой
     */
    protected function setOperands(int $index, string $expression): int
    {
        $value = '';
        while (@in_array($expression[$index], $this->numbers)) {
            $value .= $expression[$index];
            $index++;
        }
        $this->operands[] = $value;

        return $index - 1;
    }

    /**
     * Выстраиват из массива бинарное дерево.
     * Если узел не существует, создаем его, передавая в качестве значения последний элемент массива.
     * Делим оставшуюся часть массива на две части и передаем каждую из них в данный метод рекурсивно. 
     * Если в массиве остается менее 5 элементов, выстраиваем из них поддерево таким образом, 
     * чтобы оператор обязательно был узлом для двух операндов (при достаточном количестве значений в подмассиве).
     * 
     * @param array $expression - массив с элементами выражениея в постфиксной форме
     * @param Node &$node - ссылка на поддерево 
     * @return void
     */
    protected function buildTree(array $expression, ?Node &$node): void
    {
        if (is_null($node)) {
            $node = new Node(array_pop($expression));
        }
    
        $count = count($expression);
    
        if ($count > 4) {
            $middle = ceil($count / 2);
    
            list($expressionLeft, $expressionRight) = array_chunk($expression, $middle);
    
            $this->buildTree($expressionLeft, $node->left);
            $this->buildTree($expressionRight, $node->right);
        } else {   
            $last = array_pop($expression);
            $node->right = new Node($last);
    
            if (array_key_exists($last, $this->operators)) {
                if (!empty($expression)) $node->right->right = new Node(array_pop($expression));
                if (!empty($expression)) $node->right->left = new Node(array_pop($expression));
                if (!empty($expression)) $node->left = new Node(array_pop($expression));
            } else {
                if (!empty($expression)) $node->left = new Node(array_pop($expression));
                if (!empty($expression)) $node->left->right = new Node(array_pop($expression));
                if (!empty($expression)) $node->left->left = new Node(array_pop($expression));
            }       
        } 
    }

    /**
     * Вычисляем выражения во время обхода дерева
     * 
     * @param string $operator - оператор
     * @param string $a - операнд
     * @param string $b - операнд
     * @return int - результат операции
     */
    protected function execute(string $operator, string $a, string $b): int
    {
        switch ($operator) {
            case '+':
                return $a + $b;
            case '-':
                return $a - $b;
            case '/':
                return $a / $b;
            case '*':
                return $a * $b;
            case '^':
                return pow($a, $b);                
        }
    }

    /**
     * Очищаем данные объекта
     */
    protected function clearData() {
        $this->traverseStack = new SplStack();
        $this->expressionStack = new SplStack();
        $this->tree = new Tree();
        $this->operands = [];
    }
}
