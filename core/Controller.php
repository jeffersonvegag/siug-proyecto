<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require "../app/views/layout/header.php";
        require "../app/views/{$view}.php";
        require "../app/views/layout/footer.php";
    }
}
