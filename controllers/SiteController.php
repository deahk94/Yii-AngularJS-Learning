<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\BaseYii;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SearchForm;
use app\models\Wallet;
use app\models\Transaction;
use app\models\TransactionAR;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'member', 'transfer'],
                //'except' => ['index', 'error', 'captcha', 'login', 'about', 'contact'],
                'rules' => [
                    [
                        'actions' => ['logout', 'member', 'transfer'],
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
     * {@inheritdoc}
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['product/index']);

            // $wallet = new Wallet();
            // $walletData = $wallet->fetchData($model->username);

            // $walletA = 0;
            // $walletB = 0;
            // foreach ($walletData as $wallet) {
            //     if ($wallet['type'] === "A")
            //     {
            //         $walletA = $wallet->amount;
            //     }
            //     else
            //     {
            //         $walletB = $wallet->amount;
            //     }
            // }

            // return $this->render('member', ['model' => $model, 'walletA' => $walletA, 'walletB' => $walletB, 'data' => $walletData]);
            }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionMember()
    {
        $account = Yii::$app->user->identity;
        $walletList = ['ALL' => 'ALL', 'A' => 'A', 'B' => 'B'];

        $dataProvider = null;

         $search = new SearchForm();
        if($search->load(Yii::$app->request->get()) && $search->validate())
        {
            //throw new \Exception(var_export($search, true), 1);

            $query = TransactionAR::find();
            $query->AndWhere(['userid'=> $account->id]);

            // when Wallet Type input is not empty
            if(!empty($search->walletType) && $search->walletType !== "ALL")
            {
                //Get wallet's id because transaction table only have out/in-walletid
                $walletid = Wallet::findOne(['type' => $search->walletType, 'userid' => $account->id])->id;

                $query->AndWhere(['outwalletid' => $walletid]);

                //NOT USED* Change to outwalletid only
                /*$query->AndWhere(['or',
                    ['outwalletid' => $walletid],
                    ['inwalletid' => $walletid]
                ]);*/
            }

            // when min or max Amount is not empty
            if (!empty($search->minAmount) || !empty($search->maxAmount)){

                if (empty($search->minAmount))
                    $search->minAmount = $search->maxAmount;
                if (empty($search->maxAmount))
                    $search->maxAmount = $search->minAmount;

                $query->andFilterWhere(['between',
                    'amount', $search->minAmount, $search->maxAmount
                ]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    // 'enableMultiSort' => true,
                    'defaultOrder' => [
                        'date' => SORT_DESC
                    ],
                ],
            ]);
        }
        else
        {
            $dataProvider = new ActiveDataProvider([
                'query' => TransactionAR::find()
                            ->AndWhere(['userid'=> $account->id]),
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    // 'enableMultiSort' => true,
                    'defaultOrder' => [
                        'date' => SORT_DESC
                    ],
                ],
            ]);
        }

        // $dataProvider->setSort([
        //     'attributes' => [
        //         'amount' => [
        //             'asc' => ['amount' => SORT_ASC],
        //             'desc' => ['amount' => SORT_DESC],
        //             'default' => SORT_ASC
        //         ],
        //         'date' => [
        //             'asc' => ['date' => SORT_ASC],
        //             'desc' => ['date' => SORT_DESC],
        //             'default' => SORT_ASC,
        //         ],
        //         'outwalletid' => [
        //             'asc' => ['outwalletid' => SORT_ASC],
        //             'desc' => ['outwalletid' => SORT_DESC],
        //             'default' => SORT_ASC,
        //         ],
        //         'inwalletid' => [
        //             'asc' => ['inwalletid' => SORT_ASC],
        //             'desc' => ['inwalletid' => SORT_DESC],
        //             'default' => SORT_ASC,
        //         ],
        //     ],
        //     'defaultOrder' => [
        //         'date' => SORT_ASC
        //     ]
        // ]);

         //throw new \Exception(var_export(Yii::$app->request->post(), true), 1);

        return $this->render('member', [
            'account' => $account,
            'dataProvider' => $dataProvider,
            'search' => $search,
            'walletList' => $walletList,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTransfer()
    {
        $account = Yii::$app->user->identity;

        //GET request can be replace with AR find() using $account->id
        //Kept for future reference
        $walletType = $_GET['walletType'];
        $username = $_GET['username'];

        $balance = Wallet::find()->AndWhere(['userid' => $account->id, 'type' => $walletType])->one()->amount;

        $transaction = new Transaction();
        if($transaction->load(Yii::$app->request->post()) && $transaction->validate())
        {
            $outWallet = $account->getWallet($transaction->type);

            $inWalletType = ($transaction->type === 'A') ? 'B' : 'A';
            $inWallet = $account->getWallet($inWalletType);

            $outWallet->transfer($transaction->amount, $inWallet->id, [
                'remark' => 'test',
            ]);

            return $this->redirect('member');
        }

        return $this->render('transfer', [
            'transaction' => $transaction,
            'type' => $walletType,
            'user' => $username,
            'balance' => $balance,
        ]);
    }

    public function actionConfirm()
    {
        return $this->render('index');
    }
}
