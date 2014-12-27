<?php
   header('Status: 403 Forbidden');
   header('HTTP/1.1 403 Forbidden');
   echo '<h2>'._x('You do not have permission to access this file/directory.', 'general', 'membpress'). '</h2>';
   exit; 
?>