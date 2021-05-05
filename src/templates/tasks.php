<!DOCTYPE html>
<html lang='ru'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>ToDo</title>
    <link rel="stylesheet" href="../../public/css/app.css" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
<div class="body_wrapper">
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h4 class="card-title">Фильтры</h4>
                <a href="/add_task" class="btn btn-info d-block" style="margin-left: auto">Добавить задачу</a>
            </div>
            <form method="GET" action="/" class="search_form">
                <label>Task id</label>
                <input class="form-control form-control-sm" type="text" name="idTask" value="<?= isset($_GET['idTask']) ? $_GET['idTask'] : '' ?>">
                <label>Description</label>
                <input class="form-control form-control-sm" type="text" name="description" value="<?= isset($_GET['description']) ? $_GET['description'] : '' ?>">
                <label>Date start</label>
                <input class="form-control form-control-sm" type="date" name="dateStart" value="<?= isset($_GET['dateStart']) ? $_GET['dateStart'] : '' ?>">
                <label>Date end</label>
                <input class="form-control form-control-sm" type="date" name="dateEnd" value="<?= isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '' ?>">
                <label>Priorety</label>
                <select class="form-control form-control-sm" name="priorety">
                    <?php if (isset($_GET['priorety']) && !empty($_GET['priorety'])): ?>
                        <option value="">Выберите</option>
                    <?php else: ?>
                        <option selected value="">Выберите</option>
                    <?php endif; ?>

                    <?php foreach (PAGE_DATA['priorities'] as $priority): ?>
                        <?php if (isset($_GET['priorety']) && !empty($_GET['priorety']) && $_GET['priorety'] == $priority[0]): ?>
                            <option value="<?= $priority['id'] ?>" selected><?= $priority['value'] ?></option>
                        <?php else: ?>
                            <option value="<?= $priority['id'] ?>"><?= $priority['value'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <label>OfferCount</label>
                <select class="form-control form-control-sm" name="offerCount">
                    <?php foreach (OFFER_COUNTS as $offerCount): ?>
                        <?php if (isset($_GET['offerCount']) && is_numeric($_GET['offerCount']) && $_GET['offerCount'] == $offerCount): ?>
                            <option value="<?=$offerCount ?>" selected><?=$offerCount ?></option>
                        <?php else: ?>
                            <option value="<?=$offerCount ?>"><?=$offerCount ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <label>Сортировка</label>
                <div class="d-flex">
                    <select class="form-control form-control-sm" name="sort">
                        <?php foreach (SORT as $value): ?>
                            <?php if (isset($_GET['sort']) && !empty($_GET['sort']) && $_GET['sort'] == $value): ?>
                                <option value="<?= $value ?>" selected>
                                    <?= $value ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-control form-control-sm" name="sortDirection">
                        <?php foreach (SORT_DIRECTION as $key => $value): ?>
                            <?php if (isset($_GET['sortDirection']) && !empty($_GET['sortDirection']) && $_GET['sortDirection'] == $key): ?>
                                <option value="<?= $key ?>" selected><?= $value ?></option>
                            <?php else: ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input class="btn btn-primary" type="submit" value="Поиск">
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action='/'>
                <?php if (isset(PAGE_DATA['todoData']) && !empty(PAGE_DATA['todoData'])): ?>
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>Choose</th>
                            <?php foreach (PAGE_DATA['todoData'][0] as $key => $value): ?>
                                <th>
                                    <?= $key ?>
                                </th>
                            <?php endforeach; ?>
                            <th>
                                action
                            </th>
                        </tr>
                        </thead>
                        <?php foreach (PAGE_DATA['todoData'] as $todo): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="delete_ids[]" value="<?= $todo['id_task'] ?>">
                                </td>
                                <?php foreach ($todo as $value): ?>
                                    <td>
                                        <?= $value ?>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <a class="btn btn-info" target="_blank" href="/edit_task?id=<?= $todo['id_task'] ?>">Edit</a>
                                    <form action="/?<?= PAGE_DATA['query'] ?>1" method="POST" class="d-inline">
                                        <input type="hidden" name="delete" value="true">
                                        <input type="hidden" name="idTask" value="<?= $todo['id_task'] ?>">
                                        <input class="btn btn-danger" type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div>
                        Данные не найдены
                    </div>
                <?php endif; ?>

                <input class="btn btn-danger" type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this items?');">
            </form>

            <div>
                <?php if (PAGE_DATA['showPagination']): ?>
                    <ul class="pagination">
                        <?php if (PAGE_DATA['firstPage'] > 1): ?>
                            <li class="page-item"><a class="page-link" href="/?<?= PAGE_DATA['query'] ?><?= PAGE_DATA['firstPage'] - 1; ?>"> < </a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= PAGE_DATA['lastPage']; $i++): ?>
                            <?php if ($i != PAGE_DATA['firstPage']): ?>
                                <li class="page-item"><a class="page-link" href="/?<?= PAGE_DATA['query'] ?><?= $i; ?>"><?= $i; ?></a></li>
                            <?php else: ?>
                                <li class="page-item"><span class="page-link selected"><?= $i; ?></span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if (PAGE_DATA['firstPage'] < PAGE_DATA['lastPage']): ?>
                            <li class="page-item"><a class="page-link" href="/?<?= PAGE_DATA['query'] ?><?= PAGE_DATA['firstPage'] + 1; ?>"> > </a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</body>

</html>