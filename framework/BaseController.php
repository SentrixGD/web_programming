<?php
// abstract class so that no instance can be created
abstract class BaseController
{
    public PDO $pdo; // database connection shared by all controllers
    public array $params = []; // parameters captured from the URL

    public function setPDO(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    // since everything revolves around data, let's create a function
    // that will return the context with data
    public function getContext(): array
    {
        return []; // empty context by default
    }

    public function process_response(): void
    {
        $context = $this->getContext();
        $method = $_SERVER["REQUEST_METHOD"] ?? "GET";

        if ($method === "POST") {
            $this->post($context);
            return;
        }

        $this->get($context);
    }

    public function get(array $context): void {}

    public function post(array $context): void {}
}
