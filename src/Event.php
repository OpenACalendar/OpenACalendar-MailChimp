<?php

class Event {

    protected $data;

    function __construct( $data ) {
        $this->data = $data;
    }

    public function getSlug() {
        return $this->data->slug;
    }

    public function getTitle() {
        return $this->data->summaryDisplay;
    }

    public function getDescription() {
        return $this->data->description;
    }

    public function getURL() {
        if ($this->data->url) {
            return $this->data->url;
        } else if ($this->data->ticket_url) {
            return $this->data->ticket_url;
        } else {
            return $this->data->siteurl;
        }
    }

    public function getOurURL() {
        return $this->data->siteurl;
    }

    public function getStartLocalTime() {
        $start = new \DateTime('', new \DateTimeZone('Europe/London'));
        $start->setDate($this->data->start->yearlocal, $this->data->start->monthlocal, $this->data->start->daylocal);
        $start->setTime($this->data->start->hourlocal, $this->data->start->minutelocal, 0);
        return $start;
    }

    public function getEndLocalTime() {
        $end = new \DateTime('', new \DateTimeZone('Europe/London'));
        $end->setDate($this->data->end->yearlocal, $this->data->end->monthlocal, $this->data->end->daylocal);
        $end->setTime($this->data->end->hourlocal, $this->data->end->minutelocal, 0);
        return $end;
    }

    public function isStartOnDate($year, $month, $day) {
        $start = $this->getStartLocalTime();
        return $start->format('y') == $year && $start->format('m') == $month && $start->format('d') == $day;
    }

    public function isContinuingOnDate($year, $month, $day) {
        $start = $this->getStartLocalTime();
        $start->setTime(23, 59, 59);

        $end = $this->getEndLocalTime();
        $end->setTime(23,59,59);

        $date = new \DateTime('', new \DateTimeZone('Europe/London'));
        $date->setDate($year, $month, $day);

        return  $start->getTimestamp() <= $date->getTimestamp() && $date->getTimestamp() <= $end->getTimestamp();
    }

    public function hasLatLng() {
        return isset($this->data->venue) && (boolean)$this->data->venue->lat && (boolean)$this->data->venue->lng;
    }

    public function getLat() {
        return $this->data->venue->lat;
    }

    public function getLng() {
        return $this->data->venue->lng;
    }

}