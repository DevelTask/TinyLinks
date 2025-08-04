<?php
use yii\helpers\Url;

$this->title = 'TinyLinks';
?>

<div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:80vh; padding:0 15px;">
    <h1 style="font-size:3.5rem; font-weight:bold; margin-bottom:30px; color:#2c3e50; text-shadow:1px 1px 3px rgba(0,0,0,0.1);">
        TinyLinks
    </h1>

    <form id="shorten-form" style="width:100%; max-width:600px;">
        <textarea name="url" placeholder="Вставьте ссылку..."
                  style="width:100%; min-height:80px; padding:10px; font-size:1.1rem; margin-bottom:15px; border-radius:6px; border:1px solid #ccc; resize:vertical;" required></textarea>
        <button type="submit"
                style="width:100%; padding:12px; font-size:1.1rem; background:#007bff; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
            Сократить
        </button>
    </form>

    <div id="result" style="margin-top:20px; font-size:1.1rem; text-align:center; display:none;">
        <span id="short-url-text"></span><br>
        <button id="copy-button" style="margin-top:10px; padding:8px 14px; font-size:1rem; border:none; background:#28a745; color:white; border-radius:5px; cursor:pointer;">
            Копировать
        </button>
    </div>
</div>

<?php
$shortenUrl = Url::to(['/api/link/create']);
$js = <<<JS
document.getElementById('shorten-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const resultBlock = document.getElementById('result');
    const shortUrlText = document.getElementById('short-url-text');

    resultBlock.style.display = 'none';
    shortUrlText.textContent = '';

    try {
        const res = await fetch('$shortenUrl', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
        });

        const data = await res.json();

        if (res.ok && data.short_url) {
            shortUrlText.innerHTML = 'Короткая ссылка: <a href="' + data.short_url + '" target="_blank">' + data.short_url + '</a>';
            resultBlock.style.display = 'block';

            document.getElementById('copy-button').onclick = function () {
                navigator.clipboard.writeText(data.short_url).then(() => {
                    alert('Ссылка скопирована!');
                }, () => {
                    alert('Ошибка при копировании');
                });
            };
        } else {
            // Если ошибка 400 и есть message с валидацией URL
            if (res.status === 400 && data.message && data.message.includes('"url":["Url is not a valid URL."')) {
                alert('Некорректная ссылка');
            } else {
                alert(data.message || 'Произошла ошибка.');
            }
        }
    } catch (e) {
        alert('Ошибка при обработке ответа от сервера.');
    }
});

JS;

$this->registerJs($js);
?>
