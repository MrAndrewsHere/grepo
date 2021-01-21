<?php

namespace App\Http\Controllers;

use App\MeetLog;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use  Carbon\Carbon;


class GrepoController extends Controller
{
    public function __construct()
    {

        env('APP_URL') !== 'http://grepo.test' ? $this->middleware('auth', ['except' => 'functest']) : null;
    }

    public function return_data($data)
    {
        return response()->json($data, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }

    public function return_array($sql, $bind = array(), $is_json = true)
    {
        $result = DB::select($sql, $bind);
        return $is_json ? $this->return_data($result) : $result;
    }

    public function linechart()
    {

        $sql = 'select * from linechart order by `dd`';
        $data = $this->return_array($sql, [], false);

        $reducer = function ($accum, $current) {
            $accum['labels'][] = Carbon::parse($current->dd)->format('d.m');
            $accum['values']['pairs'][] = $current->cnt_pairs40;
            $accum['values']['students'][] = $current->cnt_sudents40;
            return $accum;
        };

        $dataForChart = array_reduce($data, $reducer);

        return $this->return_data($dataForChart);
    }


    public function barchart()
    {

        $sql = 'select * from barchart order by `day`';
        $result = $this->return_array($sql, array(), false);

        $reducer = function ($accum, $current) {
            $accum['labels'][] = Carbon::parse($current->day)->format('d.m');
            $accum['dataset'][] = $current->p;
            return $accum;
        };

        $dataForChart = array_reduce($result, $reducer);

        return $this->return_data($dataForChart);
    }

    public function stats()
    {
        try {
            $now = Carbon::now(new \DateTimeZone('Europe/Moscow'));
            $date_day = $now->copy()->subDays(1)->format('Y-m-d');

            $to_day = "'" . $now->copy()->startOfWeek()->format('Y-m-d') . "'";
            $now->isMonday() ? $to_day_end = "'" . $now->format('Y-m-d') . "'" : $to_day_end = "'" . $date_day . "'";

            $sdsdf = " (select t.count as cnt from (select groupRank,count(*) as count from contingent_etalon group by groupRank) t where groupRank = 'def') ct";
          $old = "(select count(*) as cnt from contingent_etalon) ct";
            $sql3 = 'select DATE_FORMAT(' . $to_day . ', "%d.%m")  as start_day,DATE_FORMAT(' . $to_day_end . ', "%d.%m")  as end_day, avgs, cnt, ROUND(((avgs*100)/cnt),1) as p from
                      (select avg(cnt_sudents40) as avgs from
                        (select count(member_name) as cnt_sudents40,dd from
                        (select member_name, sum(duration) as sum_time, date as dd from
                            meet_logs_cls where member_id<>org_email group by date, member_name) a
                        where sum_time>2400 and dd>=' . $to_day . ' and dd<=' . $to_day_end . ' group by dd) d
                      ) av, (select groupRank,count(*) as cnt from contingent_etalon  where groupRank = "def" group by groupRank limit 1 ) ct';
            $sql2 = 'select cnt_pairs40, cnt_sudents40 from
                    (select count(meet_code) as cnt_pairs40 from
                        (select meet_code, sum(duration) as sum_time from
                        meet_logs_cls where `date`=? and member_id<>org_email group by meet_code) a where sum_time>2400) b,
                        (select count(member_name) as cnt_sudents40 from
                            (select member_name, sum(duration) as sum_time from
                            meet_logs_cls where `date`=? and member_id<>org_email group by member_name) a where sum_time>2400) d;';

            $sql = 'select * from
                    (select count(meet_code) as cnt_pairs15 from (select meet_code, sum(duration) as sum_time from meet_logs_cls where `date`=CURDATE() and member_id<>org_email group by meet_code) a where sum_time>900) a,
                    (select count(meet_code) as cnt_pairs40 from (select meet_code, sum(duration) as sum_time from meet_logs_cls where `date`=CURDATE() and member_id<>org_email group by meet_code) a where sum_time>2400) b,
                    (select count(member_name) as cnt_sudents15 from (select member_name, sum(duration) as sum_time from meet_logs_cls where `date`=CURDATE() and member_id<>org_email group by member_name) a where sum_time>900) c,
                    (select count(member_name) as cnt_sudents40 from (select member_name, sum(duration) as sum_time from meet_logs_cls where `date`=CURDATE() and member_id<>org_email group by member_name) a where sum_time>2400) d;';

            $result = [
                'today' => $this->return_array($sql, array(), false),
                'yesterday' => $this->return_array($sql2, array($date_day, $date_day), false),
                'yesterday_day' => $date_day,
                'p_today' => $this->return_array($sql3, array($date_day, $date_day), false),
            ];

            return $this->return_data($result);
        } catch (\Exception $exception) {
            return $this->return_data($exception->getMessage());
        }
    }


    public function attendance($date = null)
    {
        if (!$date) {
            $date = Carbon::now(new \DateTimeZone('Europe/Moscow'))->format('Y-m-d');
        }

        return $this->return_data(MeetLog::getAttendance($date));
    }

    public function PairsLessHour($startDate = null, $toDate = null)
    {
        !$startDate ? $startDate = Carbon::now(new \DateTimeZone('Europe/Moscow'))->format('Y-m-d') : null;

        return $this->return_data(MeetLog::getPairsLessHour($startDate, $toDate));
    }


    public function functest()
    {

//
//        $logs = DB::select('select * from meet_logs_cls limit 1000');
//
//        $sql = "select * from contingent_etalon_free";
//        $contingent_etalon_free = DB::select($sql);
//        $empty = [];
//
//        foreach ($logs as $log)
//        {
//            $login = explode('@',$log->member_id)[0];
//            $isset = array_filter($contingent_etalon_free, function ($item) use ($login){
//               return strtolower($item->freeLogin) === strtolower($login);
//            });
//            if(count($isset) > 0)
//            {
//                $log->login = $isset;
//            }
//            else
//            {
//                $empty[]= $log;
//            }
//
//        }


//        $sql = "select
//                c.id,
//                c.groupName as etalonGroup,
//                c.department,
//                c.secondName,
//                c.firstName,
//                c.thirdName,
//                f.fullName,
//                f.groupName as freeGroup,
//                f.freeLogin from contingent_etalon c
//                inner join contingent_etalon_free f
//                on CONCAT(LOWER(c.secondName),' ',LOWER(c.firstName),' ',LOWER(c.thirdName)) like CONCAT('%', LOWER(f.fullName),'%')
//               limit 100";
//        $res = DB::select($sql);
//        $mails = DB::select('select from_email,from_name from mails group by from_email,from_name');
//        $lecurers = DB::select('select fullName from contingent_etalon_lecturer');

//        $sql = "select
//                c.id,
//                c.fullName,
//                m.from_email ,
//                m.from_name
//                from contingent_etalon_lecturer c
//                inner join (select from_email,from_name from mails group by from_email,from_name) m
//                 on  c.fullName  like CONCAT('%',m.from_name,'%')
//
//                 where  CHAR_LENGTH(m.from_name) > 10 group by  m.from_email, m.from_name,  c.fullName,c.id";


//        $sql = "select
//                c.id,
//                m.member_id,
//                m.member_name,
//                c.fullName,
//                c.email
//                from meet_logs_cls m
//                inner join contingent_etalon_lecturer c
//                on c.email = m.member_id ";

//        $sql = "select
//                id,
//                fullName,
//                email
//                from contingent_etalon_lecturer
//                where email = 'geo@uni-dubna.ru'";


        $sql = "select

               c.groupName,
                c.login
                from contingent_etalon c
                where 'gfn.20'  like CONCAT( LOWER(c.login),'%')
            ";


        $sql = "select

               c.freeLogin,
                c.fullName
                from contingent_etalon_free c
                where 'gfn.20'  like CONCAT( LOWER(c.freeLogin),'%')
            ";

        $sql = "select

                c.secondName,
                c.firstName,
               c.groupName,
                c.login
                from contingent_etalon c
                where c.secondName = 'Ганюшкин'
            ";

//        $sql = "select
//                c.id,
//                c.fullName,
//                m.from_email ,
//                m.from_name
//                from contingent_etalon_lecturer c
//                inner join (select from_email,from_name from mails group by from_email,from_name) m
//                 on  c.fullName  like CONCAT('%',m.from_name,'%')
//
//                 where  CHAR_LENGTH(m.from_name) > 10 group by  m.from_email, m.from_name,  c.fullName,c.id";
//        $sql = "select
//                c.fullName,
//                c.email
//                from contingent_etalon_lecturer c
//                left join (select from_email,from_name from mails group by from_email,from_name) m
//                on c.email = m.from_email
//                where c.email in not null";

//        $sql = "select from_email,from_name
//                from mails
//                where CHAR_LENGTH(from_name) > 10
//                group by from_email,from_name";



        //$sql = "select groupRank,count(*) as count from contingent_etalon group by groupRank";
        $sql = "select groupName,groupRank,login from contingent_etalon";

        $res = DB::select($sql);
        $resNew = [];
        foreach ($res as $item){
            $resNew[$item->groupRank][$item->groupName][] = $item;
           //$resNew[$item->groupRank] = array_unique($resNew[$item->groupRank]);
        }


        return response()->json($resNew, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);

    }
    public function groupRanks(){
        $sql = "select groupName,groupRank,login,CONCAT(secondName,' ',firstName,' ',thirdName) as fullName ,department,direction from contingent_etalon";

        $res = DB::select($sql);
        $resNew = [];
        foreach ($res as $item){

            $resNew[$item->groupRank][$item->groupName][] = $item;
            //$resNew[$item->groupRank] = array_unique($resNew[$item->groupRank]);
        }
        $i=1;
        $j = 1;
        $newrara = [];
        foreach ($resNew as $key => $raw)
        {

            $first =  [];
            $first['id'] = $i++;

            $countPeople = 0;
            foreach ($raw as $key2 => $group)
            {
                $second = [];
                $second['id'] = $i++;


                $second['childrens'] = array_values($group);
//                  usort($second['childrens'], function ($a,$b) { return $a->groupName <=> $b->groupName;});
                $cc = count($second['childrens']);
                $countPeople+=$cc;
                $second['name'] = $key2.' '.$cc.' чел.';
                $first['children'][] = $second;
            }
            $first['name'] = $key.' '.count($first['children']).' групп '.$countPeople." чел.";


           // $first['children'] = array_values($raw);
            $newrara[]=$first;
        }
        return response()->json($newrara, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }
    public function mails($date = 'today')
    {
        switch ($date) {
            case 'today':
            {
                $date = 'CURDATE()';
                break;
            }
            case 'week':
            {
                $date = '(DATE_SUB(CURDATE(), INTERVAL 7 DAY))  ';
                break;
            }
            case 'month':
            {
                $date = '(DATE_SUB(CURDATE(), INTERVAL 30 DAY)) ';
                break;
            }
            case 'all':
            {
                $date = '2017-01-01 ';
                break;
            }
            default:
            {
                $date = 'CURDATE()';
            }
        }

        $res = DB::select("select * from mails m left join attachments a on m.message_id=a.mail_id where mail_date >=" . $date . " order by m.from_email,m.mail_date DESC ");
        $result = array();

        foreach ($res as $item) {
            $result[$item->from_email]['name'] = mb_convert_encoding($item->from_name, 'Windows-1252', 'utf-8');

            $result[$item->from_email]['email'] = $item->from_email;
            $result[$item->from_email]['mails'][$item->message_id]['date'] = Carbon::parse($item->mail_date)->format('H:i d-m-Y');

            if ($item->type == 'jpg' || $item->type == 'jpeg' || $item->type == 'png' || $item->type == 'bmp') {
                $tmp = explode('/', $item->filepath);
                $nam = array_pop($tmp);
                $tmp = explode('.', $nam);
                array_pop($tmp);
                $tmp = implode('.', $tmp);

                $thumb = 'storage/attachments/thumbs/' . $tmp . '.jpg';
                if (!is_readable('/usr/share/nginx/html/grepo/pwa/' . $thumb)) {
                    $thumb = $item->filepath;
                }
            } else {
                $thumb = 'img/' . $item->type . '.png';
            }

            $result[$item->from_email]['mails'][$item->message_id]['attachments'][] = array([
                'path' => $item->filepath,
                'type' => $item->type,
                'name' => mb_convert_encoding($item->name, 'Windows-1252', 'utf-8'),
                'thumb' => $thumb,
            ]);
        }
        return response()->json($result, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }
}
