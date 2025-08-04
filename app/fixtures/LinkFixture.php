<?php

namespace app\fixtures;

use yii\test\ActiveFixture;

class LinkFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Link';
    public $dataFile = '@app/fixtures/data/Link.php';
}
