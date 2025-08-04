<?php

namespace app\controllers\api;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use app\services\LinkService;
use app\requests\CreateShortLinkRequest;

class LinkController extends Controller
{
    public $enableCsrfValidation = false;

    private LinkService $linkService;

    public function __construct($id, $module, LinkService $linkService, $config = [])
    {
        $this->linkService = $linkService;
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionCreate()
    {
        $request = new CreateShortLinkRequest();
        $request->load(Yii::$app->request->post(), '');

        if (!$request->validate()) {
            throw new BadRequestHttpException(json_encode($request->getErrors()));
        }

        $shortUrl = $this->linkService->createShortLink($request->url);

        return ['short_url' => $shortUrl];
    }
}
