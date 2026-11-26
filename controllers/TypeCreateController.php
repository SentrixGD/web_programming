<?php

class TypeCreateController extends BaseGameTwigController
{
    public $template = "type_create.twig";
    public $title = "Add type";

    public function getContext(): array
    {
        $context = parent::getContext();
        $context["form"] = [
            "name" => "",
        ];

        return $context;
    }

    public function post(array $context): void
    {
        $name = trim((string)($_POST["name"] ?? ""));
        $context["form"] = [
            "name" => $name,
        ];

        if ($name === "") {
            $context["error"] = "Please enter a type name.";
            $this->get($context);
            return;
        }

        $nameQuery = $this->pdo->prepare("SELECT 1 FROM types WHERE name = :name");
        $nameQuery->bindValue("name", $name);
        $nameQuery->execute();

        if ($nameQuery->fetchColumn()) {
            $context["error"] = "A type with this name already exists.";
            $this->get($context);
            return;
        }

        try {
            $query = $this->pdo->prepare(
                "INSERT INTO types (name) VALUES (:name)"
            );
            $query->bindValue("name", $name);
            $query->execute();
        } catch (PDOException $e) {
            $context["error"] = $e->getCode() === "23505"
                ? "A type with this name already exists."
                : "Could not create the type.";
            $this->get($context);
            return;
        }

        $new_id = (int)$this->pdo->lastInsertId();
        $context = $this->getContext();
        $context["message"] = "Type created successfully.";
        $context["created_type_id"] = $new_id;

        $this->get($context);
    }
}
