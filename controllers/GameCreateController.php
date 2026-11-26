<?php

class GameCreateController extends BaseGameTwigController
{
    public $template = "game_create.twig";
    public $title = "Add game";

    public function getContext(): array
    {
        $context = parent::getContext();
        $context["form"] = [
            "title" => "",
            "description" => "",
            "type_id" => "",
            "info" => "",
        ];

        return $context;
    }

    public function post(array $context): void
    {
        $title = trim((string)($_POST["title"] ?? ""));
        $description = trim((string)($_POST["description"] ?? ""));
        $info = trim((string)($_POST["info"] ?? ""));
        $type_id = filter_var($_POST["type_id"] ?? null, FILTER_VALIDATE_INT);

        $context["form"] = [
            "title" => $title,
            "description" => $description,
            "type_id" => $type_id === false ? "" : $type_id,
            "info" => $info,
        ];

        if ($title === "" || $description === "" || $type_id === false) {
            $context["error"] = "Please fill in the title, description, and type.";
            $this->get($context);
            return;
        }

        $typeQuery = $this->pdo->prepare("SELECT 1 FROM types WHERE id = :id");
        $typeQuery->bindValue("id", $type_id, PDO::PARAM_INT);
        $typeQuery->execute();

        if (!$typeQuery->fetchColumn()) {
            $context["error"] = "Please choose an existing type.";
            $this->get($context);
            return;
        }

        [$image, $imageError] = $this->saveUploadedImage("image", "games");

        if ($image === null) {
            $context["error"] = $imageError;
            $this->get($context);
            return;
        }

        $query = $this->pdo->prepare(
            "INSERT INTO games (image, type_id, title, description, info)
             VALUES (:image, :type_id, :title, :description, :info)"
        );
        $query->bindValue("image", $image);
        $query->bindValue("type_id", $type_id, PDO::PARAM_INT);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("info", $info);
        $query->execute();

        $new_id = (int)$this->pdo->lastInsertId();
        $context = $this->getContext();
        $context["message"] = "Game created successfully.";
        $context["created_game_id"] = $new_id;

        $this->get($context);
    }
}
