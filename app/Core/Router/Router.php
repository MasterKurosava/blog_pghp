<?php

declare(strict_types=1);

namespace App\Core\Router;

use App\Controllers\Controller;
use App\Core\Container;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Exceptions\Http\NotFoundException;
use ReflectionMethod;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Container $container,
    ) {
    }

    public function get(string $pattern, array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    private function addRoute(string $method, string $pattern, array $handler): void
    {
        [$regex, $parameterNames] = $this->compilePattern($pattern);

        $this->routes[] = new Route(
            method: $method,
            pattern: $pattern,
            handler: $handler,
            regex: $regex,
            parameterNames: $parameterNames,
        );
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route->method !== $request->method()) {
                continue;
            }

            $matches = [];

            if (!preg_match($route->regex, $request->uri(), $matches)) {
                continue;
            }

            $parameters = $this->extractParameters($route, $matches);

            return $this->invokeHandler($route->handler, $parameters);
        }

        throw new NotFoundException();
    }

    private function compilePattern(string $pattern): array
    {
        $parameterNames = [];
        $regex = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static function (array $matches) use (&$parameterNames): string {
                $parameterNames[] = $matches[1];

                return '([^/]+)';
            },
            $pattern,
        );

        return ['#^' . $regex . '$#', $parameterNames];
    }

    private function extractParameters(Route $route, array $matches): array
    {
        $parameters = [];

        foreach ($route->parameterNames as $index => $name) {
            $parameters[$name] = $matches[$index + 1] ?? '';
        }

        return $parameters;
    }

    private function invokeHandler(array $handler, array $parameters): Response
    {
        [$controllerClass, $action] = $handler;

        $controller = $this->container->make($controllerClass);

        if (!$controller instanceof Controller) {
            throw new RuntimeException("Controller [{$controllerClass}] must extend " . Controller::class);
        }

        if (!method_exists($controller, $action)) {
            throw new RuntimeException("Action [{$action}] not found in [{$controllerClass}].");
        }

        $method = new ReflectionMethod($controller, $action);
        $arguments = [];

        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (array_key_exists($name, $parameters)) {
                $arguments[] = $parameters[$name];
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            throw new RuntimeException("Missing route parameter [{$name}] for [{$controllerClass}::{$action}].");
        }

        return $method->invokeArgs($controller, $arguments);
    }
}
