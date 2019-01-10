<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.19
 * Time: 13:14
 */

namespace Models;

use PDO;

class DB

{
    protected $model;

    public function __construct()
    {
        $config = new Config;
        $dbInfo = $config->index();
        $this->model = new PDO ($dbInfo[0], $dbInfo[1], $dbInfo[2]);
    }

    public function pagination()
    {
        $totalPagesSql = "SELECT COUNT(*) FROM tasks";
        $result = $this->model->query($totalPagesSql);
        $total_rows = $result->fetch();

        return $total_rows[0];
    }

    public function selectOne($id)
    {
        try {

            $this->model->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "SELECT * FROM tasks WHERE id = :id";
            $task = $this->model->prepare($query);
            $task->execute(['id'=>$id]);
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }

        return $task->fetch();
    }

    public function select($query)
    {
        $data = $this->model->query($query);
        $fetchData = [];

        while($item = $data->fetch())
        {
            $fetchData[] = $item;
        }

        return $fetchData;
    }

    public function insert($username, $email, $task)
    {
        try {
            $status = 0;
            $this->model->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "INSERT into tasks (username, email, task, status) 
              VALUES (:username, :email, :task, :status)";
            $stmt = $this->model->prepare($query);

            $stmt->bindParam(":username",$username);
            $stmt->bindParam(":email",$email);
            $stmt->bindParam(":task",$task);
            $stmt->bindParam(":status",$status);
            $stmt->execute();

            return "Новая запись добавлена";
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function update($task, $status, $id)
    {
        $sql = "UPDATE tasks SET task=?, status=? WHERE id=?";
        $stmt= $this->model->prepare($sql);
        $stmt->execute([$task, $status, $id]);

        return "Данные обновлены";
    }
}