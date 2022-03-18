<?php
namespace app\helpers;

use Yii;

/**
 * Css helper class.
 */
class MyHelper
{

    public static function getListAbsensi()
    {
            $list = [
                    '1' => 'H',
                    '2' => 'I',
                    '3' => 'S',
                    '4' => 'G'

            ];

            return $list;
    }

    public static function getTahunAktif()
    {
        $tahun_id = 0;
        if(!Yii::$app->session->has('tahun_id'))
        {
            $tahun_akademik = \app\models\SimakTahunakademik::getTahunAktif();
            Yii::$app->session->set('tahun_id',$tahun_akademik->tahun_id);
            $tahun_id = $tahun_akademik->tahun_id;
        }

        else
        {
            $tahun_id = Yii::$app->session->get('tahun_id');   
        }

        return $tahun_id;
    }

    
    public static function dmYtoYmd($tgl){
        $date = str_replace('/', '-', $tgl);
        return date('Y-m-d H:i:s',strtotime($date));
    }

    public static function YmdtodmY($tgl){
        return date('d-m-Y H:i:s',strtotime($tgl));
    }
    public static function hitungDurasi($date1, $date2)
    {
        $date1 = new \DateTime($date1);
        $date2 = new \DateTime($date2);
        $interval = $date1->diff($date2);

        $elapsed = '';
        if($interval->d > 0)
            $elapsed = $interval->format('%a hari %h jam %i menit %s detik');
        else if($interval->h > 0)
            $elapsed = $interval->format('%h jam %i menit %s detik');
        else
            $elapsed = $interval->format('%i menit %s detik');
        

        return $elapsed;
    }

    public static function getSemester()
    {
        $list_semester = [
          0 => [1=>1,2=>2],
          1 => [3=>3,4=>4],
          2 => [5=>5,6=>6],
          3 => [7=>7,8=>8],
        ];

        return $list_semester;
    }


    public static function getListPrioritas(){
        $list_prioritas = [
            '1' => 'HIGH',
            '2' => 'MED',
            '3' => 'LOW',
            '4' => 'SLIGHTLY LOW',
            '5' => 'LOWEST',

        ];

        return $list_prioritas;
    }

    public static function getListSemester()
    {
        $list_semester = [
            1 => 'Semester 1',
            2 => 'Semester 2',
            3 => 'Semester 3',
            4 => 'Semester 4',
            5 => 'Semester 5',
            6 => 'Semester 6',
            7 => 'Semester 7',
            8 => 'Semester 8',
            9 => 'Semester 9 ke atas',
        ];

        return $list_semester;
    }

	public static function getKampusList()
    {
        $results = [];
        $list = \app\models\SimakKampus::find()->all();
        foreach ($list as $item_name) {
            $results[$item_name->kode_kampus] = $item_name->nama_kampus;
        }

        return $results;
    }

     public static function getStatusAktivitas()
    {
        $roles = [
        	'A' => 'AKTIF','C' => 'CUTI', 'D' => 'DO','K' => 'KELUAR' ,'L' => 'LULUS','N' => 'NON-AKTIF', 'G'=>'DOUBLE DEGREE','M'=>'MUTASI'
        ];
        

        return $roles;
    }

	public static function appendZeros($str, $charlength=6)
	{

		return str_pad($str, $charlength, '0', STR_PAD_LEFT);
	}

	public static function parseVACode($header, $nim)
	{
		return $header.str_pad(substr($nim, 0,2).substr($nim, -6,6),10,'0',STR_PAD_LEFT);
	}

	public static function logError($model)
	{
		$errors = '';
        foreach($model->getErrors() as $attribute){
            foreach($attribute as $error){
                $errors .= $error.' ';
            }
        }

        return $errors;
	}

	public static function formatRupiah($value,$decimal=0){
		return number_format($value, $decimal,',','.');
	}

    public static function getSelisihHariInap($old, $new)
    {
        $date1 = strtotime($old);
        $date2 = strtotime($new);
        $interval = $date2 - $date1;
        return round($interval / (60 * 60 * 24)) + 1; 

    }

    public static function getRandomString($minlength=12, $maxlength=12, $useupper=true, $usespecial=false, $usenumbers=true)
	{

	    $charset = "abcdefghijklmnopqrstuvwxyz";

	    if ($useupper) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	    if ($usenumbers) $charset .= "0123456789";

	    if ($usespecial) $charset .= "~@#$%^*()_±={}|][";

	    for ($i=0; $i<$maxlength; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];

	    return $key;

	}
}