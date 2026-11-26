<?php

class Route
{
    public string $route_regexp;
    public string $controller;

    public function __construct(string $route_regexp, string $controller)
    {
        $this->route_regexp = $route_regexp;
        $this->controller = $controller;
    }
}

class Router
{
    /** @var Route[] */
    protected array $routes = [];
    protected \Twig\Environment $twig;
    protected PDO $pdo;

    public function __construct(\Twig\Environment $twig, PDO $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function add(string $route_regexp, string $controller): void
    {
        $this->routes[] = new Route("#^$route_regexp$#", $controller);
    }

    protected function makeController(
        string $controller,
        array $params,
    ): BaseController {
        $controllerInstance = new $controller();
        $controllerInstance->setPDO($this->pdo);
        $controllerInstance->setParams($params);

        if ($controllerInstance instanceof TwigBaseController) {
            $controllerInstance->setTwig($this->twig);
        }

        return $controllerInstance;
    }

    public function get_or_default(string $default_controller): void
    {
        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?: "/";
        $matches = [];
        $controller = $default_controller;

        foreach ($this->routes as $route) {
            if (preg_match($route->route_regexp, $url, $matches)) {
                $controller = $route->controller;
                break;
            }
        }

        try {
            $this->makeController($controller, $matches)->process_response();
        } catch (NotFoundException $e) {
            $this->makeController($default_controller, [])->process_response();
        }
    }
}
