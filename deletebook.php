<?php

$id = '';

if(isset($_GET['id'])){
    $id = $_GET['id'];
}

require_once 'book.class.php';

$obj = new book();

if ($obj->delete($id)){
    header('location: showbook.php');
}