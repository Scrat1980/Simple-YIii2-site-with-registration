<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset".
 *
 * @property integer $id_asset
 * @property string $name
 * @property string $description
 *
 * @property AssetUser[] $assetUsers
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_asset' => 'Id Asset',
            'name' => 'Наименование',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssetUsers()
    {
        return $this->hasMany(AssetUser::className(), ['id_asset' => 'id_asset']);
    }
}
