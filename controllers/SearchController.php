<?php

class SearchController extends BaseGameTwigController
{
    public $template = "search.twig";
    public $title = "Search";

    public function getContext(): array
    {
        $context = parent::getContext();

        $type_id = $this->getSelectedTypeId();
        $title = trim($_GET["title"] ?? "");
        $description = isset($_GET["description"])
            ? trim($_GET["description"])
            : "";

        $sql = "SELECT g.id, g.image, g.title, g.description, t.name AS type
                FROM games g
                JOIN types t ON t.id = g.type_id
                WHERE 1 = 1";

        if ($type_id !== null) {
            $sql .= " AND g.type_id = :type_id";
        }
        if ($title !== "") {
            $sql .= " AND g.title ILIKE :title";
        }
        if ($description !== "") {
            $sql .= " AND g.description ILIKE :description";
        }

        $sql .= " ORDER BY g.title";

        $query = $this->pdo->prepare($sql);

        if ($type_id !== null) {
            $query->bindValue("type_id", $type_id, PDO::PARAM_INT);
        }
        if ($title !== "") {
            $query->bindValue("title", "%" . $title . "%");
        }
        if ($description !== "") {
            $query->bindValue("description", "%" . $description . "%");
        }

        $query->execute();

        $context["games"] = $query->fetchAll();
        $context["filters"] = [
            "title" => $title,
            "description" => $description,
        ];

        return $context;
    }
}
