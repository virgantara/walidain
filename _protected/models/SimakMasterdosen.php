<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_masterdosen".
 *
 * @property int $id
 * @property string $kode_pt
 * @property string $kode_fakultas
 * @property string|null $kode_jurusan
 * @property string $kode_prodi
 * @property string $kode_jenjang_studi
 * @property string $no_ktp_dosen
 * @property string|null $nidn_asli
 * @property string $nidn
 * @property string|null $niy
 * @property string $nama_dosen
 * @property string|null $gelar_depan
 * @property string|null $gelar_akademik
 * @property string|null $tempat_lahir_dosen
 * @property string|null $tgl_lahir_dosen
 * @property string|null $jenis_kelamin
 * @property string|null $kode_jabatan_akademik
 * @property string|null $kode_pendidikan_tertinggi
 * @property string|null $kode_status_kerja_pts
 * @property string|null $kode_status_aktivitas_dosen
 * @property string|null $tahun_semester
 * @property string|null $nip_pns
 * @property string|null $home_base
 * @property string|null $photo_dosen
 * @property string|null $no_telp_dosen
 * @property string|null $no_hp_dosen
 * @property string|null $email_dosen
 * @property string|null $alamat_dosen
 * @property string|null $alamat_domisili
 * @property string|null $kabupaten_dosen
 * @property int|null $provinsi_dosen
 * @property string|null $agama_dosen
 * @property string|null $created
 * @property string|null $google_scholar_id
 * @property string|null $scopus_id
 * @property string|null $sinta_id
 * @property string|null $kode_feeder
 * @property string|null $id_reg_ptk
 * @property string|null $status_aktif
 *
 * @property ErpOrganisasiMahasiswa[] $erpOrganisasiMahasiswas
 * @property Events[] $events
 * @property SimakJadwal[] $jadwals
 * @property SimakMasterprogramstudi $kodeProdi
 * @property LitabAdministrasiPenelitian[] $litabAdministrasiPenelitians
 * @property LitabAdministrasiPengabdian[] $litabAdministrasiPengabdians
 * @property LitabBorangMonevLaporanKemajuanPenelitian $litabBorangMonevLaporanKemajuanPenelitian
 * @property LitabBorangNilaiPenelitian[] $litabBorangNilaiPenelitians
 * @property LitabBorangNilaiPengabdian[] $litabBorangNilaiPengabdians
 * @property LitabBukuDosen[] $litabBukuDosens
 * @property LitabHkiDosen[] $litabHkiDosens
 * @property LitabJurnalDosen[] $litabJurnalDosens
 * @property LitabLuaranLainDosen[] $litabLuaranLainDosens
 * @property LitabPemakalahDosen[] $litabPemakalahDosens
 * @property LitabPenelitian[] $litabPenelitians
 * @property LitabPenelitian[] $litabPenelitians0
 * @property LitabPenelitian[] $litabPenelitians1
 * @property LitabPenelitian[] $litabPenelitians2
 * @property LitabPenelitian[] $litabPenelitians3
 * @property LitabPenelitian[] $litabPenelitians4
 * @property LitabPengabdian[] $litabPengabdians
 * @property LitabPengabdian[] $litabPengabdians0
 * @property LitabPengabdian[] $litabPengabdians1
 * @property LitabPengabdian[] $litabPengabdians2
 * @property LitabPengabdian[] $litabPengabdians3
 * @property LitabPengabdian[] $litabPengabdians4
 * @property LitabReviewAbdimas[] $litabReviewAbdimas
 * @property LitabReview[] $litabReviews
 * @property Notification[] $notifications
 * @property LitabPenelitian[] $penelitians
 * @property LitabPengabdian[] $pengabdians
 * @property SimakAjarDosen[] $simakAjarDosens
 * @property SimakDosenPembimbing[] $simakDosenPembimbings
 * @property SimakDosenPenguji[] $simakDosenPengujis
 * @property SimakJadwalPengajar[] $simakJadwalPengajars
 * @property SimakMasterfakultas[] $simakMasterfakultas
 * @property SimakMastermahasiswa[] $simakMastermahasiswas
 * @property SimakMastermahasiswa[] $simakMastermahasiswas0
 * @property SimakMastermahasiswa[] $simakMastermahasiswas1
 * @property SimakMasterprogramstudi[] $simakMasterprogramstudis
 * @property SimakProposalSkripsi[] $simakProposalSkripsis
 * @property SimakProposalSkripsi[] $simakProposalSkripsis0
 * @property SimakProposalSkripsi[] $simakProposalSkripsis1
 * @property SimakSkripsi[] $simakSkripsis
 * @property SimakSkripsi[] $simakSkripsis0
 * @property SimakSkripsi[] $simakSkripsis1
 * @property SimakUniversitas[] $simakUniversitas
 */
