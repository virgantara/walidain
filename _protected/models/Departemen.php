<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%departemen}}".
 *
 * @property int $id
 * @property string $nama
 * @property int $departemen_level_id
 * @property int $perusahaan_id
 * @property string $visi
 * @property string $misi
 * @property string $tujuan
 * @property string $sasaran
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DepartemenLevel $departemenLevel
 * @property Perusahaan $perusahaan
 * @property DepartemenUser[] $departemenUsers
 */
class Departemen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%departemen}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'departemen_level_id', 'perusahaan_id', 'visi', 'misi', 'tujuan', 'sasaran'], 'required'],
            [['departemen_level_id', 'perusahaan_id'], 'integer'],
            [['visi', 'misi', 'tujuan', 'sasaran'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 100],
            [['departemen_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => DepartemenLevel::className(), 'targetAttribute' => ['departemen_level_id' => 'id']],
            [['perusahaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Perusahaan::className(), 'targetAttribute' => ['perusahaan_id' => 'id_perusahaan']],
        ];
    }

    public static function getDepartemenId()
    {
        $userPt = '';
               
        $userLevel = Yii::$app->user->identity->access_role;    
            
        $query=DepartemenUser::find();
        if($userLevel != 'admin' && ($userLevel == 'operatorCabang' || $userLevel == 'operatorApotik')){
            $userPt = Yii::$app->user->identity->perusahaan_id;

            $query->where('user_id = :p1' ,[':p1'=>Yii::$app->user->id]);
            // $where = array_merge($where,['perusahaan_id' => $userPt]);   
            
        }

        $list=$query->one();

        return $list->id;
    }

    public static function getListUnits()
    {
        $userPt = '';
               
        $userLevel = Yii::$app->user->identity->access_role;    
            
        $query=Departemen::find();
        if($userLevel != 'admin' && $userLevel == 'operatorCabang'){
            $userPt = Yii::$app->user->identity->perusahaan_id;

            $query->where([
                'perusahaan_id'=>$userPt,
                'departemen_level_id' => 3
            ]);
            // $query->andWhere('departemen_level_id');
            // $where = array_merge($where,['perusahaan_id' => $userPt]);   
            
        }

        $list=$query->all();
        $listData=\yii\helpers\ArrayHelper::map($list,'id','nama');
        return $listData;
    }

    public static function getListDepartemens()
    {
        $userPt = '';
               
        $userLevel = Yii::$app->user->identity->access_role;    
            
        $query=Departemen::find();
        if($userLevel != 'admin' && $userLevel == 'operatorCabang'){
            $userPt = Yii::$app->user->identity->perusahaan_id;

            $query->where('perusahaan_id = :p2' ,[':p2'=>$userPt]);
            // $where = array_merge($where,['perusahaan_id' => $userPt]);   
            
        }

        $list=$query->all();
        $listData=\yii\helpers\ArrayHelper::map($list,'id','nama');
        return $listData;
    }

    public static function getListLevels()
    {
        $userPt = '';
        

        $list=DepartemenLevel::find()->all();
        $listData=\yii\helpers\ArrayHelper::map($list,'id','nama');
        return $listData;
    } 

    public  function getNamaPerusahaan(){
        return $this->perusahaan->nama;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama Unit',
            'namaPerusahaan' => 'Universitas',
            'departemen_level_id' => 'Departemen Level ID',
            'perusahaan_id' => 'Perusahaan ID',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'tujuan' => 'Tujuan',
            'sasaran' => 'Sasaran',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemenLevel()
    {
        return $this->hasOne(DepartemenLevel::className(), ['id' => 'departemen_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaan()
    {
        return $this->hasOne(Perusahaan::className(), ['id_perusahaan' => 'perusahaan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemenUsers()
    {
        return $this->hasMany(DepartemenUser::className(), ['departemen_id' => 'id']);
    }
}
