<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_roles".
 *
 * @property integer $id
 * @property string $title
 *
 * @property TblUser[] $tblUsers
 */
class TblRoles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUsers()
    {
        return $this->hasMany(TblUser::className(), ['role_id' => 'id']);
    }
}
