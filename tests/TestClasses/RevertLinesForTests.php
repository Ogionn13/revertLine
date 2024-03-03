<?php

namespace TestClasses;

class RevertLinesForTests extends \App\RevertLine
{
    public function publicIsChar(int $charCode): bool
    {
        return $this->isChar($charCode);
    }
    public function publicRevertChartersSetUpperPosition(): string
    {
        return $this->revertChartersSetUpperPosition();
    }

    public function publicCharToLowerSetUpperPosition(int $charCode, int $position): int
    {
        return $this->charToLowerSetUpperPosition($charCode, $position);
    }

    public function publicUpperChar(string $char): string
    {
        return $this->upperChar($char);
    }

    public function publicUpdateChartersRegister(string $preRevert): string
    {
        return $this->updateChartersRegister($preRevert);
    }

    public function getUpperPositions(): array
    {
        return $this->upperPositions;
    }

    public function setUpperPositions(array $upperPositions): void
    {
        $this->upperPositions = $upperPositions;
    }

}
