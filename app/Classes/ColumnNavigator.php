<?php


namespace App\Classes;


class ColumnNavigator extends CellNavigator
{
    protected function makeColumn(): string
    {
        if ($this->start >= 65 && $this->start < 91) {
            return sprintf('%c', $this->start);
        } elseif ($this->start >= 91 && $this->start < 117) {
            $current = $this->start - 91;
            $current += 65;
            return sprintf('A%c', $current);
        } elseif ($this->start >= 117 && $this->start < 142) {
            $current = $this->start - 117;
            $current += 65;
            return sprintf('B%c', $current);
        } else {
            return sprintf('%c', $this->start);
        }
    }

    public function currentColumn(): string
    {
        return $this->makeColumn();
    }

    public function nextColumn(): string
    {
        $this->start++;
        return $this->makeColumn();
    }

    public function previousColumn(): string
    {
        $this->start--;
        return $this->makeColumn();
    }
}