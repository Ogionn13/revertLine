<?php

namespace App;

class RevertLine
{
    protected string $line;
    protected int $lenLine;
    protected array $upperPositions;

    public function __construct(string $line)
    {
        $this->line = mb_convert_encoding($line, 'windows-1251');
        $this->lenLine = strlen($this->line);
        $this->upperPositions = [];
    }

    public function revert(): string
    {
        $revertLine = $this->revertChartersSetUpperPosition();

        return $this->updateChartersRegister($revertLine);
    }

    protected function revertChartersSetUpperPosition(): string
    {
        $result = '';
        $word = '';
        for ($i = 0; $i < $this->lenLine; ++$i) {
            $charCode = ord($this->line[$i]);
            if ($this->isChar($charCode)) {
                $charCode = $this->charToLowerSetUpperPosition($charCode, $i);
                $word = chr($charCode).$word;
            } else {
                $result .= $word.chr($charCode);
                $word = '';
            }
        }
        $result .= $word;

        return $result;
    }

    protected function updateChartersRegister(string $preRevert): string
    {
        foreach ($this->upperPositions as $pos) {
            $preRevert[$pos] = $this->upperChar($preRevert[$pos]);
        }

        return mb_convert_encoding($preRevert, 'UTF-8', 'Windows-1251');
    }

    protected function isChar(int $charCode): bool
    {
        if ($charCode > 191 && $charCode < 256) {
            return true;
        }
        if (168 == $charCode || 184 == $charCode) {
            return true;
        }

        return false;
    }

    protected function charToLowerSetUpperPosition(int $charCode, int $position): int
    {
        if ($charCode > 223 || 184 == $charCode) {
            return $charCode;
        }
        if (168 == $charCode) {
            $charCode += 16;
        } else {
            $charCode += 32;
        }
        $this->upperPositions[] = $position;

        return $charCode;
    }

    protected function upperChar(string $char): string
    {
        $charCode = ord($char);
        if (184 == $charCode) {
            $charCode -= 16;
        } else {
            $charCode -= 32;
        }

        return chr($charCode);
    }
}
