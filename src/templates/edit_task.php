<!DOCTYPE html>
<html lang='ru'>

    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>Edit Task</title>
        <link rel="stylesheet" href="../../public/css/app.css" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

    <body class="body_wrapper">
        <div class="card" style="width: 36rem;">
            <?php if (isset(PAGE_DATA['success']) && PAGE_DATA['success']): ?>
                <div class="alert alert-success" role="alert">
                    Данные успешно обновлены
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
                    <h4 class="card-title">Edit Task</h4>
                    <a href="/" class="btn btn-info d-block" style="margin-left: auto">На главную</a>
                </div>
                <form method="POST" action="/edit_task?id=<?= $taskData['idTask'] ?? null ?>" class="search_form">
                    <input type="hidden" name="idTask" value="<?= $taskData['idTask'] ?? null ?>">
                    <label>Description</label>
                    <input class="form-control form-control-sm" type="text" name="description" value="<?= $taskData['description'] ?? '' ?>">
                    <label>Date start</label>
                    <input class="form-control form-control-sm" type="datetime-local" name="dateStart" value="<?= $taskData['dateStart'] ?? null ?>">
                    <label>Date end</label>
                    <input class="form-control form-control-sm" type="datetime-local" name="dateEnd" value="<?= $taskData['dateEnd'] ?? null ?>">
                    <label>Priorety</label>
                    <select class="form-control form-control-sm" name="priorety">
                        <?php foreach (PAGE_DATA['priorities'] as $priority): ?>
                            <?php if (isset($taskData['priorety']) && $taskData['priorety'] == $priority['value']): ?>
                                <option value="<?= $priority['id'] ?>" selected><?= $priority['value'] ?></option>
                            <?php else: ?>
                                <option value="<?= $priority['id'] ?>"><?= $priority['value'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <input class="btn btn-primary" type="submit" value="Сохранить">
                </form>
            </div>
        </div>
    </body>

</html>
