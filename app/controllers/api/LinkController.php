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
    // Отключаем CSRF-проверку, так как это API и запросы идут через AJAX
    public $enableCsrfValidation = false;

    private LinkService $linkService;

    /**
     * Внедряем сервис генерации коротких ссылок через конструктор.
     */
    public function __construct($id, $module, LinkService $linkService, $config = [])
    {
        $this->linkService = $linkService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Устанавливаем формат ответа JSON для всех действий в контроллере.
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Обрабатывает POST-запрос на создание короткой ссылки.
     *
     * Пример запроса:
     * POST /api/link/create
     * {
     *     "url": "https://example.com"
     * }
     *
     * Ответ:
     * {
     *     "short_url": "http://yourdomain/abcDE"
     * }
     */
    public function actionCreate()
    {
        // Загружаем и валидируем данные запроса
        $request = new CreateShortLinkRequest();
        $request->load(Yii::$app->request->post(), '');

        if (!$request->validate()) {
            // Если невалидные данные — выбрасываем 400 с описанием ошибок
            throw new BadRequestHttpException(json_encode($request->getErrors()));
        }

        // Генерируем короткую ссылку через сервис
        $shortUrl = $this->linkService->createShortLink($request->url);

        // Возвращаем короткую ссылку в формате JSON
        return ['short_url' => $shortUrl];
    }
}
