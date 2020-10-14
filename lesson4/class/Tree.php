<?php

class Tree
{
    public ?Node $root;

    /**
     * @param Node $node - корневой узел, по умолчанию null
     * @return void
     */
    public function __construct(?Node $node = null)
    {
        $this->root = $node;
    }

    // INSERT

    /**
     * 
     * @param int $value - значение узла
     * @return void
     */
    public function insert(int $value): Tree
    {           
        if (is_null($this->root)) {
            $this->root = new Node($value);
        } else {
            $this->insertNode($this->root, $value);
        }

        return $this;
    }

    /**
     * 
     * 
     */
    private function insertNode(?Node &$node, int $value): void
    {
        if (is_null($node)) {
            $node = new Node($value);
            return;
        }

        if ($node->value > $value) {
            $this->insertNode($node->left, $value);
        } else {
            $this->insertNode($node->right, $value);
        }
    }

    // FIND

    /**
     * 
     */
    public function find(int $value): ?Node
    {
        return $this->findNode($this->root, $value);
    }

    /**
     * 
     */
    private function &findNode(?Node &$node, int $value): ?Node
    {
        if (is_null($node)) {
            $res = null;
            return $res;
        }

        if ($node->value == $value) {
            return $node;
        }

        if ($node->value > $value) {
            return $this->findNode($node->left, $value);
        } else {
            return $this->findNode($node->right, $value);   
        }
    }

    // DELETE

    public function delete(int $value)
    {
        $node = &$this->findNode($this->root, $value);

        if (is_null($node)) {
            return false;
        }
        
        $this->deleteNode($node);
        return true;
    }

    private function deleteNode(Node &$node)
    {
        
        if (is_null($node->left)) {
            // если нет левого поддерева подставляем правое, если нет обоих деревьев - null
            $node = $node->right;
        } elseif (is_null($node->right)) {
            // если нет правого поддерева подставляем левое
            $node = $node->left;         
        } else {
            
            $minNode = &$this->minNode($node->right);

            $node->value = $minNode->value;
            $minNode = null;  
        }

        return true;
    }

    // MINIMUM 

    public function minimum(): ?Node
    {
        if (is_null($this->root)) {
            return null;
        }

        return $this->minNode($this->root);
    }

    private function &minNode(&$node)
    {
        if (is_null($node->left)) {
            return $node;
        }

        return $this->minNode($node->left);
    }

    // MAXIMUM

    public function maximum(): ?Node
    {
        if (is_null($this->root)) {
            return null;
        }

        return $this->maxNode($this->root);
    }   

    private function &maxNode(&$node)
    {
        if (is_null($node->right)) {
            return $node;
        }

        return $this->maxNode($node->right);
    }

    // ОБХОД

    public function traverse(string $flag, callable $function)
    {
        if (is_null($this->root)) {
            return null;
        }

        $node = &$this->root;
        $flag = strtoupper($flag);

        $this->$flag($node, $function);
    }

    private function LRN(?Node &$node, callable $function)
    {
        if (is_null($node)) {
            return;
        }
        
        $this->LRN($node->left, $function);
        $this->LRN($node->right, $function);
        $function($node);
    }

    private function LNR(?Node &$node, callable $function)
    {
        if (is_null($node)) {
            return;
        }
        
        $this->LNR($node->left, $function);
        $function($node);
        $this->LNR($node->right, $function);
        
    }

    private function NLR(?Node &$node, callable $function)
    {
        if (is_null($node)) {
            return;
        }

        $function($node);
        $this->NLR($node->left, $function);
        $this->NLR($node->right, $function);
    }    
}

