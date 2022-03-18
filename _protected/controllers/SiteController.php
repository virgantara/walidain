<?php
namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use app\models\AccountActivation;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\ContactForm;
use app\models\SimakMastermahasiswa;
use app\models\SimakIndukKegiatan;
use app\models\SimakTahfidzNilai;

use yii\httpclient\Client;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \Firebase\JWT\JWT;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, and password reset.
 */
class SiteController extends Controller
{
    public $successUrl = '';
    
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $this->successUrl
            ],
        ];
    }

    public function beforeAction($action)
    {
        
        return true; // or false to not run the action
    }

    public function actionAuthCallback()
    {
        $results = [];
        
        try
        {
            $token = $_SERVER['HTTP_X_JWT_TOKEN'];
            $key = Yii::$app->params['jwt_key'];
            $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $results = [
                'code' => 200,
                'message' => 'Valid'
            ];
            
        }
        catch(\Exception $e) 
        {

            $results = [
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results);

        die();
       
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $user = \app\models\User::find()
            ->where([
                'email'=>$attributes['email'],
            ])
            ->one();
        if(!empty($user)){
            Yii::$app->user->login($user);
        }
        else{
            //Simpen disession attribute user dari Google
            $session = Yii::$app->session;
            $session['attributes']=$attributes;
            // redirect ke form signup, dengan mengset nilai variabell global successUrl
            $this->successUrl = \yii\helpers\Url::to(['site/index']);
        }   
    }

    public function actionLoginSso($token)
    {
        // print_r($token);exit;
        
        $key = Yii::$app->params['jwt_key'];
        $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
        
        $uuid = $decoded->uuid; // will print "1"
        $user = \app\models\User::find()
            ->where([
                'uuid'=>$uuid,
            ])
            ->one();

        if(!empty($user))
        {

            
            $session = Yii::$app->session;
            $session->set('token',$token);
           
            Yii::$app->user->login($user);
            return $this->redirect(['site/index']);
        }

        else{
            
            
            return $this->redirect($decoded->iss.'/site/sso-callback?code=302')->send();
        }
       
    }

//------------------------------------------------------------------------------------------------//
// STATIC PAGES
//------------------------------------------------------------------------------------------------//

    
    public function actionAjaxRincianTagihan()
    {
        if(Yii::$app->request->isPost)
        {
            
            $dataItem = $_POST['dataItem'];
            $tahun = $dataItem['tahun'];
            $kampus = $dataItem['kampus'];
            $status_aktivitas = $dataItem['status_aktivitas'];
            $singkatan = $dataItem['singkatan'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];

            $headers = ['x-access-token'=>$client_token];
            
            $params = [
                'tahun' => $tahun,
                'status_aktivitas' => $status_aktivitas,
                'kampus' => $kampus,
                'singkatan' => $singkatan
            ];

            $results = [];

            $response = $client->get('/tagihan/rincian', $params,$headers)->send();
        
            if ($response->isOk) {
                $results = $response->data['values'];

                
               
            }

            echo json_encode($results);

            die();
        }
    }

    public function actionAjaxDataTagihan()
    {
        if(Yii::$app->request->isPost)
        {
            
            $dataItem = $_POST['dataItem'];
            $tahun = $dataItem['tahun'];
            $kampus =  $dataItem['kampus'];
            $status_aktivitas = $dataItem['status_aktivitas'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];

            $headers = ['x-access-token'=>$client_token];
            
            $params = [
                'tahun' => $tahun,
                'kampus' => $kampus,
                'status_aktivitas' => $status_aktivitas
            ];

            $results = [];

            $response = $client->get('/tagihan/rekap', $params,$headers)->send();
        
            if ($response->isOk) {
                $tmp = $response->data['values'];


                $total_tagihan = 0;
                $total_terbayar = 0;
                foreach($tmp as $t)
                {
                    $total_tagihan += $t['tagihan'];
                    $total_terbayar += $t['terbayar'];
                }

                $total_piutang = $total_tagihan - $total_terbayar;

                $results['rincian'] = $tmp;
                $results['total_tagihan'] = $total_tagihan;
                $results['total_terbayar'] = $total_terbayar;
                $results['total_piutang'] = $total_piutang;
            }

            echo json_encode($results);

            die();
        }
    }

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {

        $saldo = 0;
        $mhs = null;
        $konfirmasi = null;
        $ta = \app\models\SimakTahunakademik::getTahunAktif();
        $tahfidz = null;
        $indukKegiatan = null;
        $tagihan = null;
        $nim = '-';

        $list_tahun = [];
        
        $fakultas = [];
        $results1 = 0;
        $results2 = 1;
        $list_kampus = [];

        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k','simakMahasiswaOrtus as ortu']);

        if(!Yii::$app->user->isGuest){
            $query->andWhere(['ortu.ortu_user_id' => Yii::$app->user->identity->id]);
        }

        else{
            $query->andWhere(['ortu.ortu_user_id' => '-']);
        }

        $list_anak = $query->all();

        $session = Yii::$app->session;

        if(count($list_anak) == 1){
            $mhs = $list_anak[0];
            $session->set('nim',$mhs->nim_mhs);
        }

        

        if(!empty($_GET['nim']) || $session->has('nim')){
            
            if($session->has('nim')){
                if(!empty($_GET['nim']) && ($_GET['nim'] == $session->get('nim'))){
                    $nim = $session->get('nim');

                }
                else if(!empty($_GET['nim'])){
                    $nim = $_GET['nim'];
                    $session->set('nim',$nim);
                }
                else{
                    $nim = $session->get('nim');
                }

            }
            else{
                $nim = $_GET['nim'];
                $session->set('nim',$nim);
            }


            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();
            $indukKegiatan = \app\models\SimakIndukKegiatan::find()->orderBy(['id'=>SORT_ASC])->cache(60 * 20)->all();
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $params = [
                'nim' => $nim
            ];
            $response = $client->get('/b/saldo', $params,$headers)->send();
           
            if ($response->isOk) {
                $saldo = is_numeric($response->data['values']) ? $response->data['values'] : 0 ;            
            }


            $mhs = \app\models\SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();

            $list_tahun = \app\models\SimakTahunakademik::find()->where([
                '>=','tahun',$mhs->tahun_masuk
            ])->orderBy(['tahun_id' => SORT_DESC])->all();


            $konfirmasi = \app\models\SimakKonfirmasipembayaran::find()->where([
                'pembayaran' => '01',
                'status' => 1,
                'nim' => $nim,
                'tahun_id' => $ta->tahun_id
            ])->one();

            $query = \app\models\Tagihan::find();
    
            $query->joinWith(['komponen as k','komponen.kategori as kat']);
            $query->where([
                'nim' => $nim,
                'bill_tagihan.tahun' => $ta->tahun_id,
                'kat.kode' => '01'
            ]);

            $tagihan = $query->one();


            $tahfidz = SimakTahfidzNilai::find()->where([
                'tahun_id' => $ta->tahun_id,
                'nim'=>$nim
            ])->one();

        }

        
    
        return $this->render('index',[
            'list_anak' => $list_anak,
            'mhs' => $mhs,
            'saldo' => $saldo,
            'ta' => $ta,
            'indukKegiatan' => $indukKegiatan
        ]);
        // }
    }

    /**
     * Displays the about static page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays the contact static page and sends the contact email.
     *
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('contact', ['model' => $model]);
        }

        if (!$model->sendEmail(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while sending email.'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 
            'Thank you for contacting us. We will respond to you as soon as possible.'));
        
        return $this->refresh();
    }

//------------------------------------------------------------------------------------------------//
// LOG IN / LOG OUT / PASSWORD RESET
//------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {

       
        
        $this->layout = 'default';
        // user is logged in, he doesn't need to login
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if 'lwe' value is 'true' we instantiate LoginForm in 'lwe' scenario
        $model = $lwe ? new LoginForm(['scenario' => 'lwe']) : new LoginForm();

        // monitor login status
        $successfulLogin = true;

        // posting data or login has failed
        if (!$model->load(Yii::$app->request->post()) || !$model->login()) {
            $successfulLogin = false;
        }



        // if user's account is not activated, he will have to activate it first
        if ($model->status === User::STATUS_INACTIVE && $successfulLogin === false) {
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'You have to activate your account first. Please check your email.'));
            return $this->refresh();
        } 

        // if user is not denied because he is not active, then his credentials are not good
        if ($successfulLogin === false) {
            return $this->render('login', ['model' => $model]);
        }

        // login was successful, let user go wherever he previously wanted
        return $this->redirect(['index']);
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

/*----------------*
 * PASSWORD RESET *
 *----------------*/

    /**
     * Sends email that contains link for password reset action.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'default';
        $model = new PasswordResetRequestForm();

        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('requestPasswordResetToken', ['model' => $model]);
        }

        if (!$model->sendEmail()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'Sorry, we are unable to reset password for email provided.'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Silakan cek inbox email Anda. Jika tidak ada, mohon cek spam Anda'));

        return $this->redirect(['login']);
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$model->load(Yii::$app->request->post()) || !$model->validate() || !$model->resetPassword()) {
            return $this->render('resetPassword', ['model' => $model]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'New password was saved.'));

        return $this->goHome();      
    }    

//------------------------------------------------------------------------------------------------//
// SIGN UP / ACCOUNT ACTIVATION
//------------------------------------------------------------------------------------------------//

    public function actionAjaxSignup()
    {
        $dataPost = $_POST['dataPost'];
        parse_str($dataPost, $searcharray);
        // print_r($searcharray);

        // exit;
        $post = $searcharray['SignupForm'];
        $results = [
            'code' => 404,
            'message' => ''
        ];

        
        if(isset($searcharray['g-recaptcha-response']) && !empty($searcharray['g-recaptcha-response'])) {
            $secret = Yii::$app->params['reCaptcha']['secret_key'];
            //get verify response data
            $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$searcharray['g-recaptcha-response']);
            $response = json_decode($verify);
            if($response->success)
            {
                $rna = Yii::$app->params['rna'];

                $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();
                $model->attributes = $post;
                if (!$model->validate()) {
                    $results = [
                        'code' => 500,
                        'message' => \app\helpers\MyHelper::logError($model),
                    ];
                    echo json_encode($results);
                    exit;
                } 

                $user = $model->ajaxSignup();

                if (!$user) {
                    // display error message to user
                    $msg = Yii::t('app', 'We couldn\'t sign you up, please contact us.');
                    $results = [
                        'code' => 500,
                        'message' => $msg,
                    ];
                    echo json_encode($results);
                    exit;
                }

                // user is saved but activation is needed, use signupWithActivation()
                if ($user->status === User::STATUS_INACTIVE) {
                    if (!$model->sendAccountActivationEmail($user)) {
                        // display error message to user
                        // Yii::$app->session->setFlash('error', Yii::t('app', 
                        //     'We couldn\'t send you account activation email, please contact us.'));

                        // // log this error, so we can debug possible problem easier.
                        // Yii::error('Signup failed! User '.Html::encode($user->username).' could not sign up. 
                        //     Possible causes: verification email could not be sent.');

                        $results = [
                            'code' => 500,
                            'message' => Yii::t('app', 'We couldn\'t send you account activation email, please contact us.'),
                        ];
                        echo json_encode($results);
                        exit;
                    }

                    // everything is OK
                    $msg = Yii::t('app', 'Halo').' '.Html::encode($user->username). '. ' .
                        Yii::t('app', 'Terima kasih atas pendaftaran Anda. Agar bisa login, Anda harus konfirmasi registrasi. 
                            Silakan cek inbox atau spam di email Anda, kami telah mengirimakan pesan.');
                    // $this->signupWithActivation($model, $user);
                    $results = [
                        'code' => 200,
                        'message' => $msg,
                    ];
                    echo json_encode($results);
                    exit;
                }

                
            }

            else{
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Google reCAPTCHA verification failed. please try again'));
                $results = [
                    'code' => 500,
                    'message' => 'Google reCAPTCHA verification failed. please try again',
                ];
                echo json_encode($results);
                exit;
            }
        }

        echo json_encode($results);
        exit;
    }

    /**
     * Signs up the user.
     * If user need to activate his account via email, we will display him
     * message with instructions and send him account activation email with link containing account activation token. 
     * If activation is not necessary, we will log him in right after sign up process is complete.
     * NOTE: You can decide whether or not activation is necessary, @see config/params.php
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {  

        $rna = Yii::$app->params['rna'];

        // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
             //your site secret key
            $secret = Yii::$app->params['reCaptcha']['secret_key'];
            //get verify response data
            $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $response = json_decode($verify);
            if($response->success)
            {
                

                // if validation didn't pass, reload the form to show errors
                if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                    return $this->render('signup', ['model' => $model]);  
                }

                // try to save user data in database, if successful, the user object will be returned
                $user = $model->signup();

                if (!$user) {
                    // display error message to user
                    Yii::$app->session->setFlash('error', Yii::t('app', 'We couldn\'t sign you up, please contact us.'));
                    return $this->refresh();
                }

                // user is saved but activation is needed, use signupWithActivation()
                if ($user->status === User::STATUS_INACTIVE) {
                    $this->signupWithActivation($model, $user);
                    return $this->refresh();
                }

                // now we will try to log user in
                // if login fails we will display error message, else just redirect to home page
            
                if (!Yii::$app->user->login($user)) {
                    // display error message to user
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Please try to log in.'));

                    // log this error, so we can debug possible problem easier.
                    Yii::error('Login after sign up failed! User '.Html::encode($user->username).' could not log in.');
                }
                              
                
                // echo 'Verification Success <br>';
                // echo 'Your submitted form : <br>';
                // echo 'Name : '.$_POST['name'].'<br>';
                // echo 'Address : '.$_POST['address'].'<br>';
                // echo 'Email : '.$_POST['email'].'<br>';
                // echo 'Thanks You!';
         
                return $this->goHome();
            }
            else
            {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Google reCAPTCHA verification failed. please try again'));
                return $this->refresh();
                
            }
        }


        return $this->render('signup', ['model' => $model]);

        // get setting value for 'Registration Needs Activation'
        
    }

    /**
     * Tries to send account activation email.
     *
     * @param $model
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // sending email has failed
        if (!$model->sendAccountActivationEmail($user)) {
            // display error message to user
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'We couldn\'t send you account activation email, please contact us.'));

            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed! User '.Html::encode($user->username).' could not sign up. 
                Possible causes: verification email could not be sent.');
        }

        // everything is OK
        Yii::$app->session->setFlash('success', Yii::t('app', 'Halo').' '.Html::encode($user->username). '. ' .
            Yii::t('app', 'Supaya bisa login, Anda harus konfirmasi registrasi. 
                Silakan cek inbox atau spam di email Anda, kami telah mengirimakan pesan.'));
    }

/*--------------------*
 * ACCOUNT ACTIVATION *
 *--------------------*/

    /**
     * Activates the user account so he can log in into system.
     *
     * @param  string $token
     * @return \yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        try {
            $user = new AccountActivation($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$user->activateAccount()) {
            Yii::$app->session->setFlash('error', Html::encode($user->username). Yii::t('app', 
                ' your account could not be activated, please contact us!'));
            return $this->goHome();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Success! You can now log in.').' '.
            Yii::t('app', 'Thank you').' '.Html::encode($user->username).' '.Yii::t('app', 'for joining us!'));

        return $this->redirect('login');
    }
}
