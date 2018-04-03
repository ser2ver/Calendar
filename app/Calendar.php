<?php

namespace App;

class Calendar
{
    /** @var int[] $DaysInMonth */
    private static $DaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    /** @var string[] $MonthNames */
    public static $MonthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

    /** @var \App\User $user */
    private $user;

    /**
     * @param array|string $date
     * @return int
     */
    public static function getDaysInMonth($date) {
        if (!is_array($date))
            $date = date_parse($date);

        $days = self::$DaysInMonth[$date['month']-1];
        if (($date['month'] == 2) && ($date['year'] % 4 == 0))
            ++$days;

        return $days;
    }

    /**
     * @param array|string $date
     * @param bool $withWeek
     * @return array
     */
    public static function parseDate($date, $withWeek=false) {
        if (!is_array($date))
            $date = date_parse($date);

        $res = [
            'year'  => $date['year'],
            'month' => $date['month'],
            'day'   => $date['day']
        ];
        if ($withWeek) {
            $res['week'] = date('w', mktime(12, 0, 0, $res['month'], $res['day'], $res['year'])) - 1;
            if ($res['week'] < 0)
                $res['week'] = 6;
        }

        return $res;
    }

    /**
     * Calendar constructor.
     * @param \App\User|null $user
     */
    public function __construct(User $user=null) {
        $this->user = $user;
    }

    /**
     * @param int $year
     * @param int $month
     * @return array
     */
    public function make(int $year=0, int $month=0) {
        $today = self::parseDate(date('Y-m-d'));

        if ($year <= 0)
            $year = $today['year'];
        if ($month <= 0)
            $month = $today['month'];
        $days = self::getDaysInMonth(sprintf('%04d-%02d-01', $year, $month));

        $res = [
            'today' => $today,
            'year' => $year,
            'month' => $month
        ];

        if ($this->user) {
            $notes = $this->user->notes()
                ->selectRaw('day, count(*) AS notes')
                ->where('year', $res['year'])
                ->where('month', $res['month'])
                ->groupBy('day')
                ->get();

            foreach ($notes as $note) {
                $res['notes'][$note->day] = $note->notes;
            }
        }

        $pyear = $year;
        if (($pmonth = $month - 1) <= 0) {
            $pmonth = 12;
            --$pyear;
        }
        $pdays = self::getDaysInMonth(sprintf('%04d-%02d-01', $pyear, $pmonth));

        $nyear = $year;
        if (($nmonth = $month + 1) > 12) {
            $nmonth = 1;
            ++$nyear;
        }
        $ndays = self::getDaysInMonth(sprintf('%04d-%02d-01', $nyear, $nmonth));

        $firstDay = self::parseDate(sprintf('%04d-%02d-01', $pyear, $pmonth), true);
        $weekDay = $firstDay['week'];
        for ($i=1; $i <= $pdays; ++$i) {
            $res['prev'][$i] = [
                'week' => $weekDay
            ];
            if (++$weekDay > 6)
                $weekDay = 0;
        }

        $firstDay = self::parseDate(sprintf('%04d-%02d-01', $year, $month), true);
        $weekDay = $firstDay['week'];
        for ($i=1; $i <= $days; ++$i) {
            $res['curr'][$i] = [
                'week' => $weekDay
            ];
            if (++$weekDay > 6)
                $weekDay = 0;
        }

        $firstDay = self::parseDate(sprintf('%04d-%02d-01', $nyear, $nmonth), true);
        $weekDay = $firstDay['week'];
        for ($i=1; $i <= $ndays; ++$i) {
            $res['next'][$i] = [
                'week' => $weekDay
            ];
            if (++$weekDay > 6)
                $weekDay = 0;
        }

        return $res;
    }
}