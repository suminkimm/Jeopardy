<?php

$db = parse_url(getenv("DATABASE_URL"));
$db["path"] = ltrim($db["path"], "/"); // db name

$conn = pg_connect("postgres://frmkuzmbtknfgk:689d7618f122e2f0f20d0bf20489d5d8e77617dde052d9b1bc5af61facd6b4fd@ec2-107-20-230-70.compute-1.amazonaws.com:5432/da19331kqa55ff
");

if (!$conn) {
    echo "Database connection failed.";
    echo pg_last_error($conn);
}
else {
    echo "Database connection success.";
}
//$result = pg_query($conn, "SELECT datname FROM pg_database");
//while ($row = pg_fetch_row($result)) {
//    echo "<p>" . htmlspecialchars($row[0]) . "</p>\n";
//}
