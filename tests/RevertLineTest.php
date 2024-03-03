<?php

use PHPUnit\Framework\TestCase;
use TestClasses\RevertLinesForTests;

class RevertLineTest extends TestCase
{
    protected RevertLinesForTests $reverter;

    /**
     * @dataProvider dataProviderRevert
     */
    public function testRevert(string $line, string $revertLine)
    {
        $this->reverter = new RevertLinesForTests($line);
        $result = $this->reverter->revert();
        $this->assertEquals($revertLine, $result);
    }

    public function dataProviderRevert(): array
    {
        return [
            ['ёлка в лесу', 'аклё в усел'],
            ['ЁЛк!а В лесу.', 'КЛё!а В усел.'],
            ["О'Браен Жуё, Яа-ла-лА.", "О'Неарб Ёуж, Ая-ал-аЛ."],
        ];
    }

    /**
     * @dataProvider dataProviderRevertChartersSetUpperPosition
     */
    public function testRevertChartersSetUpperPosition(string $line, string $lowerRevertLine, array $upperCharPositions)
    {
        $this->reverter = new RevertLinesForTests($line);
        $result = $this->reverter->publicRevertChartersSetUpperPosition();
        $result = mb_convert_encoding($result, 'UTF-8', 'Windows-1251');

        $this->assertEquals($lowerRevertLine, $result);
        $this->assertEquals($upperCharPositions, $this->reverter->getUpperPositions());
    }

    public function dataProviderRevertChartersSetUpperPosition(): array
    {
        return [
            ['ёлка в лесу', 'аклё в усел', []],
            ['ЁЛк!а В лесу.', 'клё!а в усел.', [0, 1, 6]],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateChartersRegister
     */
    public function testUpdateChartersRegister(string $preRevert, array $upperPositions, string $correctLine)
    {
        $this->reverter = new RevertLinesForTests('');
        $preRevert = mb_convert_encoding($preRevert, 'Windows-1251');
        $this->reverter->setUpperPositions($upperPositions);
        $result = $this->reverter->publicUpdateChartersRegister($preRevert);

        $this->assertEquals($correctLine, $result);
    }

    public function dataProviderUpdateChartersRegister(): array
    {
        return [
            ['ёлка в лесу', [0, 2, 3, 8], 'ЁлКА в лЕсу'],
            ['ёлка в лесу', [], 'ёлка в лесу'],
        ];
    }

    /**
     * @dataProvider dataProviderUpperChar
     */
    public function testUpperChar(string $char, string $upperChar)
    {
        $this->reverter = new RevertLinesForTests('');
        $char = mb_convert_encoding($char, 'windows-1251');
        $result = $this->reverter->publicUpperChar($char);

        $result = mb_convert_encoding($result, 'UTF-8', 'Windows-1251');
        $this->assertEquals($upperChar, $result);
    }

    public function dataProviderUpperChar(): array
    {
        return [
            ['ё', 'Ё'],
            ['а', 'А'],
            ['я', 'Я'],
        ];
    }

    /**
     * @dataProvider dataProviderIsChar
     */
    public function testIsChar(int $code, bool $isChar)
    {
        $this->reverter = new RevertLinesForTests('');
        $result = $this->reverter->publicIsChar($code);
        $this->assertEquals($isChar, $result);
    }

    public function dataProviderIsChar(): array
    {
        return [
            [191, false],
            [192, true],
            [255, true],
            [256, false],
            [1, false],
            [99, false],
            [168, true],
            [184, true],
        ];
    }

    /**
     * @dataProvider dataProvideCharToLowerSetUpperPosition
     */
    public function testCharToLowerSetUpperPosition(int $code, int $position, int $lowerChar)
    {
        $this->reverter = new RevertLinesForTests('');
        $result = $this->reverter->publicCharToLowerSetUpperPosition($code, $position);
        $this->assertEquals($lowerChar, $result);
        if ($result == $code) {
            $this->assertEquals([], $this->reverter->getUpperPositions());
        } else {
            $this->assertEquals([$position], $this->reverter->getUpperPositions());
        }
    }

    public function dataProvideCharToLowerSetUpperPosition(): array
    {
        return [
            [168, 5, 184],
            [184, 1, 184],
            [255, 2, 255],
            [223, 3, 255],
        ];
    }
}
