<!--
    Niszczymy sesje
 -->
<?php
session_start();

session_unset();    // TAK NISZCZYMY SESJE

header('Location: index.php');
?>
<!-- 

-->