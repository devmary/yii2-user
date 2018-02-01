<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $settings app\modules\newmodule\models\SettingsForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS

JS;

$this->registerJs($script);
?>
<script>
    function createNewSecret(){
        $.ajax({
            url: '/newmodule/settings/secret',
            type: 'POST',
            beforeSend: function(){
                $('.qr-holder').css('opacity', '0.5');
            },
            success: function(result){
                if(!result) alert('Error!');
                $('.qr-holder').css('opacity', '1');
                var data = JSON.parse(result);
                $('#settingsform-secretcode').val(data.secret);
                $('.qr-img').attr('src', data.qrUrl);
            },
            error: function(){
                alert('Error!');
            }
        });
        return false;
    }

    function submitSettings() {
        var active_2fa = $('#settingsform-active2fa').is(':checked');
        var secret = $('#settingsform-secretcode').val();
        var imgUrl = $('#settingsform-qrurl').val();
        //var imgUrl = $('.qr-img').attr('src');

        var data = 'active=' + active_2fa + '&secret=' + secret + '&imgurl=' + imgUrl;


        /*var formData = new FormData('auth-2fa');
        var form_data = document.getElementById("auth-2fa");
        var data = "";
        var i;
        for (i = 0; i < form_data.length; i++) {
            data = data + form_data.elements[i].name + '=' + form_data.elements[i].value + "&";
        }
        console.log(formData);*/
        $.ajax({
            url: '/newmodule/settings/submit',
            type: 'POST',
            data: data,
            success: function(result){
                console.log(result);
                if (result) {
                    $('#check-code').modal('show');
                }
                //var data = JSON.parse(result);
                //console.log(data);
            },
            error: function(){
                alert('Error!');
            }
        });
        return false;
    }

    function checkCode() {
        var secret = $('#settingsform-secretcode').val();
        var code = $('#settingsform-checkcode').val();
        var data = 'code=' + code + '&secret=' + secret;
        $.ajax({
            url: '/newmodule/settings/check',
            type: 'POST',
            data: data,
            success: function(result) {
                if(result) {

                }
                console.log(result);
            },
            error: function(){
                alert('Error!');
            }
        });
    }

    /*jQuery(document).ready(function(){
        jQuery('#auth-2fa').submit(function(e){
            e.preventDefault();
            var formData = new FormData();
            $.ajax({
                url: '/newmodule/settings/submit',
                type: 'POST',
                data: formData,
                success: function(result){
                    var data = JSON.parse(result);
                    console.log(data);
                },
                error: function(){
                    alert('Error!');
                }
            });
            return false;
        });
    });
*/
</script>
<div class="settings container">
    <h1><?= Html::encode($this->title) ?></h1><br>

    <h4>Two Factor Authentication</h4><br>

    <?php
//    Pjax::begin([
//    // Pjax options
//    ]);
    ?>

    <?php $form = ActiveForm::begin([
        'id' => 'auth-2fa',
        'layout' => 'horizontal',
        //'options' => ['data' => ['pjax' => true]],
        'options' => ['name' => 'auth-2fa'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ]
    ]); ?>

    <?= $form->field($settings, 'active2FA', ['template' => "<label class=\"col-lg-1 control-label\" for=\"settingsform-able2fa\">{label}</label><div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",])->checkbox([
        //'autofocus' => true,
        'label' => 'Active',
//        'template' => "<label class=\"col-lg-1 control-label\" for=\"settingsform-able2fa\">{label}</label><div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ], false) ?>

    <?= $form->field($settings, 'secretCode', ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<a href='#' class='btn btn-default' onclick='return createNewSecret();'>Create new secret</a>",])->textInput([
        'label' => 'Secret',
        'readonly' => 'readonly',
        //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div><a href='#' class='btn btn-primary'></a>",
    ]) ?>



    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-5 qr-holder"><img src="<?= $settings['qrUrl'] ?>" class="qr-img"></div>
        <div class="col-lg-offset-1 col-lg-12" style="margin-top: 10px;"><p><i>Scan this with the Google Authenticator app.</i></p></div>
    </div>





    <?= $form->field($settings, 'qrUrl')->hiddenInput()->label(false); ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <a href="#" class="btn btn-primary" onclick="submitSettings();">Submit</a>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'settings-button']) ?>
        </div>
    </div>

    <?php
    Modal::begin([
        'id' => 'check-code',
        'size' => 'modal-sm',
        'clientOptions' => [
            'backdrop' => false, 'keyboard' => true
        ]
    ]); ?>

    <?= $form->field($settings, 'checkCode', ['template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>", 'labelOptions' => ['class' => 'col-lg-12'],])->textInput(/*['maxlength' => 6, 'type' => 'number']*/)->label('Google Authenticator code'); ?>

    <div>
        <a href="#" class="btn btn-primary" onclick="checkCode();">Send</a>
    </div>

    <?php Modal::end();
    ?>

    <?php ActiveForm::end(); ?>

    <?php //Pjax::end(); ?>



</div>