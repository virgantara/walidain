<?php
namespace app\helpers;

use Yii;

/**
 * Css helper class.
 */
class MyHelper
{

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