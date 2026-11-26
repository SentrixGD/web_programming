<?php

class TwigBaseController extends BaseController
{
    public $title = ""; // page title
    public $template = ""; // page template
    protected \Twig\Environment $twig; // reference to the twig instance for rendering

    public function setTwig(\Twig\Environment $twig): void
    {
        $this->twig = $twig;
    }

    // we override the context function
    public function getContext(): array
    {
        $context = parent::getContext(); // we call the parent method
        $context["title"] = $this->title; // we add title to the context

        return $context;
    }

    public function get(array $context): void
    {
        echo $this->twig->render($this->template, $context);
    }
}
