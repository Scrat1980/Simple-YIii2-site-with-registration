<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset_user".
 *
 * @property integer $id_asset_user
 * @property integer $id_user
 * @property integer $id_asset
 * @property integer $quantity
 *
 * @property TblUser $idUser
 * @property Asset $idAsset
 */
class AssetUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_asset', 'quantity'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_asset_user' => 'Id Asset User',
            'id_user' => 'Id User',
            'id_asset' => 'Id Asset',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(TblUser::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAsset()
    {
        return $this->hasOne(Asset::className(), ['id_asset' => 'id_asset']);
    }
}
