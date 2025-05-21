<?php

namespace Tools\CommandBus;

class CommandHandlerResult implements CommandHandlerResultInterface
{
    private bool $result;
    private string $resultMessage;
    private int $errCode;
    private string $errMessage;
    private array $data;
    private string $html;

    public function __construct(bool $result = true, int $errCode = 0, string $errMessage = '')
    {
        $this->result = $result;
        $this->errCode = $errCode;
        $this->errMessage = $errMessage;
        $this->resultMessage = '';
        $this->data = [];
        $this->html = '';
    }

    public function changeResult(bool $result, int $errCode, string $errMessage): CommandHandlerResult
    {
        $this->result = $result;
        $this->errCode = $errCode;
        $this->errMessage = $errMessage;
        return $this;
    }

    public function setResult(bool $result): CommandHandlerResult
    {
        $this->result = $result;
        return $this;
    }

    public function setErrCode(int $errCode): CommandHandlerResult
    {
        $this->errCode = $errCode;
        return $this;
    }

    public function setErrMessage(string $errMessage): CommandHandlerResult
    {
        $this->errMessage = $errMessage;
        return $this;
    }

    public function setResultMessage(string $resultMessage): CommandHandlerResult
    {
        $this->resultMessage = $resultMessage;
        return $this;
    }

    public function setHtml(string $html): CommandHandlerResult
    {
        $this->html = $html;
        return $this;
    }

    public function addDataArray(array $dataArray): CommandHandlerResult
    {
        $this->data = array_merge($this->data, $dataArray);
        return $this;
    }

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getErrCode(): int
    {
        return $this->errCode;
    }

    public function getErrMessage(): string
    {
        return $this->errMessage;
    }

    public function getResultMessage(): string
    {
        return $this->resultMessage;
    }
    public function getHtml(): string
    {
        return $this->html;
    }

    public function getDataArray(): array
    {
        return $this->data;
    }

    public function getAsArray(): array
    {
        return [
            'result' => $this->result,
            'resultMessage' => $this->resultMessage,
            'errCode' => $this->errCode,
            'errMessage' => $this->errMessage,
            'data' => $this->data,
        ];
    }

    public function jsonGet(): false|string
    {
        return json_encode($this->getAsArray());
    }
}
