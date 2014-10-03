<?php
   header('Status: 403 Forbidden');
   header('HTTP/1.1 403 Forbidden');
   echo '<h2>You do not have permission to access this file/directory.</h2>';
   exit; 
?>