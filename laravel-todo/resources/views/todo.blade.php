<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel To-Do App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        ul#task-list {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        ul#task-list li {
            padding: 10px;
            margin-bottom: 8px;
            background-color: #f9f9f9;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        ul#task-list li span.completed {
            text-decoration: line-through;
            color: #888;
        }

        ul#task-list li input[type="checkbox"] {
            margin-right: 10px;
        }

        .delete-task {
            background-color: #e74c3c;
        }

        .delete-task:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laravel To-Do App</h2>

        <input type="text" id="task-input" placeholder="Enter a task">
        <button id="add-task">Add Task</button>
        <button id="show-all">Show All Tasks</button>

        <ul id="task-list"></ul>
    </div>

    <script>
        function loadTasks() {
            $.get('/tasks/all', function(tasks) {
                $('#task-list').empty();
                tasks.forEach(task => {
                    const html = `
                        <li data-id="${task.id}">
                            <input type="checkbox" class="complete-task" ${task.completed ? 'checked' : ''}>
                            <span class="${task.completed ? 'completed' : ''}">
                                ${task.title}
                            </span>
                            <button class="delete-task">Delete</button>
                        </li>
                    `;
                    $('#task-list').append(html);
                });
            });
        }

        $(document).ready(function() {
            loadTasks();

            $('#add-task').click(function() {
                const title = $('#task-input').val().trim();
                if (title === '') return;

                $.post('/tasks', {
                    title,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }).done(task => {
                    $('#task-input').val('');
                    loadTasks();
                }).fail(err => {
                    alert(err.responseJSON.message || "Duplicate or invalid task.");
                });
            });

            $('#task-input').keypress(function(e) {
                if (e.which === 13) {
                    const title = $('#task-input').val().trim();
                    if (title === '') return;

                    $.post('/tasks', {
                        title,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }).done(task => {
                        $('#task-input').val('');
                        loadTasks();
                    }).fail(err => {
                        alert(err.responseJSON.message || "Duplicate or invalid task.");
                    });
                }
            });

            $('#task-list').on('change', '.complete-task', function() {
                const taskItem = $(this).closest('li');
                const id = taskItem.data('id');

                $.post(`/tasks/${id}/complete`, {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }).done(function(response) {
                    taskItem.fadeOut(400, function() {
                        taskItem.remove();
                    });
                });
            });

            $('#task-list').on('click', '.delete-task', function() {
                if (!confirm("Are you sure to delete this task?")) return;
                const id = $(this).closest('li').data('id');

                $.ajax({
                    url: `/tasks/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: loadTasks
                });
            });

            $('#show-all').click(function() {
                loadTasks();
            });
        });
    </script>
</body>
</html>
