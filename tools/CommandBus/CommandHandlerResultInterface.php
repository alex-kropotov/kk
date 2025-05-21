<?php

namespace Tools\CommandBus;

interface CommandHandlerResultInterface
{
    public function __construct(bool $result = true, int $errCode = 0, string $errMessage = '');


    public function changeResult(bool $result, int $errCode, string $errMessage): CommandHandlerResultInterface;


    public function setResult(bool $result): CommandHandlerResultInterface;

    public function setErrCode(int $errCode): CommandHandlerResultInterface;

    public function setErrMessage(string $errMessage): CommandHandlerResultInterface;

    public function setResultMessage(string $resultMessage): CommandHandlerResultInterface;

    public function setHtml(string $html): CommandHandlerResultInterface;

    public function addDataArray(array $dataArray): CommandHandlerResultInterface;

    public function getResult(): bool;

    public function getErrCode(): int;

    public function getErrMessage(): string;

    public function getResultMessage(): string;

    public function getHtml(): string;

    public function getDataArray(): array;

    public function getAsArray(): array;

    public function jsonGet(): false|string;

}
