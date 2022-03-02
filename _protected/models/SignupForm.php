<?php
namespace app\models;

use app\rbac\helpers\RbacHelper;
use kartik\password\StrengthValidator;
use yii\base\Model;
use Yii;

/**
 * Model representing Signup Form.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match',  'not' => true,
                // we do not want to allow users to pick one of spam/bad usernames 
                'pattern' => '/\b('.Yii::$app->params['user.spamNames'].')\b/i',
                'message' => Yii::t('app', 'It\'s impossible to have that username.')],            
            ['username', 'unique', 'targetClass' => '\app\models\User', 
                'message' => Yii::t('app', 'This username has already been taken.')],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 
                'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),

            // on default scenario, user status is set to active
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            // status is set to not active on rna (registration needs activation) scenario
            ['status', 'default', 'value' => User::STATUS_INACTIVE, 'on' => 'rna'],
            // status has to be integer value in the given range. Check User model.
            ['status', 'in', 'range' => [User::STATUS_INACTIVE, User::STATUS_ACTIVE]]
        ];
    }

    /**
     * Set password rule based on our setting value ( Force Strong Password ).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }    

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Signs up the user.
     * If scenario is set to "rna" (registration needs activation), this means
     * that user need to activate his account using email confirmation method.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup()
    {
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;

        // if scenario is "rna" ( Registration Needs Activation ) we will generate account activation token
        if ($this->scenario === 'rna') {
            $user->generateAccountActivationToken();
        }

        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->access_role = 'ortu';

        if($user->save()){
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($user->access_role);
            $info = $auth->assign($role, $user->getId());

            if (!$info) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
                return null;
            }

            return $user;
        }

        else{

            print_r(\app\helpers\MyHelper::logError($user));exit;
            return null;
        }

        // if user is saved and role is assigned return user object
        
    }

    /**
     * Sends email to registered user with account activation link.
     *
     * @param  object $user Registered user.
     * @return bool         Whether the message has been sent successfully.
     */
    public function sendAccountActivationEmail($user)
    {


        $to      = $this->email;
        $subject = 'Aktivasi Walidain UNIDA Gontor untuk akun ' . Yii::$app->name;

        // $message = Yii::$app->controller->renderPartial('accountActivationToken',[
        //     'user' => $user
        // ]);

        // $headers =  'MIME-Version: 1.0' . "\r\n"; 
        // $headers .= 'From: Admin Tracer UNIDA Gontor <'.Yii::$app->params['supportEmail'].'>' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // mail($to, $subject, $message, $headers);

        $mailer = Yii::$app->mailer->compose();
        $message = Yii::$app->controller->renderPartial('accountActivationToken',[
            'user' => $user
        ]); 

        $mailer->setTo($to);
        $mailer->setFrom([Yii::$app->params['supportEmail'] => 'UPT PPTIK UNIDA Gontor']);
        $mailer->setSubject($subject);
        $mailer->setHtmlBody($message);
        $mailer->send();

        return true;
        // return Yii::$app->mailer->compose('accountActivationToken', ['user' => $user])
        //                         ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
        //                         ->setTo($this->email)
        //                         ->setSubject('Account activation for ' . Yii::$app->name)
        //                         ->send();
    }
}
