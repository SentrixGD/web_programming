<?php

class GameController extends BaseGameTwigController
{
    public $template = "game.twig";
    protected string $show = "";
    protected ?array $game = null;

    protected function loadGame(): void
    {
        $id = (int)($this->params["id"] ?? 0);

        $query = $this->pdo->prepare(
            "SELECT id, image, title, description, info FROM games WHERE id = :id"
        );
        $query->bindValue("id", $id, PDO::PARAM_INT);
        $query->execute();
        $this->game = $query->fetch() ?: null;
    }

    protected function loadShow(): void
    {
        $show = isset($_GET["show"]) && is_string($_GET["show"])
            ? $_GET["show"]
            : "";

        $this->show = in_array($show, ["image", "description"], true) ? $show : "";
    }

    protected function resolveTemplate(): void
    {
        $this->template = match ($this->show) {
            "image" => "game_image.twig",
            "description" => "game_description.twig",
            default => "game.twig",
        };
    }

    public function getContext(): array
    {
        $this->loadGame();

        if ($this->game === null) {
            throw new NotFoundException("Game not found");
        }

        $this->loadShow();
        $this->resolveTemplate();

        $context = parent::getContext();

        $context["game"] = $this->game;
        $context["title"] = $this->game["title"];

        $base = "/games/" . $this->game["id"];

        $context["links"] = [
            [
                "title" => $this->game["title"],
                "url" => $base,
                "active" => $this->show === "",
            ],
            [
                "title" => "Image",
                "url" => $base . "?show=image",
                "active" => $this->show === "image",
            ],
            [
                "title" => "Description",
                "url" => $base . "?show=description",
                "active" => $this->show === "description",
            ],
        ];

        return $context;
    }
}
