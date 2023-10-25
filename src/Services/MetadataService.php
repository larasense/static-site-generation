<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Services;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use Larasense\StaticSiteGeneration\Attributes\SSG;
use Larasense\StaticSiteGeneration\DTOs\Page;
use ReflectionClass;
use ReflectionAttribute;

class MetadataService
{
    public function get(Route|string|null $route): Page | false
    {
        if (!$route instanceof Route) {
            return false;
        }

        $attributes = $this->getAttributes($route);

        if (count($attributes) == 0) {
            return false;
        }

        [$controller, $name] = explode('@', $route->action['controller']);
        return (new Page(
            uri:        $route->uri,
            controller: $controller,
            method:     $name,
        ))->setAttribute($attributes[0]);
    }

    /**
     * Get all routes defined metadata. this is use mainly
     * in the middleware and in the commands to know if
     * the route needs to be stored in files or not.
     *
     * @return Collection<int,Page>
     */
    public function all(): Collection
    {
        /** @phpstan-ignore-next-line */
        return $this->routes()
            ->map(fn (Route $route) => $this->get($route))
        ;
    }

    /**
     * List of routes with the SSG attribute defined
     * this is public because we need to register
     * the middleware to this routes on boot.
     *
     * @return Collection<int,Route>
     */
    public function routes(): Collection
    {
        if(!count(RouteFacade::getRoutes()->getRoutesByMethod())) {
            return collect([]);
        }
        /** @phpstan-ignore-next-line */
        return collect(RouteFacade::getRoutes()->getRoutesByMethod()['GET'])
            ->filter(fn (Route $route) => $this->hasAttributes($route))
        ;
    }

    protected function hasAttributes(Route $route): bool
    {
        if (!isset($route->action['controller'])) {
            return false;
        }

        if (!$route->action['controller']) {
            return false;
        }
        if (!Str::contains($route->action['controller'], '@')) {
            return false;
        }

        $attributes = $this->getAttributes($route);

        if (count($attributes) == 0) {
            return false;
        }

        return true;
    }

    /**
     *
     * @return array<int,ReflectionAttribute<SSG>>
     */
    protected function getAttributes(Route $route): array
    {
        [$controller, $name] = explode('@', $route->action['controller']);
        /** @phpstan-ignore-next-line */
        return (new ReflectionClass($controller))
                            ->getMethod($name)
                            ->getAttributes(SSG::class);

    }
}
