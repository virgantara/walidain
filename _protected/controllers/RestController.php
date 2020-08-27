<?php

namespace app\controllers;

use yii\rest\ActiveController;
use app\models\Tagihan;
use app\models\Transaksi;
use app\models\Tahun;
use app\models\SimakKonfirmasipembayaran;
use app\models\SimakMastermahasiswa;
use app\models\SimakTahunakademik;

class RestController extends ActiveController
{

	public $modelClass = 'app\models\Tagihan';

	public function actionAutodebet()
	{
		
		$input = json_decode(file_get_contents('php://input'),true);
 		header('Content-type:application/json;charset=utf-8');
		
		$nim = $input['nim'];
		$trxdate = $input['trxdate'];
		$refno = $input['refno'];
		$fidbank = $input['fidbank'];
		$channelid = $input['channelid'];
		$kredit = (int)$input['kredit'];
		$reffbank = $input['reffbank'];
		$transno = $input['transno'];
		$tahun = Tahun::getTahunAktif();
		$mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();
		

		$transaction = \Yii::$app->db->beginTransaction();
        $errors = '';
        $msg = '';
		$results = [];
		try 
        {
			if($kredit > 0)
			{
				$trx = new Transaksi;
				$trx->CUSTID = $nim;
				$trx->METODE = 'TOPUP';
				$trx->TRXDATE = $trxdate;
				$trx->NOREFF = $refno;
				$trx->FIDBANK = $fidbank;
				$trx->KDCHANNEL = $channelid;
				$trx->DEBET = 0;
				$trx->KREDIT = $kredit;
				$trx->REFFBANK = $reffbank;
				$trx->TRANSNO = $transno;
				if(!$trx->save())
				{
					$errors .= \app\helpers\MyHelper::logError($trx);
				}

				else{
					$msg .= 'Transaksi '.$trx->METODE.' bertambah '.$kredit;
				}
			}

			else{
				$msg .= 'Kredit minus/nol '.json_encode($input);
			}

			$count = 0;
			
			$listTagihan = Tagihan::find()->where([
				'urutan' => 1,
				'tahun' => $tahun->id,
				'nim' => $nim
			])->all();

			foreach($listTagihan as $t)
			{				

				$saldo = Transaksi::getSaldo($nim);
				$nilai_tagihan = $t->nilai - $t->terbayar;

				if($saldo > 0)
				{

					$terbayar = $saldo >= $nilai_tagihan ? $nilai_tagihan : $saldo;
					$t->terbayar = $t->terbayar + $terbayar;


					if($t->save(false,['terbayar']))
					{

						if($terbayar > 0)
						{
							$trx = new Transaksi;
							$trx->CUSTID = $nim;
							$trx->METODE = 'PAYMENT';
							$trx->TRXDATE = $trxdate;
							$trx->NOREFF = $refno;
							$trx->FIDBANK = $fidbank;
							$trx->KDCHANNEL = $channelid;
							$trx->DEBET = $terbayar;
							$trx->KREDIT = 0;
							$trx->REFFBANK = $reffbank;
							$trx->TRANSNO = $transno;
							$trx->tagihan_id = $t->id;
							if($trx->save())
							{
								$count++;	
							}

							else
							{
								$errors .= \app\helpers\MyHelper::logError($trx).' line 91';
								throw new \Exception;
								
							}

						
							
							if($t->komponen->kategori->kode == '01' && $t->terbayar >= $t->nilai_minimal)
							{

				                $tahun_siakad = SimakTahunakademik::getTahunAktif();
				                $selisih = $tahun_siakad->tahun - $mhs->tahun_masuk + 1;
				                $semester = $tahun_siakad->tahun_id % 2 == 0 ? $selisih * 2 : $selisih * 2 - 1;
				                $mhs->semester = $semester;
				                $mhs->status_aktivitas = 'A';
				                if(!$mhs->save(false,['semester','status_aktivitas']))
				                {
				                	$errors = \app\helpers\MyHelper::logError($mhs);
									throw new \Exception;	
				                }

				                $konfirmasi = new SimakKonfirmasipembayaran;
								$konfirmasi->nim = $nim;
								$konfirmasi->pembayaran = '01';
								$konfirmasi->jumlah = $saldo;
								$konfirmasi->bank = $reffbank;
								$konfirmasi->tanggal = $trxdate;
								$konfirmasi->keterangan = $trxdate.'_autodebet';
								$konfirmasi->status = 1;
								$konfirmasi->semester = (string)$semester;
								$konfirmasi->date_created = date('Y:m:d H:i:s');
								$konfirmasi->tahun_id = $tahun_siakad->tahun_id;

								if(!$konfirmasi->save())
								{
									$errors .= \app\helpers\MyHelper::logError($konfirmasi);
									throw new \Exception;
								}
							}
						}
					}

					else
					{
						$errors .= \app\helpers\MyHelper::logError($t);
						throw new \Exception;
					}
				}

				

			}

			$transaction->commit();
			$results = [
            	'code' => 200,
            	'message' => 'Autodebet berhasil. Total : '.$count.' '.$msg
            ];
		} catch (\Exception $e) {
            $errors .= $e->getMessage();
            
            $results = [
            	'code' => 501,
            	'message' => $errors
            ];
            $transaction->rollBack();
            
        } catch (\Throwable $e) {
            $errors .= $e->getMessage();
            
            $results = [
            	'code' => 502,
            	'message' => $errors
            ];
            $transaction->rollBack();
            
        }
		echo json_encode($results);

		die();
	}
}