<?php

namespace app\controllers;

use app\jobs\LogVisitJob;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\services\LinkCacheService;
use app\repositories\LinkRepository;

class SiteController extends Controller
{
    private LinkCacheService $cache;
    private LinkRepository $repository;

    public function __construct($id, $module, LinkCacheService $cache, LinkRepository $repository, $config = [])
    {
        $this->cache = $cache;
        $this->repository = $repository;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRedirect(string $short): \yii\web\Response
    {
        $url = $this->cache->getOriginalUrl($short);
        if (!$url) {
            $link = $this->repository->findByShortCode($short);
            if (!$link) {
                throw new NotFoundHttpException('Ссылка не найдена.');
            }
            $url = $link->original_url;
            $this->cache->cacheLink($short, $url);
        }

        Yii::$app->queue->push(new LogVisitJob([
            'shortCode' => $short,
            'userAgent' => Yii::$app->request->userAgent ?? '',
            'ip' => Yii::$app->request->userIP ?? '',
        ]));

        return $this->redirect($url, 301);
    }

    public function actionError()
    {
        return $this->render('error', [
            'exception' => Yii::$app->errorHandler->exception,
        ]);
    }

    public function actionTestQueue()
    {
        $id = Yii::$app->queue->push(new \app\jobs\LogVisitJob([
            'shortCode' => 'zopUT',
            'userAgent' => 'Yandex',
            'ip' => '127.0.0.1',
        ]));

        return 'Результат push: ' . var_export($id, true);
    }
}
