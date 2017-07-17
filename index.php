<?php

/**
 * @author Plam Rad <plamrad@gmail.com>
 */
require_once 'Classes/PeakLogReporter.php';
$logFile = 'LogFiles/logs';

$LogPeakReporter = new PeakLogReporter($logFile);
echo $LogPeakReporter->peakLogInfo();
