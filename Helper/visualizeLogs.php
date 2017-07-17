<?php
/**
 * Plam Rad <plamrad@gmail.com>
 */
//as this is intended for viewing in a browser kill it in the CLI
php_sapi_name() === 'cli' ? die('Browser viewing only') : '';
?><!DOCTYPE html>
<html>
    <head>
        <title>Log Viewer</title>
        <script
            src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
        crossorigin="anonymous"></script>

        <link href="/Resources/visualizeLogs.css" rel="stylesheet" type="text/css" />
        <script src="/Resources/visualizeLogs.js" />
    </head>
    <body>
        <div id="vertical_line"></div>
        <?php
        $logFile = '../LogFiles/logs';

        $lineCount = 0;
        $infoBoxMargin = 120;

        $handle = fopen($logFile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            $lineCount++;
        }

        $line = fgets(fopen($logFile, 'r'));
        $times = explode(':', $line);

        $initialStartTime = $times[0];
        $initialEndTime = $times[1];

        $handle = fopen($logFile, "r") or die("");

        $i = 0;
        while (!feof($handle)) {
            $line = fgets($handle);
            $times = explode(':', $line);

            if (count($times) == 2) {
                $i++;

                $thisStartTime = $times[0];
                $thisEndTime = $times[1];

                $marginLeft = $thisStartTime - $initialStartTime + $infoBoxMargin;
                $width = $thisEndTime - $thisStartTime - 8; //account for border and padding

                $startTimeShort = substr($thisStartTime, 5, 5);
                $endTimeShort = substr($thisEndTime, 5, 5);
                echo
                '
                <div id="log_box_' . $i . '" data-i="' . $i . '" class="log_box" style="margin-left: ' . $marginLeft . 'px; '
                . 'width: ' . $width . 'px;">'
                . '<div class="log_box_info" style="margin-left: -' . $infoBoxMargin . 'px;">'
                . '<span></span> | s: ' . $startTimeShort
                . ' | e: ' . $endTimeShort
                . ' | d: ' . ($endTimeShort - $startTimeShort)
                . ' </div></div>';
            }
        }
        ?>
        <div id="vertical_line_position_info"></div>
        <div id="logPeak_info"></div>
        <div id="ts_clicked_info"></div>
    </body>
</html>
