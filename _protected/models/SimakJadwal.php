<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_jadwal".
 *
 * @property int $id
 * @property string $hari
 * @property string $jam
 * @property string $kode_mk
 * @property int|null $matkul_id
 * @property string $kode_dosen
 * @property string $semester
 * @property string $kelas
 * @property string|null $fakultas
 * @property string|null $prodi
 * @property string $kd_ruangan
 * @property string|null $tahun_akademik
 * @property int|null $kuota_kelas
 * @property string|null $kampus
 * @property string|null $presensi
 * @property string|null $materi
 * @property string|null $bobot_formatif
 * @property string|null $bobot_uts
 * @property string|null $bobot_uas
 * @property string|null $bobot_harian1
 * @property string|null $bobot_harian
 * @property int|null $jadwal_temp_id
 * @property int|null $jumlah_tatap_muka
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property MHari $hari0
 * @property SimakKampus $kampus0
 * @property SimakMastermatakuliah $matkul
 * @property SimakMasterprogramstudi $prodi0
 * @property SimakAbsenHarian[] $simakAbsenHarians
 * @property SimakDatakrs[] $simakDatakrs
 */
class SimakJadwal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_jadwal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hari', 'jam', 'kode_mk', 'kode_dosen','kode_pengampu_nidn', 'semester', 'kelas', 'kd_ruangan'], 'required'],
            [['jam', 'created_at', 'updated_at','kode_feeder','a_selenggara_pditt','bahasan_case','lingkup_kelas','mode_kuliah','tgl_mulai_koas','tgl_selesai_koas','classroom_id','alternateLink','tgl_tutup_daftar','kapasitas'], 'safe'],
            [['matkul_id', 'kuota_kelas', 'jadwal_temp_id', 'jumlah_tatap_muka'], 'integer'],
            [['presensi'], 'string'],
            
            [['hari'], 'string', 'max' => 30],
            [['bobot_harian','bobot_formatif', 'bobot_uts', 'bobot_uas'],'number'],
            [['kode_mk', 'kode_dosen', 'kd_ruangan'], 'string', 'max' => 20],
            [['semester'], 'string', 'max' => 5],
            [['kelas', 'prodi', 'tahun_akademik'], 'string', 'max' => 10],
            [['fakultas'], 'string', 'max' => 7],
            [['kampus'], 'string', 'max' => 2],
            [['materi','bahasan_case'], 'string', 'max' => 200,'message'=>'{attribute} maksimal 200 karakter'],
            [['bobot_harian1', 'bobot_harian'], 'string', 'max' => 4],
            [['matkul_id'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMatakuliah::className(), 'targetAttribute' => ['matkul_id' => 'id']],
            [['prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['prodi' => 'kode_prodi']],
            [['kampus'], 'exist', 'skipOnError' => true, 'targetClass' => SimakKampus::className(), 'targetAttribute' => ['kampus' => 'kode_kampus']],
            [['hari'], 'exist', 'skipOnError' => true, 'targetClass' => MHari::className(), 'targetAttribute' => ['hari' => 'nama_hari']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hari' => 'Hari',
            'enrollment_code' => 'Enrollment Code',
            'a_selenggara_pditt' => 'Kelas Kampus Merdeka',
            'bahasan_case' => 'Bahasan',
            'tgl_mulai_koas' => 'Tgl Mulai Efektif',
            'tgl_selesai_koas' => 'Tgl Selesai Efektif', 
            'jam' => 'Jam',
            'kode_mk' => 'Kode Mk',
            'lingkup_kelas' => 'Lingkup Kelas',
            'mode_kuliah' => 'Mode Perkuliahan',
            'matkul_id' => 'Matkul ID',
            'kode_dosen' => 'Kode Dosen',
            'kode_pengampu_nidn' => 'Kode Dosen Ber-NIDN',
            'semester' => 'Semester',
            'kelas' => 'Kelas',
            'fakultas' => 'Fakultas',
            'prodi' => 'Prodi',
            'kd_ruangan' => 'Kd Ruangan',
            'tahun_akademik' => 'Tahun Akademik',
            'kuota_kelas' => 'Kuota Kelas',
            'kampus' => 'Kampus',
            'presensi' => 'Presensi',
            'materi' => 'Materi',
            'bobot_formatif' => 'Bobot Formatif',
            'bobot_uts' => 'Bobot Uts',
            'bobot_uas' => 'Bobot Uas',
            'bobot_harian1' => 'Bobot Harian1',
            'bobot_harian' => 'Bobot Harian',
            'jadwal_temp_id' => 'Jadwal Temp ID',
            'jumlah_tatap_muka' => 'Jumlah Tatap Muka',
            'countPeserta' => 'Peserta Kelas',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'tgl_tutup_daftar' => 'Tanggal Tutup Daftar',
            'kapasitas' => 'Kapasitas'
        ];
    }

     /**
     * Gets query for [[SimakTopiks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTopiks()
    {
        return $this->hasMany(SimakTopik::className(), ['jadwal_id' => 'id']);
    }

    /**
     * Gets query for [[SimakMateris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMateris()
    {
        return $this->hasMany(SimakMateri::className(), ['jadwal_id' => 'id']);
    }

    /**
     * Gets query for [[Hari0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHari0()
    {
        return $this->hasOne(MHari::className(), ['nama_hari' => 'hari']);
    }

    /**
     * Gets query for [[Kampus0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKampus0()
    {
        return $this->hasOne(SimakKampus::className(), ['kode_kampus' => 'kampus']);
    }

    /**
     * Gets query for [[Matkul]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMatkul()
    {
        return $this->hasOne(SimakMatakuliah::className(), ['id' => 'matkul_id']);
    }

    public function getNamaMatkul()
    {
        return !empty($this->matkul) ? $this->matkul->nama_mk : null;
    }

    /**
     * Gets query for [[Prodi0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdi0()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'prodi']);
    }

    /**
     * Gets query for [[SimakAbsenHarians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakAbsenHarians()
    {
        return $this->hasMany(SimakAbsenHarian::className(), ['kode_jadwal' => 'id']);
    }

    /**
     * Gets query for [[SimakDatakrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakDatakrs()
    {
        return $this->hasMany(SimakDatakrs::className(), ['kode_jadwal' => 'id']);
    }

    public function getListPeserta()
    {
        return $this->hasMany(SimakDatakrs::className(), ['kode_jadwal' => 'id'])->joinWith(['mahasiswa0 as mhs'])->orderBy(['mhs.semester'=>SORT_DESC,'mhs.nama_mahasiswa'=>SORT_ASC]);
    }

    public function getCountPeserta(){
        return $this->hasMany(SimakDatakrs::className(), ['kode_jadwal' => 'id'])->count();
    }

    public function getPembelajarans()
    {
        return $this->hasMany(SimakPembelajaran::className(), ['jadwal_id' => 'id']);
    }

    public function getNamaDosen()
    {
        $dosen = SimakMasterdosen::find()->select(['nama_dosen'])->where(['nidn'=>$this->kode_dosen])->asArray()->one();
        return !empty($dosen) ? $dosen['nama_dosen'] : 'not found';
    }

    public function getNamaDosenNIDN()
    {
        $dosen = SimakMasterdosen::find()->select(['nama_dosen'])->where(['nidn'=>$this->kode_pengampu_nidn])->asArray()->one();
        return !empty($dosen) ? $dosen['nama_dosen'] : 'not found';
    }

    public function getBobotSks()
    {
        $mk = SimakMatakuliah::find()->select(['sks_mk'])->where(['kode_mk'=>$this->kode_mk])->asArray()->one();
        return !empty($mk) ? $mk['sks_mk'] : 'not found';
    }

    public function getNamaMk()
    {
        $list_mk = SimakMatakuliah::find()->where(['kode_mk'=>$this->kode_mk])->all();

        $results = [];

        foreach($list_mk as $mk){
            $results[] = $mk->nama_mk;
        }

        return !empty($results) ? implode(", ",$results) : 'not found';
    }

    /**
     * Gets query for [[SimakTugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTugas()
    {
        return $this->hasMany(SimakTugas::className(), ['jadwal_id' => 'id'])->orderBy(['created_at'=>SORT_ASC]);
    }

    public function getNamaKelas()
    {
        $nama_kelas = $this->kelas;
        $label = $nama_kelas;

        return $label;
    }

}
