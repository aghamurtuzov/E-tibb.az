<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['yeni-parol', 'token' => $user->password_reset_token]);
?>
Salam <?= $user->name ?>,

Aşağıdakı linkə daxil olaraq şifrənizi yeniləyə bilərsiniz:

<?= $resetLink ?>
