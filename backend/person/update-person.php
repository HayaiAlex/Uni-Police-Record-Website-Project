<?php

header('Content-Type: application/json');

require_once(dirname(__DIR__,1).'/protected/database.php');

echo '{"status":1, "message":"person updated"';