<?php

/**
 * @author Plam Rad <plamrad@gmail.com>
 */
class LogDataCreator {

    const MAX_LOG_SIZE = 100000;

    /**
     *
     * @var string The path to the log file
     */
    private $filename;

    /**
     *
     * @var string Date Y-m-d H:i:s
     */
    private $startDateTime;

    /**
     *
     * @var string Date Y-m-d H:i:s
     */
    private $endDateTime;

    public function __construct($filename, $startDateTime, $endDateTime) {
        $this->filename = $filename;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }

    public function createDummyData() {

        $startLog = $startPeriod = strtotime($this->startDateTime);
        $endPeriod = strtotime($this->endDateTime);

        //remove old file
        if (is_file($this->filename)) {
            unlink($this->filename);
        }

        $i = 0;
        for ($t = $startPeriod; $t <= $endPeriod; $t++) {

            //Assume logs are made during normall office hours
            $timeOfDay = date('H', $t);
            if ($timeOfDay >= 9 && $timeOfDay <= 18) {

                //randomize starting time and log duration
                $startLog = $startLog + rand(0, 30);
                $endLog = $startLog + rand(20, 380);

                $i++;
                file_put_contents($this->filename, $startLog . ':' . $endLog . "\n", FILE_APPEND);
            }

            //enough data for testing purposes
            if ($i > self::MAX_LOG_SIZE) {
                return "Max log size of " . self::MAX_LOG_SIZE . " has been reached";
                exit;
            }
        }

        return "A number of " . $i . " have been created";
    }

}
