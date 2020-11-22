<?php
namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * AppController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC ) for your controllers and their actions.
 */
class AppController extends Controller
{
     public function beforeAction($action)
    {
        
        $session = Yii::$app->session;
        
        if($session->has('token'))
        {

            try
            {

                $token = $session->get('token');
                $key = Yii::$app->params['jwt_key'];
                $decoded = \Firebase\JWT\JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);

                $api_baseurl = Yii::$app->params['invoke_token_uri'];
                $client = new \yii\httpclient\Client(['baseUrl' => $api_baseurl]);
                $headers = ['x-jwt-token'=>$token];

                $params = [];
                $response = $client->get($api_baseurl, $params,$headers)->send();
                if ($response->isOk) {
                    $res = $response->data;
                    if($res['code'] != '200')
                    {
                        $session->remove('token');
                        throw new \Exception;
                        
                    }
                }

            }

            catch(\Exception $e) 
            {
                return $this->redirect(Yii::$app->params['sso_login']);
            }
            
            if (!parent::beforeAction($action)) {
                return false;
            } 
        }

        else
        {
            
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        

        // other custom code here

        return true; // or false to not run the action
    }
    

} // AppController
