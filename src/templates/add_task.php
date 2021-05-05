<!DOCTYPE html>
<html lang='ru'>

    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>Add Task</title>
        <link rel="stylesheet" href="../../public/css/app.css" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

    <body class="body_wrapper">
        <div class="card" style="width: 36rem;">
            <?php if (isset(PAGE_DATA['success']) && PAGE_DATA['success']): ?>
                <div class="alert alert-success" role="alert">
                    Задача успешно добавлена
                </div>
            <?php endif; ?>
            <?php if (isset(PAGE_DATA['errors']) && !empty(PAGE_DATA['errors'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach (PAGE_DATA['errors'] as $error): ?>
                        <?= $error ?>
                        <br/>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <div class="d-flex">
                    <h4 class="card-title">Add Task</h4>
                    <a href="/" class="btn btn-info d-block" style="margin-left: auto">На главную</a>
                </div>
                <form method="POST" action="/add_task" class="search_form">
                    <label>Description</label>
                    <input class="form-control form-control-sm" type="text" name="description" value="<?= $_POST['description'] ?? '' ?>">
                    <label>Date start</label>
                    <input class="form-control form-control-sm" type="datetime-local" name="dateStart" value="<?= $_POST['dateStart'] ?? null ?>">
                    <label>Date end</label>
                    <input class="form-control form-control-sm" type="datetime-local" name="dateEnd" value="<?= $_POST['dateEnd'] ?? null ?>">
                    <label>Priorety</label>
                    <select class="form-control form-control-sm" name="priorety">
                        <?php foreach (PAGE_DATA['priorities'] as $priority): ?>
                            <?php if (isset($_POST['priorety']) && $_POST['priorety'] == $priority['id']): ?>
                                <option value="<?= $priority['id'] ?>" selected><?= $priority['value'] ?></option>
                            <?php else: ?>
                                <option value="<?= $priority['id'] ?>"><?= $priority['value'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <div class="d-flex">
                        <input class="btn btn-primary" type="submit" value="Добавить">
                        <a href="../../index.php" class="btn btn-info d-block" style="margin-left: auto">Очистить</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

</html>
