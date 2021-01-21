<?php


namespace App;

use Illuminate\Support\Facades\DB;
use function foo\func;

class MeetLog
{

    static function toProductionArray($MeetLogsArray, $filterFunction = null)
    {

        $result = [];

        foreach ($MeetLogsArray as $item) {
            foreach (explode(',', $item->pair) as $pair) {

                $meetIndex = md5($item->meet_code . $pair . $item->date);
                $memberIndex = md5($item->member_id . $item->member_name);

                $result[$meetIndex]['pair'] = $pair;
                $result[$meetIndex]['meet_code'] = $item->meet_code;
                $result[$meetIndex]['date'] = $item->date;
                $result[$meetIndex]['org_email'] = $item->org_email;
                $result[$meetIndex]['members'][$memberIndex]['member_name'] = $item->member_name;
                $result[$meetIndex]['members'][$memberIndex]['groupname'] = $item->group;
                $result[$meetIndex]['members'][$memberIndex]['member_email'] = $item->member_id;
                $result[$meetIndex]['members'][$memberIndex]['durations'][] = intval($item->duration);
                $result[$meetIndex]['members'][$memberIndex]['times'][] = $item->times;
                $result[$meetIndex]['members'][$memberIndex]['start_times'][] = $item->start_time;

                $item->lecturer ? $result[$meetIndex]['members'][$memberIndex]['lecturer_id'] = $item->lecturer : null;

                if ($item->member_id === $item->org_email) {
                    $result[$meetIndex]['org'] = $item->member_name;
                    $result[$meetIndex]['members'][$memberIndex]['org'] = 1;
                }

            }
        }

        $result = array_values($result);
        // $meet['members'] = array_values($meet['members']);
        foreach ($result as $key => &$meet) {

            $meet['id'] = $key + 1;
            $meet['members_count'] = count($meet['members']);
            $lecturers = [];

            foreach ($meet['members'] as &$memberItem) {

                $memberItem['duration'] = round(array_sum($memberItem['durations']) / 60);
                $memberItem['start_time'] = min($memberItem['start_times']);
                $memberItem['time'] = max($memberItem['times']);
                isset($memberItem['lecturer_id']) ? $lecturers[] = $memberItem : null;
            }

            count($lecturers) > 1 ? usort($lecturers, function ($a, $b, $key = 'duration') {
                return ($a[$key] <=> $b[$key]);
            }) : null;
            $meet['lecturer'] = array_pop($lecturers);


            $meet['groups'] = implode(',', array_filter(array_unique(array_column($meet['members'], 'groupname'))));


        }
        if ($filterFunction && is_callable($filterFunction)) {
            $result = array_filter($result, $filterFunction);
        }


        return $result;
    }

    static function getAttendance($date)
    {
        $sql = self::selectLogs(" and m.`date`=:in_datetime ");

        $bind = array('in_datetime' => $date);

        return self::toProductionArray(DB::select($sql, $bind));

    }

    static function getPairsLessHour($startDate, $endDate = null)
    {

        $and = "and m.`date`>=:startDate ";
        $endDate ? $and .= " and m.`date`<=:endDate " : null;
        $sql = self::selectLogs($and);

        $bind = ['startDate' => $startDate];
        $endDate ? $bind['endDate'] = $endDate : null;

        $filter = function ($item) {
            return $item['lecturer'] && $item['lecturer']['duration'] < 60;
        };
        return self::toProductionArray(DB::select($sql, $bind), $filter);
    }

    static function selectLogs($and = '')
    {
        return " select
                                     m.id_meet,
                                     m.meet_code,
                                     m.member_id,
                                     m.member_name,
                                     l.fullName,
                                     l.email,
                                     m.org_email,
                                     m.pair,
                                     m.group as group_id,
                                     ce.groupName as `group`,
                                     m.lecturer,
                                     m.date,
                                     SUBTIME(m.times,SEC_TO_TIME(m.duration)) as start_time,
                                     m.times,
                                     m.duration
                                     from meet_logs_cls m
                                     left join contingent_etalon_lecturer l
                                     on m.lecturer = l.id
                                     left join contingent_etalon ce
                                     on ce.id = m.group
                                     where m.processed = 1 " . $and;
    }


}
