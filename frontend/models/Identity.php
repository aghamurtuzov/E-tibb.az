<?php
namespace frontend\models;


use \yii\web\IdentityInterface;
use \frontend\models\User;
use \frontend\models\Doctor;
use \frontend\models\Enterprise;
final class Identity implements IdentityInterface
{
    const TYPE_USER = 'user';
    const TYPE_DOCTOR = 'doctor';
    const TYPE_ENTERPRISE = 'enterprise';

    private $_id;
    private $_authkey;
    private $_passwordHash;

    public static function findIdentity($id)
    {
        $ALLOWED_TYPES = [self::TYPE_USER, self::TYPE_DOCTOR, self::TYPE_ENTERPRISE];
        $parts = explode('-', $id);
        if (\count($parts) !== 2) {
            throw new \yii\base\InvalidCallException('id should be in form of Type-number');
        }
        [$type, $number] = $parts;

        if (!\in_array($type, $ALLOWED_TYPES, true)) {
            throw new \yii\base\InvalidCallException('Unsupported identity type');
        }

        $model = null;
        switch ($type) {
            case self::TYPE_USER:
                $model = User::find()->where(['id' => $number])->one();
                break;
            case self::TYPE_DOCTOR:
                $model = Doctor::find()->where(['id' => $number])->one();
                break;
            case self::TYPE_ENTERPRISE:
                $model = Enterprise::find()->where(['id' => $number])->one();
                break;
        }

        if ($model === null) {
            return false;
        }


        $identity = new Identity();
        $identity->_id = $id;
        $identity->_authkey = $model->authkey;
        $identity->_passwordHash = $model->password_hash;
        return $identity;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $model = User::find()->where(['token' => $token])->one();
        if (!$model) {
            $model = Doctor::find()->where(['token' => $token])->one();
            if(!$model) {
                $model = Enterprise::find()->where(['token' => $token])->one();
            }
        }

        if (!$model) {
            return false;
        }

        if ($model instanceof User) {
            $type = self::TYPE_USER;
        } elseif ($model instanceof Doctor) {
            $type = self::TYPE_DOCTOR;
        }elseif ($model instanceof Enterprise) {
            $type = self::TYPE_ENTERPRISE;
        }

        $identity = new Identity();
        $identity->_id = $type . '-' . $model->id;
        $identity->_authkey = $model->authkey;
        $identity->_passwordHash = $model->password_hash;
        return $identity;
    }

    public function validatePassword($password)
    {
        return password_verify($password, $this->_passwordHash);
    }

    public static function findIdentityByEmail($email)
    {
        $model = User::find()->where(['email' => $email])->one();
        if (!$model) {
            $model = Doctor::find()->where(['email' => $email])->one();
            if (!$model) {
                $model = Enterprise::find()->where(['email' => $email])->one();
            }
        }

        if (!$model) {
            return false;
        }

        if ($model instanceof User) {
            $type = self::TYPE_USER;
        } elseif ($model instanceof Doctor) {
            $type = self::TYPE_DOCTOR;
        }elseif ($model instanceof Enterprise) {
            $type = self::TYPE_ENTERPRISE;
        }


        $identity = new Identity();
        $identity->_id = $type . '-' . $model->id;
        $identity->_authkey = $model->authkey;
        $identity->_passwordHash = $model->password_hash;
        return $identity;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getAuthKey()
    {
        return $this->_authkey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}