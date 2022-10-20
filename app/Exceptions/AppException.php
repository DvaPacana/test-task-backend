<?php


namespace App\Exceptions;

abstract class AppException extends \RuntimeException
{
    public function getNs(): array
    {
        return ['App'];
    }

    public function getName(): string
    {
        return last(explode('\\', get_class($this)));
    }

    public function getFullName(): string
    {
        return join('/', $this->getNs()) . '::' . $this->getName();
    }

    public function getHttpCode(): int
    {
        return 500;
    }

    public function getUserData(): array
    {
        return [];
    }
}
