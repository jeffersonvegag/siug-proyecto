<?php
class HomeController extends Controller {
    public function index() {
        $data = ["titulo" => "Bienvenido al modulo de convenios"];
        $this->view("home", $data);
    }
}
