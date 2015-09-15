<?php
exec('git pull', $output, $return_value);
echo '<pre>';
print_r($output);
echo '</pre>';
echo 'Return value: '.$return_value;
?>