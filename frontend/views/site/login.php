<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$bundle = yiister\gentelella\assets\Asset::register($this);

$this->title = 'İdarə paneli';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login">
<?php $this->beginBody(); ?>
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="form login_form">
            <section class="login_content">

                <?PHP $form = ActiveForm::begin(['id'=>'login-form']) ?>

                <?= Html::tag('h1',Html::encode($this->title)) ?>

                <?= $form->field($model,'username')->textInput(['placeholder' => 'İstifadəçi adı','autofocus'=>true])->label(false) ?>

                <?= $form->field($model,'password')->passwordInput(['placeholder' => 'Şifrə'])->label(false) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['label'=>'Məni xatırla','value'=>true]) ?>

                <?= Html::submitButton('Daxil ol',['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>

                <div class="separator">
                    <div>
                        <p>© <?=date('Y')?> Bütün hüquqlar qorunur.E-tibb.az</p>
                    </div>
                </div>

                <?PHP ActiveForm::end(); ?>

            </section>
        </div>
    </div>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>