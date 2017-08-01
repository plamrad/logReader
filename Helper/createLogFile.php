<?php

/**
 * @author Plam Rad <plamrad@gmail.com>
 */
require_once '../Classes/LogDataCreator.php';

$filename = '../LogFiles/logs';
$logDataCreator = new LogDataCreator($filename, '2017-07-12 09:00:00', '2017-07-12 10:01:00');
echo $logDataCreator->createDummyData();
