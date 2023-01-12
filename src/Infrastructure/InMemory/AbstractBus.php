<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use GeekCell\Ddd\Contracts\Core\Interactable;
use GeekCell\Ddd\Support\Attributes\ForType;

abstract class AbstractBus
{
    /**
     * @var array<string, mixed>
     */
    protected array $handlers = [];

    /**
     * @return array<string, mixed>
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * Register a handler.
     *
     * The handler can be a callable or an object that implements
     * the Interactable interface and has the ForType class attribute.
     *
     * @param mixed $handler
     * @return void
     */
    abstract public function registerHandler(mixed $handler): void;

    /**
     * Register a callable handler.
     *
     * @param callable $callable     The callable to register
     * @param string $parameterType  The type of the parameter that the callable
     *
     * @return void
     */
    protected function registerCallableHandler(
        callable $callable,
        string $parameterType,
    ): void
    {
        $reflectionMethod = new \ReflectionMethod($callable, '__invoke');
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (
                is_subclass_of($type->getName(), $parameterType) &&
                $type->allowsNull() === false) {
                $this->handlers[$type->getName()] = $callable;
            }
        }
    }

    /**
     * Register a handler class.
     *
     * @param Interactable $handler  The handler to register
     * @param string $attributeType  Class attribute which is a
     *                               subclass of ForType
     * @return void
     */
    protected function registerHandlerClass(
        Interactable $handler,
        string $attributeType,
    ): void
    {
        $reflectionClass = new \ReflectionClass($handler);
        $attributes = $reflectionClass->getAttributes();
        foreach ($attributes as $attribute) {
            if (
                is_subclass_of($attributeType, ForType::class) &&
                $attribute->getName() !== $attributeType) {
                continue;
            }

            /** @var ForType $context */
            $context = $attribute->newInstance();
            if (!$context->isValid()) {
                return;
            }

            foreach ($reflectionClass->getMethods() as $method) {
                if ($method->getName() === $context->getHandler()) {
                    $this->handlers[$context->getType()] = [
                        $handler,
                        $method->getName()
                    ];
                }
            }
        }
    }
}
