<?php

session_start();

session_destroy();

header("location: ../telas/loja.php");