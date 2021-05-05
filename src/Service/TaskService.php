<?php

namespace src\Service;

class TaskService
{
    public function getDataFromQuery()
    {
        $conditions = [];
        $parameters = [];
        $prevQueries = [];
        $orderBy = '';

        if (isset($_GET['idTask']) && !empty($_GET['idTask'])) {
            $conditions[] = "t.id_task = :idTask";
            $parameters['idTask'] = $_GET['idTask'];
            $prevQueries[] = "idTask=" . $_GET['idTask'];
        }

        if (isset($_GET['description']) && !empty($_GET['description'])) {
            $conditions[] = "t.description LIKE :description";
            $parameters['description'] = "%" . $_GET['description'] . "%";
            $prevQueries[] = "description=" . $_GET['description'];
        }

        if (isset($_GET['dateStart']) && !empty($_GET['dateStart'])) {
            $conditions[] = "CAST(t.date_start AS DATE) >= :dateStart AND CAST(t.date_end AS DATE) >= :dateStart";
            $parameters['dateStart'] = $_GET['dateStart'];
            $prevQueries[] = "dateStart=" . $_GET['dateStart'];
        }

        if (isset($_GET['dateEnd']) && !empty($_GET['dateEnd'])) {
            $conditions[] = "CAST(t.date_end AS DATE) <= :dateEnd AND CAST(t.date_start AS DATE) <= :dateEnd";
            $parameters['dateEnd'] = $_GET['dateEnd'];
            $prevQueries[] = "dateEnd=" . $_GET['dateEnd'];
        }

        if (isset($_GET['priorety']) && !empty($_GET['priorety'])) {
            $conditions[] = "p.value = :priorety";
            $parameters['priorety'] = $_GET['priorety'];
            $prevQueries[] = "priorety=" . $_GET['priorety'];
        }

        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $orderBy .= $_GET['sort'] . " ";
            $prevQueries[] = "sort=" . $_GET['sort'];
        }

        if (isset($_GET['sortDirection']) && !empty($_GET['sortDirection'])) {
            $orderBy .= $_GET['sortDirection'];
            $prevQueries[] = "sortDirection=" . $_GET['sortDirection'];
        }

        if (isset($_GET['offerCount']) && is_numeric($_GET['offerCount']) && in_array($_GET['offerCount'], OFFER_COUNTS) && $_GET['offerCount'] != 10) {
            $limit = $_GET['offerCount'];
            $prevQueries[] = "offerCount=" . $_GET['offerCount'];
        }

        return [$conditions, $parameters, $prevQueries, $orderBy];
    }
}
