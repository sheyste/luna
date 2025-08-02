<?php
/**
 * Controller for Home page
 */
require BASE_PATH . '/core/Controller.php';

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }

    public function index()
    {
        $this->view('home');
    }
}
