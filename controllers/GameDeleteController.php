<?php

class GameDeleteController extends BaseController
{
    public function get(array $context): void
    {
        header("Location: /");
        exit;
    }

    public function post(array $context): void
    {
        $id = (int)($this->params["id"] ?? 0);

        if ($id <= 0) {
            header("Location: /");
            exit;
        }

        $query = $this->pdo->prepare("DELETE FROM games WHERE id = :id");
        $query->bindValue("id", $id, PDO::PARAM_INT);
        $query->execute();

        header("Location: /");
        exit;
    }
}
