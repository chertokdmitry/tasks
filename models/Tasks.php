<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.19
 * Time: 13:38
 */

namespace Models;


class Tasks
{
    public function showTasks()
    {
        $order = (isset($_COOKIE["Order"])) ? $_COOKIE["Order"] : 'id';
        $connection = new DB;
        $paginationInfo = $this->makePagination($connection);
        $totalPages = $paginationInfo[2];
        $query = "SELECT * from tasks ORDER BY $order LIMIT $paginationInfo[0], $paginationInfo[1]";
        $tasks = $connection->select($query);

        return [$tasks, $totalPages];
    }

    public function getTask($id)
    {
        $connection = new DB;
        $task = $connection->selectOne($id);

        return $task;
    }

    public function makePagination($connection)
    {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $lastPart = array_pop($url);
        $urlPage = explode('=', trim($lastPart));

        if (isset($urlPage[1])) {
            $page = $urlPage[1];
        } else {
            $page = 1;
        }

        $recordsPerPage = 3;
        $offset = ($page-1) * $recordsPerPage;
        $totalRecords = $connection->pagination();
        $totalPages = ceil($totalRecords / $recordsPerPage);

        return [$offset, $recordsPerPage, $totalPages];
    }

    public function newTask()
    {
        $username = $_POST['name'];
        $email = $_POST['email'];
        $task = $_POST['task'];
        $connection = new DB;
        $result = $connection->insert($username, $email, $task);

        return $result;
    }

    public function updateTask()
    {
        $task = $_POST['task'];
        $status = (isset($_POST['status']))? 1 : 0;
        $id = $_POST['id'];
        $connection = new DB;
        $result = $connection->update($task, $status, $id);

        return $result;
    }
}