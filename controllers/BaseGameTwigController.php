<?php

class BaseGameTwigController extends TwigBaseController
{
    protected function getSelectedTypeId(): ?int
    {
        if (!isset($_GET["type"]) || !is_string($_GET["type"]) || !ctype_digit($_GET["type"])) {
            return null;
        }

        return (int)$_GET["type"];
    }

    protected function saveUploadedImage(string $field, string $folder): array
    {
        if (
            !isset($_FILES[$field])
            || !is_array($_FILES[$field])
            || $_FILES[$field]["error"] !== UPLOAD_ERR_OK
        ) {
            return [null, "Please choose an image file."];
        }

        $file = $_FILES[$field];

        if (($file["size"] ?? 0) > 5 * 1024 * 1024) {
            return [null, "Image must be smaller than 5 MB."];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file["tmp_name"]);
        $extensions = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/gif" => "gif",
            "image/webp" => "webp",
        ];

        if (!isset($extensions[$mime])) {
            return [null, "Only JPEG, PNG, GIF, and WebP images are allowed."];
        }

        $directory = dirname(__DIR__) . "/public/media/" . $folder;

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            return [null, "Could not create the upload directory."];
        }

        $filename = bin2hex(random_bytes(16)) . "." . $extensions[$mime];
        $destination = $directory . "/" . $filename;

        if (!move_uploaded_file($file["tmp_name"], $destination)) {
            return [null, "Could not save the uploaded image."];
        }

        return ["/media/" . $folder . "/" . $filename, null];
    }

    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->query(
            "SELECT id, name, image FROM types ORDER BY name",
        );

        $current_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?: "/";
        $type_id = $this->getSelectedTypeId();
        $is_search = $current_path === "/search";

        $context["types"] = $query->fetchAll();
        $context["current_path"] = $current_path;
        $context["selected_type_id"] = $is_search ? null : $type_id;
        $context["selected_search_type_id"] = $is_search ? $type_id : null;

        return $context;
    }
}
