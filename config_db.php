<?php

//$db = parse_url(getenv("postgres://frmkuzmbtknfgk:689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd@ec2-107-20-230-70.compute-1.amazonaws.com:5432/da19331kqa55ff
//"));
//$db["path"] = ltrim($db["path"], "/"); // db name
//
//echo "db: ".$db;
//
//$conn = pg_connect(getenv("postgres://frmkuzmbtknfgk:689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd@ec2-107-20-230-70.compute-1.amazonaws.com:5432/da19331kqa55ff
//"));


$dsn = "pgsql:"
    . "host=ec2-107-20-230-70.compute-1.amazonaws.com;"
    . "dbname=da19331kqa55ff;"
    . "user=frmkuzmbtknfgk;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd";

$conn = new PDO($dsn);

//$conn = pg_connect("postgres://frmkuzmbtknfgk:689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd@ec2-107-20-230-70.compute-1.amazonaws.com:5432/da19331kqa55ff
//");

//$result = pg_query($conn, "SELECT datname FROM pg_database");
//while ($row = pg_fetch_row($result)) {
//    echo "<p>" . htmlspecialchars($row[0]) . "</p>\n";
//}

if (!$conn) {
    echo "Database connection failed.";
    echo pg_last_error($conn);
}
else {
    echo "Database connection success.";
}
