<?php

class Controller404 extends BaseGameTwigController {
    public $template = "404.twig";
    public $title = "Page not found";

    public function get(array $context): void
    {
        http_response_code(404);
        parent::get($context);
    }

    public function post(array $context): void
    {
        $this->get($context);
    }
}
