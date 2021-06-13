<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tagihan;

/**
 * TagihanSearch represents the model behind the search form of `app\models\Tagihan`.
 */
class TagihanSearch extends Tagihan
{

    public $tanggal_awal;
    public $tanggal_akhir;
    public $namaKomponen;
    public $namaCustomer;
    public $namaProdi;
    public $namaKampus;
    public $namaTahun;
    public $namaSemester;
    public $excludeWisuda;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'semester', 'tahun', 'komponen_id', 'edit', 'status_bayar'], 'integer'],
            [['nim', 'created_at', 'updated_at','namaKomponen','namaCustomer','namaProdi','namaKampus','namaTahun','nilai_minimal','namaSemester'], 'safe'],
            [['nilai', 'terbayar'], 'number'],
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

    public function searchManual()
    {
        $query = Tagihan::find();
        $query->joinWith([
            'komponen as k',
            'nim0 as c',
            'tahun0 as t',
            'nim0.kodeProdi as p',
            'nim0.kampus0 as kps'
        ]);

        // grid filtering conditions
        $query->andWhere([
            'k.tahun' => $this->tahun,
            'p.kode_prodi' => $this->namaProdi,
            'c.kampus' => $this->namaKampus,
            'c.status_aktivitas' => 'A'
        ]);

        $query->andWhere('terbayar < nilai');

        if($this->excludeWisuda == 1)
        {
            $query->andWhere(['NOT LIKE','k.kode','06%',false]);
        }

        $query->orderBy(['c.nama_mahasiswa'=>SORT_ASC]);
            

        return $query->all();
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
        $query = Tagihan::find();
        $query->joinWith([
            'komponen as k',
            'nim0 as c',
            'tahun0 as t',
            'nim0.kodeProdi as p',
            'nim0.kampus0 as kps'
        ]);

        $tahun = Tahun::getTahunAktif();
        $this->tahun = $tahun->id;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['namaKomponen'] = [
            'asc' => ['k.nama'=>SORT_ASC],
            'desc' => ['k.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaCustomer'] = [
            'asc' => ['c.nama_mahasiswa'=>SORT_ASC],
            'desc' => ['c.nama_mahasiswa'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['p.nama_prodi'=>SORT_ASC],
            'desc' => ['p.nama_prodi'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['kps.nama_kampus'=>SORT_ASC],
            'desc' => ['kps.nama_kampus'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaTahun'] = [
            'asc' => ['t.nama'=>SORT_ASC],
            'desc' => ['t.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaSemester'] = [
            'asc' => ['c.semester'=>SORT_ASC],
            'desc' => ['c.semester'=>SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // if(!empty($this->namaKampus))
        $query->andFilterWhere(['c.kampus'=> $this->namaKampus]);

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.semester' => $this->semester,    
            self::tableName().'.urutan' => $this->urutan,
        ]);


        $query->andWhere([self::tableName().'.tahun' => $tahun->id]);

        
        if(!empty($this->komponen_id))
        {
            $query->andWhere(['komponen_id'=>$this->komponen_id]);
        }

        if(!empty($this->namaSemester))
        {
            $query->andWhere(['c.semester'=>$this->namaSemester]);
        }

        if(!empty($this->status_bayar))
        {
            switch ($this->status_bayar) {
                case 1:
                    $query->andWhere('terbayar >= nilai');
                    break;
                case 2:
                    $query->andWhere('terbayar > 0 AND terbayar < nilai');
                    break;
                case 3:
                    $query->andWhere('terbayar = 0');
                    break;
                
            }
        }

        $query->andFilterWhere(['p.kode_prodi'=> $this->namaProdi]);

        if(Yii::$app->user->identity->access_role == 'admin')
        {
            $list_kampus = explode(',', Yii::$app->user->identity->kampus);
            $query->andWhere(['IN','c.kampus',$list_kampus]);
            
        }

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'c.nama_mahasiswa', $this->namaCustomer])
            
            
            ->andFilterWhere(['like', 't.nama', $this->namaTahun]);

        return $dataProvider;
    }

    public function searchRiwayat($params)
    {
        $query = Tagihan::find();
        $query->joinWith([
            'komponen as k',
            'nim0 as c',
            'tahun0 as t',
            'nim0.kodeProdi as p',
            'nim0.kampus0 as kps'
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['namaKomponen'] = [
            'asc' => ['k.nama'=>SORT_ASC],
            'desc' => ['k.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaCustomer'] = [
            'asc' => ['c.nama_mahasiswa'=>SORT_ASC],
            'desc' => ['c.nama_mahasiswa'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['p.nama_prodi'=>SORT_ASC],
            'desc' => ['p.nama_prodi'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['kps.nama_kampus'=>SORT_ASC],
            'desc' => ['kps.nama_kampus'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaTahun'] = [
            'asc' => ['t.nama'=>SORT_ASC],
            'desc' => ['t.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaSemester'] = [
            'asc' => ['c.semester'=>SORT_ASC],
            'desc' => ['c.semester'=>SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

     
        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.semester' => $this->semester,    
            self::tableName().'.urutan' => $this->urutan,
        ]);

        if(!empty($this->tahun))
            $query->andWhere([self::tableName().'.tahun' => $this->tahun]);

        

        if(!empty($this->komponen_id))
        {
            $query->andWhere(['komponen_id'=>$this->komponen_id]);
        }

        if(!empty($this->namaSemester))
        {
            $query->andWhere(['c.semester'=>$this->namaSemester]);
        }

        if(!empty($this->status_bayar))
        {
            switch ($this->status_bayar) {
                case 1:
                    $query->andWhere('terbayar >= nilai');
                    break;
                case 2:
                    $query->andWhere('terbayar > 0 AND terbayar < nilai');
                    break;
                case 3:
                    $query->andWhere('terbayar = 0');
                    break;
                
            }
        }

        $query->andFilterWhere(['p.kode_prodi'=> $this->namaProdi]);
        $query->andFilterWhere(['c.kampus'=> $this->namaKampus]);

        if(Yii::$app->user->identity->access_role == 'admin')
        {
            $list_kampus = explode(',', Yii::$app->user->identity->kampus);
            $query->andWhere(['IN','c.kampus',$list_kampus]);
            
        }

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'c.nama_mahasiswa', $this->namaCustomer])
            
            ->andFilterWhere(['like', 't.nama', $this->namaTahun]);

        return $dataProvider;
    }
}
