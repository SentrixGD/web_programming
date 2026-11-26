<?php

class MainController extends BaseGameTwigController {
    public $template = "main.twig";
    public $title = "Home";

    public function getContext(): array {
        $context = parent::getContext();

        $type_id = $this->getSelectedTypeId();

        if ($type_id !== null) {
            $query = $this->pdo->prepare(
                "SELECT id, image, title, description FROM games WHERE type_id = :type_id ORDER BY title"
            );
            $query->bindValue("type_id", $type_id, PDO::PARAM_INT);
            $query->execute();
        } else {
            $query = $this->pdo->query(
                "SELECT id, image, title, description FROM games ORDER BY title"
            );
        }

        $context["games"] = $query->fetchAll();

        return $context;
    }
}
