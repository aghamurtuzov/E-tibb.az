<?PHP
use backend\components\Functions;

if(isset($data) && !empty($data['answer']))
{
?>
    <div class="answer">
        <div class="header">
            <a href="javascript:void(0);" class="float-right darkblue-color"><img src="assets/img/icon/doctor-c.png"> <?=$data['doctor']?></a>
            <a href="javascript:void(0);" class="float-left gray-color"><img src="assets/img/icon/date-d.png"><?=Functions::getDatetime($data['a_datetime'])?></a>
            <div class="clearfix"></div>
        </div>
        <div class="body">
            <p><?=$data['answer']?></p>
        </div>
    </div>
<?PHP
};
?>