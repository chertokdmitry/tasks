<?php

namespace Controllers;

use Views\View;
use Models\Tasks;

class IndexController
{
    public function index()
    {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $lastPart = array_pop($url);
        $urlPage = explode('=', trim($lastPart));
        if (isset($urlPage[0]) && $urlPage[0]=='page') {
            if(isset($url[0])){
                if($url[0] == 'admin') {
                    $this->requireAuth();
                } else {
                    $this->getTasks();
                }
            } else {
                $this->getTasks();
            }
        }

        if (isset($urlPage[0]) && $urlPage[0]=='order') {

            $this->setCookie($urlPage[1]);
            $this->getTasks();
        }

        if (isset($urlPage[0]) && $urlPage[0]=='edit') {

            $tasks = new Tasks();
            $data = $tasks->getTask($urlPage[1]);
            $this->getAdminForm($data);
        }

        if($_SERVER['REQUEST_URI'] == '/') $this->getTasks();
        if($_SERVER['REQUEST_URI'] == '/create') $this->getForm();
        if($_SERVER['REQUEST_URI'] == '/form') $this->saveData();
        if($_SERVER['REQUEST_URI'] == '/adminform') $this->saveAdminData();
        if($_SERVER['REQUEST_URI'] == '/admin') $this->requireAuth();
    }

    public function setCookie($order)
    {
        setcookie("Order", $order);
        header('Location: /');
    }

    public function getTasks()
    {
        $tasks = new Tasks();
        $data = $tasks->showTasks();

        $view = new View();
        $view->showTasks($data[0], $data[1]);
    }

    public function getForm()
    {
        $view = new View();
        $view->showForm();
    }

    public function getAdminForm($data)
    {
        $view = new View();
        $view->showAdminForm($data);
    }

    public function saveData()
    {
        $tasks = new Tasks();
        $data = $tasks->newTask();

        $view = new View();
        $view->showResult($data);
    }

    public function saveAdminData()
    {
        $tasks = new Tasks();
        $data = $tasks->updateTask();

        $view = new View();
        $view->showResult($data);
    }

    function requireAuth() {
        $AUTH_USER = 'admin';
        $AUTH_PASS = '123';
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }

        $tasks = new Tasks();
        $data = $tasks->showTasks();

        $view = new View();
        $view->showTasks($data[0], $data[1], true);
    }
}