<?php
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::url_edit(
    $_POST["act"],
    $_POST["id"],
    $_POST["program_default_name"],
    $_POST["platform"],
    $_POST["weight"],
    $_POST["url"],
    $_POST["interval"],
    $_POST["content"]
);

echo json_encode($result);

