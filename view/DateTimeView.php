<?php

namespace View;

class DateTimeView {
    // [Day of week], the [day of month numeric]th of [Month as text] [year 4 digits].The time is [Hour]:[minutes]:[Seconds].
    // Example: "Monday, the 8th of July 2015, The time is 10:59:21".
    private $formatString = 'l, \\t\\h\\e jS \\of F Y, \\t\\h\\e \\t\\i\\m\\e \\i\\s H:i:s';

    public function generateHTML() {
        $dateString = date($this->formatString);

        return '<p>'.$dateString.'</p>';
    }
}
