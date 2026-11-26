<?php

class GameUpdateController extends BaseGameTwigController
{
    public $template = "game_edit.twig";
    public $title = "Edit game";

    public function getContext(): array
    {
        $context = parent::getContext();

        $id = (int)($this->params["id"] ?? 0);
        $query = $this->pdo->prepare(
            "SELECT id, image, title, description, info, type_id FROM games WHERE id = :id"
        );
        $query->bindValue("id", $id, PDO::PARAM_INT);
        $query->execute();
        $game = $query->fetch() ?: null;

        if ($game === null) {
            throw new NotFoundException("Game not found");
        }

        $context["game"] = $game;
        $context["form"] = [
            "title" => $game["title"] ?? "",
            "description" => $game["description"] ?? "",
            "type_id" => $game["type_id"] ?? "",
            "info" => $game["info"] ?? "",
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

        $image = $context["game"]["image"];
        $hasUpload = isset($_FILES["image"])
            && is_array($_FILES["image"])
            && ($_FILES["image"]["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        if ($hasUpload) {
            [$image, $imageError] = $this->saveUploadedImage("image", "games");

            if ($image === null) {
                $context["error"] = $imageError;
                $this->get($context);
                return;
            }
        }

        $query = $this->pdo->prepare(
            "UPDATE games
             SET image = :image, type_id = :type_id, title = :title,
                 description = :description, info = :info
             WHERE id = :id"
        );
        $query->bindValue("id", (int)$context["game"]["id"], PDO::PARAM_INT);
        $query->bindValue("image", $image);
        $query->bindValue("type_id", $type_id, PDO::PARAM_INT);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("info", $info);
        $query->execute();

        $context = $this->getContext();
        $context["message"] = "Game updated successfully.";
        $this->get($context);
    }
}
