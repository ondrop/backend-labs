<?php

const DB_HOST = 'localhost';
const DB_NAME = 'backend_learning';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const DB_PORT = '3307';
const DB_DSN = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;

const OFFER_COUNTS = [10, 20, 30, 50, 70];
CONST SORT_DIRECTION = [
    "ASC" => "По возрастанию",
    "DESC" => "По убыванию"
];
CONST SORT = ['id_task', 'description', 'date_start', 'date_end', 'priorety'];
CONST ORDER_BYS = [
    "id_task_asc" => "id_task ASC",
    "id_task_desc" => "id_task DESC",
    "description_asc" => "description ASC",
    "description_desc" => "description DESC",
    "date_start_asc" => "date_start ASC",
    "date_start_desc" => "date_start DESC",
    "date_end_asc" => "date_end ASC",
    "date_end_desc" => "date_end DESC",
    "priorety_asc" => "priorety ASC",
    "priorety_desc" => "priorety DESC",
];