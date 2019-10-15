<?php

//$dsn = "pgsql:"
//    . "host=ec2-107-20-230-70.compute-1.amazonaws.com;"
//    . "dbname=da19331kqa55ff;"
//    . "user=frmkuzmbtknfgk;"
//    . "port=5432;"
//    . "sslmode=require;"
//    . "password=689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd";
//
//$conn = new PDO($dsn);
//$conn = pg_connect("host=c2-107-20-230-70.compute-1.amazonaws.com dbname=da19331kqa55ff user=frmkuzmbtknfgk password=689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd");

$db_url = getenv("DATABASE_URL") ?: "postgres://frmkuzmbtknfgk:689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd@ec2-107-20-230-70.compute-1.amazonaws.com:5432/da19331kqa55ff
";
$conn = pg_connect($db_url);

if (!$conn) {
    echo "Database connection failed.";
    echo pg_last_error($conn);
}
else {
}
