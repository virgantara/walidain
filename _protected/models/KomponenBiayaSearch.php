<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KomponenBiaya;

/**
 * KomponenBiayaSearch represents the model behind the search form of `app\models\KomponenBiaya`.
 */
class KomponenBiayaSearch extends KomponenBiaya
{

    public $namaKategori;
    public $list_kampus;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'prioritas', 'kategori_id', 'tahun'], 'integer'],
            [['kode', 'nama', 'created_at', 'updated_at','namaKategori','biaya_minimal','kampus_id','bulan_id'], 'safe'],
            [['biaya_awal'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = KomponenBiaya::find();

        $query->joinWith(['kategori as k']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'tahun' => SORT_DESC
                ]
            ]
        ]);

        $dataProvider->sort->attributes['namaKategori'] = [
            'asc' => ['k.nama'=>SORT_ASC],
            'desc' => ['k.nama'=>SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->tahun))
        {
            $query->andWhere(['tahun'=>$this->tahun]);
        }

        if(!empty($this->kategori_id))
        {
            $query->andWhere(['kategori_id'=>$this->kategori_id]);
        }

        if(!empty($this->bulan_id))
        {
            $query->andWhere(['bulan_id'=>$this->bulan_id]);
        }

        if(!empty($this->kampus_id))
        {
            $query->andWhere(['kampus_id'=>$this->kampus_id]);
        }

        if(!empty($this->list_kampus))
        {
            // $list_kampus = explode(',', Yii::$app->user->identity->kampus);
            $query->andWhere(['IN','kampus',$list_kampus]);
            // $query->andFilterWhere(['or',
            //     ['kampus'=>Yii::$app->user->identity->kampus],
            //     ['kampus'=>Yii::$app->user->identity->kampus2]
                
            // ]);
        }


        $query->andFilterWhere(['like', self::tableName().'kode', $this->kode])
            ->andFilterWhere(['like', self::tableName().'.nama', $this->nama]);
            // ->andFilterWhere(['like', 'k.nama', $this->namaKategori]);

        return $dataProvider;
    }

    public static function getKategoriList()
    {
        $list = \yii\helpers\ArrayHelper::map(Kategori::find()->all(),'id','nama');

        return $list;
    }

    public static function getKampusList()
    {
        $list = \yii\helpers\ArrayHelper::map(SimakKampus::find()->all(),'kode_kampus','nama_kampus');

        return $list;
    }

    public static function getBulanList()
    {
        $list = \yii\helpers\ArrayHelper::map(Bulan::find()->all(),'id','nama');

        return $list;
    }

    public static function getTahunList()
    {
        $list = \yii\helpers\ArrayHelper::map(Tahun::find()->orderBy(['id'=>SORT_DESC])->all(),'id','nama');

        return $list;
    }
}
