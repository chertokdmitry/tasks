<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.19
 * Time: 13:01
 */

namespace Views;

class View
{
    private $html;

    public function showHeader()
    {
        require_once __DIR__ . '/header.php';
    }

    public function showFooter()
    {
        require_once __DIR__ . '/footer.php';
    }

    public function showTasks($data, $totalPages, $admin = false)
    {
        $this->html = '
            <ul class="pagination" style="margin-top:40px;">';
        $link = ($admin) ? '/admin/' : '';

        for($i=1; $i<$totalPages+1; $i++) {

            $this->html .= '<li class="page-item"><a class="page-link" 
                    href="' . $link . 'page=' . $i . '">' . $i . '</a></li>';
        }

        $this->html .= '</ul>';

        $this->html .= '<div class="table-responsive" style="margin-top:40px;">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th><a href="/order=id">ID</a></th>
                    <th><a href="/order=username">Имя</a></th>
                    <th><a href="/order=email">Email</a></th>
                    <th>Задача</th>
                    <th><a href="/order=status">Статус</a></th>
                </tr>
                </thead>
                <tbody>';

        foreach ($data as $task) {

            $editButton = ($admin) ? '<br><a href="admin/edit=' . $task['id'] . '" type="button" class="btn btn-warning">edit</a>' : '';
            $status = ($task['status'] == 1) ? 'выполнена' : 'новая';
            $this->html .= "<tr>
                        <td>" . $task['id'] . "</td>
                        <td>" . $task['username'] . "</td>
                        <td>" . $task['email'] . "</td>
                        <td>" . $task['task'] . "</td>
                        <td>" . $status . $editButton . "</td>";
        }

        $this->html .= '</tbody>
            </table>
        </div>';

        $this->render();
    }

    public function showForm()
    {
        $this->html = '<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <h3 style="margin-top:50px;">
            <span>Добавить задачу</span>
        </h3>
        <div>
            <form method="POST" action="/form">
                <label for="name">Имя</label>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name">
                <br><br>
                <label for="email">Email</label>
                <input type="text"
                       class="form-control"
                       id="eamil"
                       name="email">
                <br><br>
                <label for="task">Задача</label>
                <textarea class="form-control" id="task" name="task" rows="3"></textarea>
                <br>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </form>
        </div>
    </main>';

        $this->render();
    }

    public function showAdminForm($task)
    {
        $this->html = '<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <h3 style="margin-top:50px;">
            <span>Редактировать задачу</span>
        </h3>
        <div>
            <form method="POST" action="/adminform">
            Имя: ' . $task['username'] . '<br><br>
            Email: ' . $task['email'] . '<br><br>
            Задача: ' . $task['task'] . '<br><br>
            
                <label for="task">Редактировать</label>
                <textarea class="form-control" 
                id="task" name="task" rows="3"">' . $task['task'] . '</textarea>
                <input type="hidden" name="id" value="' . $task['id'] . '">
                <input type="checkbox" id="status" name="status" value="1">
                <label for="status">Отметить как выполненное</label>
                <br>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </main>';

        $this->render();
    }

    public function showResult($data)
    {
        $this->showHeader();
        echo $data;
        $this->showFooter();
    }

    public function render()
    {
        $this->showHeader();
        echo $this->html;
        $this->showFooter();
    }
}