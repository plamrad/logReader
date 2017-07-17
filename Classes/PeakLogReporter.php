<?php

/**
 * @author Plam Rad <plamrad@gmail.com>
 */
class PeakLogReporter {

    /**
     *
     * @var string
     */
    private $logFile;

    /**
     *
     * The maximum number of concurrent logs at any given time
     * @var int
     */
    private $peakLogs = 0;

    /**
     *
     * Timestamp of the earliest moment $peakLogs maximum was reached
     * @var int
     */
    private $peakStartTime = 0;

    /**
     *
     * Timestamp of the latest moment of the $peakLogs maximum
     * @var int
     */
    private $peakEndTime = 0;

    /**
     * 
     * A count of logEndTimes showing the current concurrent logs
     * @var array
     */
    private $logCount = [];

    /**
     *
     * @param string $logFile
     */
    public function __construct($logFile) {
        $this->logFile = $logFile;
    }

    /**
     *
     * @param int $logEndTime
     */
    public function addLogToQueueCounter($logEndTime) {
        if (!isset($this->logCount[$logEndTime])) {
            $this->logCount[$logEndTime] = 1;
        } else {
            $this->logCount[$logEndTime] ++;
        }

        ksort($this->logCount);
    }

    /**
     *
     * @param int $logStartTime
     * @param int $earliestToLeaveTheQueueTime
     */
    public function updatePeakLogData($logStartTime, $earliestToLeaveTheQueueTime) {
        $concurrentLogsNumber = array_sum($this->logCount);

        if ($this->peakLogs < $concurrentLogsNumber) {
            $this->peakLogs = $concurrentLogsNumber;

            $this->peakStartTime = $logStartTime;
            $this->peakEndTime = $earliestToLeaveTheQueueTime;
        }
    }

    /**
     *
     * @param int $logStartTime
     */
    public function removeEndedLogsAndUpdate($logStartTime) {
        //$this->logCount has been through ksort so no need to reset
        $earliestToLeaveTheQueueTime = key($this->logCount);

        //remove the logs that have ended
        if ($logStartTime > $earliestToLeaveTheQueueTime) {
            foreach ($this->logCount as $ts => $count) {
                if ($ts < $logStartTime) {
                    unset($this->logCount[$ts]);
                }
            }
        }

        $this->updatePeakLogData($logStartTime, $earliestToLeaveTheQueueTime);
    }

    /**
     * Read line by line to extract and pass data onwards
     */
    public function iterateLogFile() {
        $handle = fopen($this->logFile, "r");
        if ($handle !== false) {
            while (!feof($handle)) {
                $line = fgets($handle);

                //example 1499904803:1499904834
                $times = explode(':', $line);

                //assuming the logs were created properly do a minimal validity check
                if (is_array($times) && count($times) == 2) {
                    $logStartTime = (int) $times[0];
                    $logEndTime = (int) $times[1];

                    $this->addLogToQueueCounter($logEndTime);
                    $this->removeEndedLogsAndUpdate($logStartTime);
                }
            }
        }
    }

    /**
     *
     * @return object
     */
    public function calculatePeakLog() {
        $this->iterateLogFile();

        return $this;
    }

    /**
     *
     * @return string
     */
    public function peakLogInfo() {
        $this->calculatePeakLog();

        if ($this->peakLogs > 0) {
            return
                    'The peak for this log log is ' .
                    $this->peakLogs .
                    ' simultaneous logs, that occurred between ' .
                    $this->peakStartTime .
                    ' and ' .
                    $this->peakEndTime;
        } else {
            return
                    'Unable to calculate the simultaneous logs';
        }
    }

}
