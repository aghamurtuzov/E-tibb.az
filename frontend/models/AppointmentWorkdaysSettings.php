<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_transaction_details".
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $day
 * @property string $setting
 *
 */
class AppointmentWorkdaysSettings extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctor_workdays_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id'], 'required'],
            ['day','number'],
            ['setting','string','max'=>250]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Hekim ID',
            'day' => 'Gün',
            'setting' => 'Tənzimləmə'
        ];
    }

    public function getUserWorkday($user_id, $day)
    {
        $result = $this->db->createCommand("SELECT `setting` FROM `".self::tableName()."` WHERE `doctor_id`=$user_id and `day`='$day'")->queryOne();
        return $result;
    }

    public static function getWorkTime($startTime, $endTime, $interval, $breakTimeStart, $breakTimeEnd)
    {
        $data      = [];
        $loop      = 0;
//        $request   = Yii::$app->request;
//        $startTime = $request->get('start-time');
//        $endTime   = $request->get('end-time');
//        $interval  = $request->get('interval');
        $interval  = !empty($interval) ? intval($interval) : 30;
//        $breakTimeStart = $request->get('break-time-start');
//        $breakTimeEnd   = $request->get('break-time-end');

        /** Loop teyin etme */
        if(strpos($startTime,':')){$exp_start = explode(':',$startTime); }
        if(strpos($endTime,':')){ $exp_end = explode(':',$endTime); }

        if(isset($exp_start[1]) && isset($exp_end[1]))
        {
            $minute = (intval($exp_end[0])-intval($exp_start[0]))*60;

            $a = intval($exp_start[1]);
            $b = intval($exp_end[1]);

            if($a>$b)
            {
                $d = $b == 0 ? $minute-$a : $minute-($a-$b);
            }else{
                $d = $a == 0 ? $minute+$b : $minute+($b-$a);
            }

            $loop = intval($d/$interval);

        }

        /** Fasile loop teyin etme */
        if(strpos($breakTimeStart,':')){ $exp_breakStart = explode(':',$breakTimeStart); }
        if(strpos($breakTimeEnd,':')){ $exp_breakEnd = explode(':',$breakTimeEnd); }

        if(isset($exp_breakStart[1]) && isset($exp_breakEnd[1]))
        {
            $minute = (intval($exp_breakEnd[0])-intval($exp_breakStart[0]))*60;

            $i = intval($exp_breakStart[1]);
            $f = intval($exp_breakEnd[1]);

            if($i>$f)
            {
                $g = $f == 0 ? $minute-$i : $minute-($i-$f);
            }else{
                $g = $i == 0 ? $minute+$f : $minute+($f-$i);
            }

            //$breakTimeLoop = intval($g/$interval);
            $breakTimeLoop = ceil($g/$interval);

        }

        /** Vaxt siyahisi */
        $startTime_str = strtotime($startTime);

        $bp = 0;
        $limit = 0;
        for($x=1;$x<$loop;$x++)
        {
            if($x == 1)
            {
                $t2 = date("H:i", strtotime('+'.$interval.' minutes', $startTime_str));
                $data['list'][]= $startTime.'-'.$t2;
            }

            $t1   = date("H:i", strtotime('+'.$interval.' minutes', $startTime_str));
            $t2   = date("H:i", strtotime('+'.$interval.' minutes', strtotime($t1)));

            if(strpos($t1,':')){ $exp_t1 = explode(':',$t1); }

            if(isset($exp_breakStart))
            {
                if($exp_t1[0] == $exp_breakStart[0] && $exp_t1[1]>=$exp_breakStart[1] && $bp == 0)
                {
                    $bp = 1;
                    $limit = $x+$breakTimeLoop;
                    $startTime_str = strtotime($t1);
                    continue;
                }

                if($bp == 1 && $x<$limit)
                {
                    $startTime_str = strtotime($t1);
                    continue;
                }
            }

            $data['list'][] = $t1.'-'.$t2;

            $startTime_str = strtotime($t1);

        }
        return $data;
    }
}