class SimakMasterdosen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_masterdosen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_pt', 'kode_fakultas', 'kode_prodi', 'kode_jenjang_studi', 'no_ktp_dosen', 'nidn', 'nama_dosen'], 'required'],
            [['tgl_lahir_dosen', 'created'], 'safe'],
            [['alamat_dosen', 'alamat_domisili'], 'string'],
            [['provinsi_dosen'], 'integer'],
            [['kode_pt', 'home_base'], 'string', 'max' => 6],
            [['kode_fakultas', 'kode_jurusan', 'kode_jenjang_studi', 'kode_jabatan_akademik', 'kode_pendidikan_tertinggi', 'kode_status_kerja_pts', 'kode_status_aktivitas_dosen', 'tahun_semester'], 'string', 'max' => 5],
            [['kode_prodi'], 'string', 'max' => 15],
            [['no_ktp_dosen', 'nidn_asli', 'nidn', 'nip_pns'], 'string', 'max' => 30],
            [['niy', 'gelar_depan', 'no_hp_dosen'], 'string', 'max' => 20],
            [['nama_dosen', 'tempat_lahir_dosen'], 'string', 'max' => 50],
            [['gelar_akademik', 'kabupaten_dosen', 'status_aktif'], 'string', 'max' => 10],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['photo_dosen', 'google_scholar_id', 'scopus_id', 'sinta_id'], 'string', 'max' => 255],
            [['no_telp_dosen'], 'string', 'max' => 25],
            [['email_dosen', 'kode_feeder', 'id_reg_ptk'], 'string', 'max' => 100],
            [['agama_dosen'], 'string', 'max' => 2],
            [['nidn'], 'unique'],
            [['kode_prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['kode_prodi' => 'kode_prodi']],
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
            'kode_jurusan' => 'Kode Jurusan',
            'kode_prodi' => 'Kode Prodi',
            'kode_jenjang_studi' => 'Kode Jenjang Studi',
            'no_ktp_dosen' => 'No Ktp Dosen',
            'nidn_asli' => 'Nidn Asli',
            'nidn' => 'Nidn',
            'niy' => 'Niy',
            'nama_dosen' => 'Nama Dosen',
            'gelar_depan' => 'Gelar Depan',
            'gelar_akademik' => 'Gelar Akademik',
            'tempat_lahir_dosen' => 'Tempat Lahir Dosen',
            'tgl_lahir_dosen' => 'Tgl Lahir Dosen',
            'jenis_kelamin' => 'Jenis Kelamin',
            'kode_jabatan_akademik' => 'Kode Jabatan Akademik',
            'kode_pendidikan_tertinggi' => 'Kode Pendidikan Tertinggi',
            'kode_status_kerja_pts' => 'Kode Status Kerja Pts',
            'kode_status_aktivitas_dosen' => 'Kode Status Aktivitas Dosen',
            'tahun_semester' => 'Tahun Semester',
            'nip_pns' => 'Nip Pns',
            'home_base' => 'Home Base',
            'photo_dosen' => 'Photo Dosen',
            'no_telp_dosen' => 'No Telp Dosen',
            'no_hp_dosen' => 'No Hp Dosen',
            'email_dosen' => 'Email Dosen',
            'alamat_dosen' => 'Alamat Dosen',
            'alamat_domisili' => 'Alamat Domisili',
            'kabupaten_dosen' => 'Kabupaten Dosen',
            'provinsi_dosen' => 'Provinsi Dosen',
            'agama_dosen' => 'Agama Dosen',
            'created' => 'Created',
            'google_scholar_id' => 'Google Scholar ID',
            'scopus_id' => 'Scopus ID',
            'sinta_id' => 'Sinta ID',
            'kode_feeder' => 'Kode Feeder',
            'id_reg_ptk' => 'Id Reg Ptk',
            'status_aktif' => 'Status Aktif',
        ];
    }

    /**
     * Gets query for [[ErpOrganisasiMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpOrganisasiMahasiswas()
    {
        return $this->hasMany(ErpOrganisasiMahasiswa::className(), ['pembimbing_id' => 'id']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['dosen_id' => 'nidn']);
    }

    /**
     * Gets query for [[Jadwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJadwals()
    {
        return $this->hasMany(SimakJadwal::className(), ['id' => 'jadwal_id'])->viaTable('simak_ajar_dosen', ['dosen_id' => 'id']);
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
     * Gets query for [[LitabAdministrasiPenelitians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabAdministrasiPenelitians()
    {
        return $this->hasMany(LitabAdministrasiPenelitian::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabAdministrasiPengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabAdministrasiPengabdians()
    {
        return $this->hasMany(LitabAdministrasiPengabdian::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabBorangMonevLaporanKemajuanPenelitian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabBorangMonevLaporanKemajuanPenelitian()
    {
        return $this->hasOne(LitabBorangMonevLaporanKemajuanPenelitian::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabBorangNilaiPenelitians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabBorangNilaiPenelitians()
    {
        return $this->hasMany(LitabBorangNilaiPenelitian::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabBorangNilaiPengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabBorangNilaiPengabdians()
    {
        return $this->hasMany(LitabBorangNilaiPengabdian::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabBukuDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabBukuDosens()
    {
        return $this->hasMany(LitabBukuDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabHkiDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabHkiDosens()
    {
        return $this->hasMany(LitabHkiDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabJurnalDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabJurnalDosens()
    {
        return $this->hasMany(LitabJurnalDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabLuaranLainDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabLuaranLainDosens()
    {
        return $this->hasMany(LitabLuaranLainDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPemakalahDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPemakalahDosens()
    {
        return $this->hasMany(LitabPemakalahDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians()
    {
        return $this->hasMany(LitabPenelitian::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians0()
    {
        return $this->hasMany(LitabPenelitian::className(), ['reviewer1_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians1()
    {
        return $this->hasMany(LitabPenelitian::className(), ['reviewer2_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians2()
    {
        return $this->hasMany(LitabPenelitian::className(), ['anggota1_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians3()
    {
        return $this->hasMany(LitabPenelitian::className(), ['anggota2_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPenelitians4]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPenelitians4()
    {
        return $this->hasMany(LitabPenelitian::className(), ['anggota3_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians()
    {
        return $this->hasMany(LitabPengabdian::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians0()
    {
        return $this->hasMany(LitabPengabdian::className(), ['reviewer1_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians1()
    {
        return $this->hasMany(LitabPengabdian::className(), ['reviewer2_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians2()
    {
        return $this->hasMany(LitabPengabdian::className(), ['anggota1_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians3()
    {
        return $this->hasMany(LitabPengabdian::className(), ['anggota2_id' => 'id']);
    }

    /**
     * Gets query for [[LitabPengabdians4]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabPengabdians4()
    {
        return $this->hasMany(LitabPengabdian::className(), ['anggota3_id' => 'id']);
    }

    /**
     * Gets query for [[LitabReviewAbdimas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabReviewAbdimas()
    {
        return $this->hasMany(LitabReviewAbdimas::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[LitabReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLitabReviews()
    {
        return $this->hasMany(LitabReview::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[Penelitians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenelitians()
    {
        return $this->hasMany(LitabPenelitian::className(), ['id' => 'penelitian_id'])->viaTable('litab_review', ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[Pengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdians()
    {
        return $this->hasMany(LitabPengabdian::className(), ['id' => 'pengabdian_id'])->viaTable('litab_review_abdimas', ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[SimakAjarDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakAjarDosens()
    {
        return $this->hasMany(SimakAjarDosen::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[SimakDosenPembimbings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakDosenPembimbings()
    {
        return $this->hasMany(SimakDosenPembimbing::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[SimakDosenPengujis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakDosenPengujis()
    {
        return $this->hasMany(SimakDosenPenguji::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[SimakJadwalPengajars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakJadwalPengajars()
    {
        return $this->hasMany(SimakJadwalPengajar::className(), ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[SimakMasterfakultas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMasterfakultas()
    {
        return $this->hasMany(SimakMasterfakultas::className(), ['pejabat' => 'nidn']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['nip_co_promotor1' => 'id']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas0()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['nip_co_promotor2' => 'id']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas1()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['nip_promotor' => 'id']);
    }

    /**
     * Gets query for [[SimakMasterprogramstudis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMasterprogramstudis()
    {
        return $this->hasMany(SimakMasterprogramstudi::className(), ['nidn_ketua_prodi' => 'nidn']);
    }

    /**
     * Gets query for [[SimakProposalSkripsis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakProposalSkripsis()
    {
        return $this->hasMany(SimakProposalSkripsi::className(), ['penguji1' => 'id']);
    }

    /**
     * Gets query for [[SimakProposalSkripsis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakProposalSkripsis0()
    {
        return $this->hasMany(SimakProposalSkripsi::className(), ['penguji2' => 'id']);
    }

    /**
     * Gets query for [[SimakProposalSkripsis1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakProposalSkripsis1()
    {
        return $this->hasMany(SimakProposalSkripsi::className(), ['penguji3' => 'id']);
    }

    /**
     * Gets query for [[SimakSkripsis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakSkripsis()
    {
        return $this->hasMany(SimakSkripsi::className(), ['penguji1' => 'id']);
    }

    /**
     * Gets query for [[SimakSkripsis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakSkripsis0()
    {
        return $this->hasMany(SimakSkripsi::className(), ['penguji2' => 'id']);
    }

    /**
     * Gets query for [[SimakSkripsis1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakSkripsis1()
    {
        return $this->hasMany(SimakSkripsi::className(), ['penguji3' => 'id']);
    }

    /**
     * Gets query for [[SimakUniversitas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakUniversitas()
    {
        return $this->hasMany(SimakUniversitas::className(), ['rektor' => 'id']);
    }
}
