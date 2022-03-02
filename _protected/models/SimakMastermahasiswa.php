<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%simak_mastermahasiswa}}".
 *
 * @property int $id
 * @property string|null $kode_pt
 * @property string|null $kode_fakultas
 * @property string|null $kode_prodi
 * @property string|null $kode_jenjang_studi
 * @property string $nim_mhs
 * @property string $nama_mahasiswa
 * @property string|null $tempat_lahir
 * @property string|null $tgl_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $tahun_masuk
 * @property string|null $semester_awal
 * @property string|null $batas_studi
 * @property string|null $asal_propinsi
 * @property string|null $tgl_masuk
 * @property string|null $tgl_lulus
 * @property string|null $status_aktivitas
 * @property string|null $status_awal
 * @property string|null $jml_sks_diakui
 * @property string|null $nim_asal
 * @property string|null $asal_pt
 * @property string|null $nama_asal_pt
 * @property string|null $asal_jenjang_studi
 * @property string|null $asal_prodi
 * @property string|null $kode_biaya_studi
 * @property string|null $kode_pekerjaan
 * @property string|null $tempat_kerja
 * @property string|null $kode_pt_kerja
 * @property string|null $kode_ps_kerja
 * @property string|null $nip_promotor
 * @property string|null $nip_co_promotor1
 * @property string|null $nip_co_promotor2
 * @property string|null $nip_co_promotor3
 * @property string|null $nip_co_promotor4
 * @property string|null $photo_mahasiswa
 * @property string|null $semester
 * @property string|null $keterangan
 * @property int|null $status_bayar
 * @property string|null $telepon
 * @property string|null $hp
 * @property string|null $email
 * @property string|null $alamat
 * @property string|null $berat
 * @property string|null $tinggi
 * @property string|null $ktp
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $dusun
 * @property string|null $kode_pos
 * @property string|null $desa
 * @property string|null $kecamatan
 * @property string|null $kecamatan_feeder
 * @property string|null $jenis_tinggal
 * @property string|null $penerima_kps
 * @property string|null $no_kps
 * @property string|null $provinsi
 * @property string|null $kabupaten
 * @property string|null $status_warga
 * @property string|null $warga_negara
 * @property string|null $warga_negara_feeder
 * @property string|null $status_sipil
 * @property string|null $agama
 * @property string|null $gol_darah
 * @property string|null $masuk_kelas
 * @property string|null $no_sk_yudisium
 * @property string|null $tgl_sk_yudisium
 * @property string|null $no_ijazah
 * @property int|null $status_mahasiswa 1 Reguler, 2 Intensif
 * @property string|null $kampus
 * @property string|null $jur_thn_smta
 * @property int|null $is_synced
 * @property string|null $kode_pd
 * @property string|null $va_code
 * @property int|null $is_eligible
 * @property int|null $kamar_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BillTagihan[] $billTagihans
 * @property BillTransaksi[] $billTransaksis
 * @property ErpIzinMahasiswa[] $erpIzinMahasiswas
 * @property ErpRiwayatKamar[] $erpRiwayatKamars
 * @property ErpRiwayatPelanggaran[] $erpRiwayatPelanggarans
 * @property SimakKegiatanMahasiswa[] $simakKegiatanMahasiswas
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus
 * @property SimakMahasiswaProgramTambahan[] $simakMahasiswaProgramTambahans
 * @property SimakMasterprogramstudi $kodeProdi
 * @property SimakKampus $kampus0
 * @property SimakPencekalan[] $simakPencekalans
 * @property SimakTahfidzKelompokAnggota[] $simakTahfidzKelompokAnggotas
 * @property SimakTahfidzNilai[] $simakTahfidzNilais
 */
class SimakMastermahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $tahun_id;

    public static function tableName()
    {
        return '{{%simak_mastermahasiswa}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nim_mhs', 'nama_mahasiswa','tahun_masuk','semester_awal','kode_prodi','kampus','jenis_kelamin','ktp','email','semester','tempat_lahir'], 'required'],
            [['tgl_lahir', 'tgl_masuk', 'tgl_lulus', 'tgl_sk_yudisium', 'created_at', 'updated_at','judul_skripsi','judul_skripsi_en','konsulat','pmb_id','foto_path','no_transkrip','nina','judul_skripsi_ar','kode_pd','id_jalur_daftar','id_jenis_daftar','biaya_awal','id_pembiayaan','id_reg_pd','is_accept_term','passport_no','passport_issue_date','visa_no','visa_issue_date','passport_exp_date','visa_exp_date','tgl_sk_pembimbingan_tesis','no_sk_pembimbingan_tesis','apakah_4_tahun'], 'safe'],
            [['keterangan'], 'string'],
            [['foto_path'],'required','on' => 'upload_foto'],
            [['foto_path'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, bmp','maxSize' => 1024 * 1024 / 2],
            [['status_bayar', 'status_mahasiswa', 'is_synced', 'is_eligible', 'kamar_id'], 'integer'],
            [['kode_pt', 'asal_prodi', 'kode_pos'], 'string', 'max' => 6],
            [['kode_fakultas', 'kode_prodi', 'kode_jenjang_studi', 'jenis_kelamin', 'semester_awal', 'batas_studi', 'status_awal', 'asal_jenjang_studi', 'rt', 'rw'], 'string', 'max' => 5],
            [['nim_mhs', 'nama_asal_pt', 'telepon', 'hp'], 'string', 'max' => 25],
            [['nama_mahasiswa', 'dusun', 'desa', 'kecamatan', 'warga_negara', 'status_sipil', 'jur_thn_smta', 'kode_pd'], 'string', 'max' => 100],
            [['tempat_lahir', 'asal_propinsi', 'status_aktivitas', 'email', 'status_warga'], 'string', 'max' => 50],
            // [['tahun_masuk'], 'string', 'max' => 4],
            [['jml_sks_diakui'], 'string', 'max' => 45],
            [['nina'], 'string', 'max' => 15],
            [['tgl_sk_yudisium'], 'date', 'format' => 'php:Y-m-d'],
            [['nim_asal', 'kode_biaya_studi', 'kode_pekerjaan', 'tempat_kerja', 'kode_pt_kerja'], 'string', 'max' => 55],
            [['asal_pt', 'ktp'], 'string', 'max' => 30],
            [['kode_ps_kerja', 'nip_co_promotor4'], 'string', 'max' => 44],
            [['nip_co_promotor3'], 'string', 'max' => 33],
            [['photo_mahasiswa', 'alamat', 'kecamatan_feeder', 'provinsi', 'kabupaten', 'warga_negara_feeder', 'no_sk_yudisium', 'no_ijazah'], 'string', 'max' => 255],
            [['berat', 'tinggi'], 'string', 'max' => 3],
            [['jenis_tinggal', 'no_kps', 'agama', 'va_code'], 'string', 'max' => 20],
            [['penerima_kps', 'masuk_kelas'], 'string', 'max' => 1],
            [['gol_darah', 'kampus'], 'string', 'max' => 2],
            [['nim_mhs'], 'unique'],
            [['nama_mahasiswa','tgl_lahir','ktp','kode_prodi'], 'unique','on'=>'insert'],
            [['kode_prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['kode_prodi' => 'kode_prodi']],
            [['kampus'], 'exist', 'skipOnError' => true, 'targetClass' => SimakKampus::className(), 'targetAttribute' => ['kampus' => 'kode_kampus']],
            [['nip_promotor'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterdosen::className(), 'targetAttribute' => ['nip_promotor' => 'id']],
            [['nip_co_promotor1'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterdosen::className(), 'targetAttribute' => ['nip_co_promotor1' => 'id']],
            [['nip_co_promotor2'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterdosen::className(), 'targetAttribute' => ['nip_co_promotor2' => 'id']],
            [['konsulat'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['konsulat' => 'id']],
            [['dapur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dapur::className(), 'targetAttribute' => ['dapur_id' => 'id']],
            [['kamar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ErpKamar::className(), 'targetAttribute' => ['kamar_id' => 'id']],
            [['id_jenis_daftar'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['id_jenis_daftar' => 'id']],
            [['id_jalur_daftar'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['id_jalur_daftar' => 'id']],
            [['id_pembiayaan'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['id_pembiayaan' => 'id']],
             [['warga_negara'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['warga_negara' => 'iso2']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_pt' => 'Kode Pt',
            'kode_fakultas' => 'Kode Fakultas',
            'kode_prodi' => 'Kode Prodi',
            'kode_jenjang_studi' => 'Kode Jenjang Studi',
            'nim_mhs' => 'NIM',
            'nama_mahasiswa' => 'Nama Mahasiswa',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tahun_masuk' => 'Angkatan',
            'semester_awal' => 'Semester Awal',
            'nina' => 'Nomor Ijazah Nasional',
            'batas_studi' => 'Batas Studi',
            'asal_propinsi' => 'Asal Propinsi',
            'tgl_masuk' => 'Tgl Masuk',
            'tgl_lulus' => 'Tgl Lulus',
            'status_aktivitas' => 'Status Aktivitas',
            'status_awal' => 'Status Awal',
            'jml_sks_diakui' => 'Jml Sks Diakui',
            'nim_asal' => 'Nim Asal',
            'asal_pt' => 'Asal Pt',
            'nama_asal_pt' => 'Nama Asal Pt',
            'asal_jenjang_studi' => 'Asal Jenjang Studi',
            'asal_prodi' => 'Asal Prodi',
            'kode_biaya_studi' => 'Kode Biaya Studi',
            'kode_pekerjaan' => 'Kode Pekerjaan',
            'tempat_kerja' => 'Tempat Kerja',
            'kode_pt_kerja' => 'Kode Pt Kerja',
            'kode_ps_kerja' => 'Kode Ps Kerja',
            'nip_promotor' => 'Dosen PA',
            'nip_co_promotor1' => 'Pembimbing 1',
            'nip_co_promotor2' => 'Pembimbing 2',
            'nip_co_promotor3' => 'Nip Co Promotor3',
            'nip_co_promotor4' => 'Nip Co Promotor4',
            'photo_mahasiswa' => 'Photo Mahasiswa',
            'semester' => 'Semester',
            'keterangan' => 'Keterangan',
            'status_bayar' => 'Status Bayar',
            'telepon' => 'Telepon',
            'hp' => 'Hp',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'berat' => 'Berat',
            'tinggi' => 'Tinggi',
            'ktp' => 'Ktp',
            'rt' => 'Rt',
            'rw' => 'Rw',
            'dusun' => 'Dusun',
            'kode_pos' => 'Kode Pos',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'kecamatan_feeder' => 'Kecamatan Feeder',
            'jenis_tinggal' => 'Jenis Tinggal',
            'penerima_kps' => 'Penerima Kps',
            'no_kps' => 'No Kps',
            'provinsi' => 'Provinsi',
            'kabupaten' => 'Kabupaten',
            'status_warga' => 'Status Warga',
            'warga_negara' => 'Warga Negara',
            'warga_negara_feeder' => 'Warga Negara Feeder',
            'status_sipil' => 'Status Sipil',
            'agama' => 'Agama',
            'gol_darah' => 'Gol Darah',
            'masuk_kelas' => 'Masuk Kelas',
            'no_sk_yudisium' => 'No SK Yudisium',
            'tgl_sk_yudisium' => 'Tanggal SK Yudisium',
            'tgl_sk_pembimbingan_tesis' => 'Tanggal SK Pembimbingan',
            'no_sk_pembimbingan_tesis' => 'No SK Pembimbingan',
            'no_ijazah' => 'No Ijazah',
            'status_mahasiswa' => 'Status Mahasiswa',
            'kampus' => 'Kampus',
            'jur_thn_smta' => 'Jur Thn Smta',
            'is_synced' => 'Is Synced',
            'va_code' => 'Va Code',
            'is_eligible' => 'Is Eligible',
            'kamar_id' => 'Kamar',
            'order_mount' => Yii::t('app', 'Order Amount'),
            'foto_path' => 'Profil',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'id_jenis_daftar' => 'Jenis Daftar',
            'id_jalur_daftar' => 'Jalur Daftar' ,           
            'id_pembiayaan' => 'Jenis Pembiayaan',
            'id_reg_pd' => 'Kode Registrasi FEEDER',
            'kode_pd' => 'Kode FEEDER',
            'apakah_4_tahun' => 'Lama Rencana Studi'
        ];
    }

    public function getNamaWali()
    {

        $results = null;

        foreach($this->simakMahasiswaOrtus as $ortu) {
            if($ortu->hubungan == 'WALI'){
                $results = ucwords(strtolower($ortu->nama));
                break;
            }

            else if($ortu->hubungan == 'AYAH'){
                $results = ucwords(strtolower($ortu->nama));
                break;
            }
        }


        return $results;
    }

    public function getAlamatWali()
    {

        $results = null;

        foreach($this->simakMahasiswaOrtus as $ortu) {
            if($ortu->hubungan == 'WALI'){
                $results = ucwords(strtolower($ortu->alamat));
                break;
            }

            else if($ortu->hubungan == 'AYAH'){
                $results = ucwords(strtolower($ortu->alamat));
                break;
            }
        }


        return $results;
    }

    public function getInvalidAttributes()
    {
        // $list_mhsvars = ['id_reg_pd','kode_pd','id_jalur_daftar','id_jenis_daftar','id_pembiayaan','tgl_masuk','biaya_awal'];
        $list_mhsvars = ['id_reg_pd','kode_pd'];
        $header = '<ul>';
        $footer = '</ul>';
        $result = '';

        $list_status = ['A','C','N'];

        if($this->apakah_4_tahun == '1' && in_array($this->status_aktivitas,$list_status)){
            foreach($this->attributes as $q => $attr){
                if(in_array($q,$list_mhsvars)){
                    if(!isset($attr)){
                        $result .= '<li style="color:red;font-style:italic">'.$this->getAttributeLabel($q). ' belum diisi. Klik di '.\yii\helpers\Html::a('sini',['simak-mastermahasiswa/update','id'=>$this->id],['data-pjax'=>0,'target'=>'_blank']).' untuk mengubah</li>';

                    }
                }
            }
        }

        // $is_ayah_empty = true;
        $is_ibu_empty = true;
        // foreach($this->simakMahasiswaOrtus as $ortu) {
        //     // if($ortu->hubungan == 'AYAH')
        //     //     $is_ayah_empty = false;

        //     if($ortu->hubungan == 'IBU')
        //         $is_ibu_empty = false;            
        // }

        

        // if($is_ayah_empty)
        //     $result .= "<li style=\"color:red;font-style:italic\">Data AYAH belum diisi</li>";

        // if($is_ibu_empty)
        //     $result .= "<li style=\"color:red;font-style:italic\">Data IBU belum diisi</li>";

        if(!empty($result)){
            $result = $header.$result.$footer;
        }
        return $result;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->nama_mahasiswa = strtoupper($this->nama_mahasiswa);
    }

    public function getDosenPembimbing1()
    {
        return !empty($this->nipCoPromotor1) ? $this->nipCoPromotor1->nama_dosen : '';
    }

    public function getDosenPembimbing2()
    {
        return !empty($this->nipCoPromotor2) ? $this->nipCoPromotor2->nama_dosen : '';
    }

     public function getPembiayaan()
    {
        return $this->hasOne(SimakPilihan::className(), ['id' => 'id_pembiayaan']);
    }

    public function getJalurDaftar()
    {
        return $this->hasOne(SimakPilihan::className(), ['id' => 'id_jalur_daftar']);
    }

    /**
     * Gets query for [[JenisDaftar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisDaftar()
    {
        return $this->hasOne(SimakPilihan::className(), ['id' => 'id_jenis_daftar']);
    }

    public function getKamar()
    {
        return $this->hasOne(ErpKamar::className(), ['id' => 'kamar_id']);
    }

    public function getDapur()
    {
        return $this->hasOne(Dapur::className(), ['id' => 'dapur_id']);
    }

    public function getKonsulat0()
    {
        return $this->hasOne(Cities::className(), ['id' => 'konsulat']);
    }

    /**
     * Gets query for [[BillTagihans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillTagihans()
    {
        return $this->hasMany(BillTagihan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[BillTransaksis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillTransaksis()
    {
        return $this->hasMany(BillTransaksi::className(), ['CUSTID' => 'nim_mhs']);
    }

    /**
     * Gets query for [[ErpIzinMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpIzinMahasiswas()
    {
        return $this->hasMany(ErpIzinMahasiswa::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[ErpRiwayatKamars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatKamars()
    {
        return $this->hasMany(ErpRiwayatKamar::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[ErpRiwayatPelanggarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatPelanggarans()
    {
        return $this->hasMany(ErpRiwayatPelanggaran::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[SimakKegiatanMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakKegiatanMahasiswas()
    {
        return $this->hasMany(SimakKegiatanMahasiswa::className(), ['nim' => 'nim_mhs']);
    }
    public function getKodeOrtu()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['nim' => 'nim_mhs']);
    }
    /**
     * Gets query for [[SimakMahasiswaOrtus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['nim' => 'nim_mhs']);
    }

    public function getOrtuAyah()
    {
        return $this->getKodeOrtu()->where(['hubungan' => 'ayah']);
    }
    public function getOrtuIbu()
    {
        return $this->getKodeOrtu()->where(['hubungan' => 'ibu']);
    }
    public function getOrtuWali()
    {
        return $this->getKodeOrtu()->where(['hubungan' => 'wali']);
    }
    /**
     * Gets query for [[SimakMahasiswaProgramTambahans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaProgramTambahans()
    {
        return $this->hasMany(SimakMahasiswaProgramTambahan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[KodeProdi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKodeProdi()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'kode_prodi']);
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
     * Gets query for [[SimakPencekalans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakPencekalans()
    {
        return $this->hasMany(SimakPencekalan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[SimakTahfidzKelompokAnggotas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTahfidzKelompokAnggotas()
    {
        return $this->hasMany(SimakTahfidzKelompokAnggota::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * Gets query for [[SimakTahfidzNilais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTahfidzNilais()
    {
        return $this->hasMany(SimakTahfidzNilai::className(), ['nim' => 'nim_mhs']);
    }

    public function getNipPromotor()
    {
        return $this->hasOne(SimakMasterdosen::className(), ['id' => 'nip_promotor']);
    }

    public function getNipCoPromotor1()
    {
        return $this->hasOne(SimakMasterdosen::className(), ['id' => 'nip_co_promotor1']);
    }

    public function getSimakHistoryPendidikans()
    {
        return $this->hasMany(SimakHistoryPendidikan::className(), ['mahasiswa_id' => 'id']);
    }

    /**
     * Gets query for [[NipCoPromotor2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNipCoPromotor2()
    {
        return $this->hasOne(SimakMasterdosen::className(), ['id' => 'nip_co_promotor2']);
    }

    public function getTotalNilai()
    {
        return $this->hasMany(SimakKegiatanMahasiswa::className(), ['nim' => 'nim_mhs'])->sum('totalNilai');
    }

    public function getNamaPembimbingAkademik()
    {
        return !empty($this->nipPromotor) ? $this->nipPromotor->nama_dosen : '';
    }

    public function getWargaNegara()
    {
        return $this->hasOne(Countries::className(), ['iso2' => 'warga_negara']);
    }

    public static function isTercekalADM($nim)
    {
        $query = BillTagihan::find();
        $query->joinWith(['komponen as k']);
        $query->where([
            'k.is_pencekalan' => '1',
            'nim' => $nim
        ]);

        $query->andWhere('terbayar < nilai');
        return $query->count() > 0;

    }

    public function hitungRekapAkpam($semester)
    {
        $hsl = '';
        
            
        $errors = '';
        $transaction = \Yii::$app->db->beginTransaction();
        try 
        {

            $jenis_kegiatan = SimakJenisKegiatan::find()->all();

            foreach ($jenis_kegiatan as $key => $value) 
            {
                $rekap = SimakRekapAkpam::find()->where([
                    'nim'=>$this->nim_mhs,
                    'semester'=>$semester,
                    'id_jenis_kegiatan' => $value->id
                ])->one();

                if(empty($rekap)){
                    $rekap = new SimakRekapAkpam;
                    $rekap->nim = $this->nim_mhs;
                    $rekap->id_jenis_kegiatan = $value->id;

                }


                $query = SimakKegiatanMahasiswa::find()
                    ->where([
                        'nim'=>$this->nim_mhs,
                        'semester' => $semester,
                        'is_approved' => 1,
                        'id_jenis_kegiatan' => $value->id
                    ]);
                    

                $akpam = $query->sum('nilai');

                $rekap->semester = $semester;
                $rekap->akpam = !empty($akpam) ? $akpam : 0;

                if($rekap->validate())
                {
                    $rekap->save();
                    $transaction->commit();
                    $hsl = [
                        'code' => 200,
                        'message' => 'Data updated'
                    ];
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($rekap);
                    throw new \Exception;
                }
            }

            
        } catch (\Exception $e) {
            $errors .= $e->getMessage();
            
            $hsl = [
                'code' => 501,
                'message' => $errors
            ];
            $transaction->rollBack();
            
        } catch (\Throwable $e) {
            $errors .= $e->getMessage();
            
            $hsl = [
                'code' => 502,
                'message' => $errors
            ];
            $transaction->rollBack();
            
        }

        return $hsl;
    }

    public function labelStatus(){
        if ($this->status_aktivitas =='A') {
            return 'AKTIF';
        }elseif ($this->status_aktivitas =='C') {
            return 'CUTI';
        }elseif ($this->status_aktivitas =='D') {
            return 'DROP OUT / PUTUS STUDI';
        }elseif ($this->status_aktivitas =='K') {
            return 'KELUAR';
        }elseif ($this->status_aktivitas =='L') {
            return 'LULUS';
        }elseif ($this->status_aktivitas =='N') {
            return 'NON AKTIF';
        }elseif ($this->status_aktivitas =='G') {
            return 'SEDANG DOUBLE DEGREE';
        }
        elseif ($this->status_aktivitas =='M') {
            return 'MUTASI';
        }
        else{
            return $this->status_aktivitas;
        }
    }

    public function hitungIPSemester($semester)
    {
        $hsl = '';
        $query = SimakDatakrs::find()
            ->where([
                'mahasiswa'=>$this->nim_mhs,
                'semester' => $semester
            ])
            ->orderBy(['semester'=>SORT_ASC,'id'=>SORT_ASC]);

        $datakrs = $query->all();
            
        $hasil = [];
        $ipk = 0;
        foreach($datakrs as $d)
        {

            if(empty($d->nilai_huruf)) 
                continue;
            
            $hasil[$d->kode_mk] = [
                'nilai_huruf' => $d->nilai_huruf,
                'sks' => $d->sks,
                'bobot_sks' => $d->bobot_sks
            ];
        }

        // $results1 = [];
        // $results2 = [];
        // $results3 = [];
        $ipk = 0;
        $total_sks = 0;
        $bobot = 0;
        $i = 0;
        try{
            foreach($hasil as $tmp)
            {
                $sks = !empty($tmp['sks']) ? $tmp['sks'] : 0;
                $total_sks += (int)$sks;
                $bobot += $bobot_sks;
                
                $i++;
            }
        }   

        catch(\Exception $e)
        {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
            exit;
        }
        

        if($total_sks > 0)
        {
            $ipk = round($bobot / $total_sks,2);
        }
        
        return $ipk;
    }

    public function hitungAkpamSemester($semester)
    {
        $hsl = 0;
        
            
        $errors = '';
        

        $jenis_kegiatan = SimakJenisKegiatan::find()->all();

        foreach ($jenis_kegiatan as $key => $value) 
        {
            
            $query = SimakKegiatanMahasiswa::find()
                ->where([
                    'nim'=>$this->nim_mhs,
                    'semester' => $semester,
                    'is_approved' => 1,
                    'id_jenis_kegiatan' => $value->id
                ]);
                

            $akpam = $query->sum('nilai');

            $hsl = $akpam;
        }

            
       

        return $hsl;
    }

    
    public function getIPS($semester)
    {

        // $query = SimakRekapIp::find()
        // ->where([
        //     'nim'=>$this->nim_mhs,
        //     'semester' => $semester
        // ])->one();
     
       
        // return !empty($query) ? $query->ip : 0;

        // $ips = 0;
        $hasil = (new \yii\db\Query())
            ->select(['mahasiswa', 'semester','(SUM(bobot_sks) / SUM(sks)) as ips'])
            ->from('simak_datakrs d')
            ->where([
                'mahasiswa'=>$this->nim_mhs,
                'semester' => $semester
            ])
            ->andWhere(['not',['nilai_huruf' => null]])
            ->groupBy(['mahasiswa','semester'])
            ->orderBy(['mahasiswa'=>SORT_ASC,'semester'=>SORT_ASC])
            ->one();

        // $query = SimakDatakrs::find();
        // $query->select(['mahasiswa','semester','(SUM(bobot_sks) / SUM(sks)) as ips']);
        // $query->where([
        //     'mahasiswa'=>$this->nim_mhs,
        //     'semester' => $semester
        // ]);

        // $query->andWhere(['not',['nilai_huruf' => null]]);

        // $query->groupBy(['mahasiswa','semester']);
        // $query->orderBy(['mahasiswa'=>SORT_ASC,'semester'=>SORT_ASC]);

        // $hasil = $query->all();
        // print_r($hasil);exit;
        // $datakrs = $query->all();
        // $total_bobot = 0;
        // $total_sks = 0;
        // foreach($datakrs as $krs)
        // {
        //     $total_bobot += $krs->bobot_sks;
        //     $total_sks += $krs->sks;
        // }   

        $ips = !empty($hasil['ips']) ? $hasil['ips'] : null;

        return $ips;
    }

    public function getIPKS($semester, $id_jenis_kegiatan)
    {

        $query = SimakRekapAkpam::find()
        ->where([
            'nim'=>$this->nim_mhs,
            'semester' => $semester,
            'id_jenis_kegiatan' => $id_jenis_kegiatan
        ])->one();
     

        return !empty($query) ? $query->akpam : 0;
    }

   

    public function getSKSTempuh()
    {
        $query = SimakDatakrs::find()
            ->where(['mahasiswa'=>$this->nim_mhs])
            ->orderBy(['semester'=>SORT_ASC,'id'=>SORT_ASC]);

        $datakrs = $query->all();
            
        $hasil = [];
        $ipk = 0;
        foreach($datakrs as $d)
        {

            // if(empty($d->nilai_huruf)) 
            //     continue;
            $mk = SimakMastermatakuliah::find()
                ->where([
                    'kode_mata_kuliah' => $d->kode_mk,
                    'kode_prodi' => $this->kode_prodi,
                    'tahun_akademik' => $d->tahun_akademik
                ])
                ->orderBy(['semester'=>SORT_ASC])
                ->one();

            $hasil[$d->kode_mk] = [
                'kode_mk' => $mk->kode_mata_kuliah,
                'nama_mk' => $mk->nama_mata_kuliah,
                'nama_mk_en' => $mk->nama_mata_kuliah_en,
                'semester' => $mk->semester,
                'nilai_huruf' => $d->nilai_huruf,
                'sks' => $d->sks
            ];
        }

        $total_sks = 0;
        foreach($hasil as $tmp)
        {
            $total_sks += !empty($tmp['sks']) ? $tmp['sks'] : 0;
        }

        
        return $total_sks;
    }

    public function getSKSLulus()
    {
        $list_semester = \app\helpers\MyHelper::getSemester();
        $query = SimakDatakrs::find()
                ->where(['mahasiswa'=>$this->nim_mhs])
                ->orderBy(['id'=>SORT_ASC]);

        $datakrs = $query->all();
            
        $ipk = 0;
        $total_sks = 0;
        $bobot = 0;    
        $hasil = [];
        $results = [];
        foreach($datakrs as $d)
        {

            if($d->kode_mk == '-') 
                    continue;

            if(empty($d->nilai_huruf)) 
                continue;
            
            if(in_array($d->nilai_huruf,['D','E','F'])) 
                continue;

            $jadwal = SimakJadwal::findOne([
                'kode_mk' => $d->kode_mk,
                'prodi' => $this->kode_prodi,
                'tahun_akademik' => $d->tahun_akademik
            ]);

            $matkul = SimakMatakuliah::find()->where([
                    'kode_mk' => trim($d->kode_mk),
                    'prodi' => $this->kode_prodi
                ])->one();

            if(!empty($matkul) && $matkul->sks_mk != 0){
                $hasil[$d->kode_mk] = [
                    'kode_mk' => !empty($matkul) ? $matkul->kode_mk : '-',
                    'nama_mk' => !empty($matkul) ? $matkul->nama_mk : '-',
                    'nama_mk_en' => !empty($matkul) ? $matkul->nama_mk_en : '-',
                    'semester' => !empty($jadwal) ? $jadwal->semester : 0,
                    'nilai_huruf' => !empty($matkul) ? $d->nilai_huruf : null,
                    'sks' =>!empty($matkul) ? $matkul->sks_mk : 0
                ];
            }
        }

        $total_sks = 0;
        foreach($hasil as $tmp) {
            $results[$tmp['semester']][] = $tmp;
        }

        for($br=0;$br<4;$br++){
            foreach($list_semester[$br] as $semester) {
                if(empty($results[$semester])) continue;

                foreach($results[$semester] as $m) {
                    $m = (object) $m;
                    $total_sks += $m->sks;
                }

            }
        }
        
        
        
        return $total_sks;
    }

    public function getIpk()
    {   
        
        $ipk = 0;
        $query = SimakDatakrs::find()
                ->where(['mahasiswa'=>$this->nim_mhs])
                ->orderBy(['id'=>SORT_ASC]);

        $hasil = [];
        $datakrs = $query->all();
        foreach($datakrs as $d)
        {

            if($d->kode_mk == '-') 
                    continue;

            if(empty($d->nilai_huruf)) 
                continue;
            
            if(in_array($d->nilai_huruf,['D','E','F'])) 
                continue;

            $jadwal = SimakJadwal::findOne([
                'kode_mk' => $d->kode_mk,
                'prodi' => $this->kode_prodi,
                'tahun_akademik' => $d->tahun_akademik
            ]);

            $matkul = SimakMatakuliah::find()->where([
                'kode_mk' => trim($d->kode_mk),
                'prodi' => $this->kode_prodi
            ])->one();
            
            if(!empty($matkul) && $matkul->sks_mk != 0){
                $hasil[$d->kode_mk] = [
                    'kode_mk' => !empty($matkul) ? $matkul->kode_mk : ' tidak ada di Perkuliahan > Mata kuliah',
                    'nama_mk' => !empty($matkul) ? $matkul->nama_mk : ' tidak ada di Perkuliahan > Mata kuliah',
                    'nama_mk_en' => !empty($matkul) ? $matkul->nama_mk_en : ' tidak ada di Perkuliahan > Mata kuliah',
                    'semester' => !empty($jadwal) ? $jadwal->semester : '-',
                    'nilai_huruf' => !empty($matkul) ? $d->nilai_huruf : '-',
                    'sks' => !empty($matkul) ? $matkul->sks_mk : 0,
                    'bobot_sks' => !empty($d) ? $d->bobot_sks : 0,
                ];
            }
            // $hasil[$d->kode_mk] = [
            //     'semester' => !empty($d->kodeJadwal) ? $d->kodeJadwal->semester : '-',
            //     'sks' => !empty($matkul) ? $matkul->sks_mk : 0,
            //     'bobot_sks' => !empty($matkul) ? $d->bobot_sks : 0
            // ];
        }

        $results = [];
        
        $total_sks = 0;
        
        $total_bobot = 0;
        foreach($hasil as $tmp)
        {
            // $sks = empty($tmp['sks']) ? 0 : $tmp['sks'];
            // $total_sks += $sks;
            $results[$tmp['semester']][] = $tmp;
        }
        $list_semester = \app\helpers\MyHelper::getSemester();
        for($br=0;$br<4;$br++) {
            foreach($list_semester[$br] as $semester) {

                if(empty($results[$semester])) continue;

                $sks = 0;
                $subbobot = 0;
                if(!empty($results[$semester])){
                    foreach($results[$semester] as $m) {
                        $m = (object) $m;
                        $subbobot += $m->bobot_sks;
                        $total_sks += $m->sks;
                    }

                    
                }

                $total_bobot += $subbobot;    
                
            }
        }

        // print_r($total_bobot);exit;
        
        if($total_sks > 0)
        {
            $ipk = round($total_bobot / $total_sks,2);
        }

        // print_r($bobot);exit;

        return $ipk;
    }
    
     public function getSimakCatatanHarians()
    {
        return $this->hasMany(SimakCatatanHarian::className(), ['nim' => 'nim_mhs']);
    }

     public function getSimakProposalSkripsis()
    {
        return $this->hasMany(SimakProposalSkripsi::className(), ['nim' => 'nim_mhs']);
    }

    public function getNilaiAngkaTahfidz($tahun_id)
    {
        $t = \app\models\SimakTahfidzNilai::find()->where([
          'nim' => $this->nim_mhs,
          'tahun_id' => $tahun_id
        ])->one();

        $na = !empty($t) ? $t->nilai_angka : '';
        $nh = !empty($t) ? $t->nilai_huruf : '';
        // $status = $na <= 2 ? 'danger' : 'success';
        // $label = $na <= 2 ? 'BELUM LULUS' : 'LULUS';

        return $na;
    }

    public function getNilaiHurufTahfidz($tahun_id)
    {
        $t = \app\models\SimakTahfidzNilai::find()->where([
          'nim' => $this->nim_mhs,
          'tahun_id' => $tahun_id
        ])->one();

        $na = !empty($t) ? $t->nilai_angka : '';
        $nh = !empty($t) ? $t->nilai_huruf : '';
        // $status = $na <= 2 ? 'danger' : 'success';
        // $label = $na <= 2 ? 'BELUM LULUS' : 'LULUS';

        return $nh;
    }

    public function getStatusTahfidz($tahun_id)
    {
        $t = \app\models\SimakTahfidzNilai::find()->where([
          'nim' => $this->nim_mhs,
          'tahun_id' => $tahun_id
        ])->one();

        $na = !empty($t) ? $t->nilai_angka : '';
        // $nh = !empty($t) ? $t->nilai_huruf : '';
        // $status = $na <= 2 ? 'danger' : 'success';
        $label = $na <= 2 ? 'BELUM LULUS' : 'LULUS';

        return $label;
    }

    public function getSimakDatakrs()
    {
        return $this->hasMany(SimakDatakrs::className(), ['mahasiswa' => 'nim_mhs']);
    }

    public function getSimakDatakrsPertahun()
    {
        return $this->hasMany(SimakDatakrs::className(), ['mahasiswa' => 'nim_mhs'])->where(['tahun_akademik'=>$this->tahun_id]);
    }
}
