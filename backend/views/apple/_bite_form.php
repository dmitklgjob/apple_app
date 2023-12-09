<?php
/**
 * @var \backend\forms\BiteUpdateForm $model
 * @var Apple $apple
 */

use Apple\Domain\Apple;
use backend\forms\BiteUpdateForm;
use yii\bootstrap5\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'form-ajax-percent',
        'class' => 'modal-content',
        'enctype' => 'multipart/form-data',
        'data' => ['ajax-form' => 'ajax'],
    ],
])
?>

<div class="modal-body">
    <div>
        Максимальный процент, который можно откусить: <?= $apple->getRestPercent() ?>
    </div>
    <div class="user-form">
        <div class="row">
            <?= $form->field($model, 'percent', ['options' => ['class' => 'col-sm-12']])->textInput(['type' => 'string']) ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
    <button class="btn btn-primary" type="submit">Применить</button>
</div>
<?php ActiveForm::end(); ?>
