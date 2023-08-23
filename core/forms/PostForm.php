<?php

namespace app\core\forms;

use yii\base\Model;

class PostForm extends Model
{
    public $author;
    public $message;
    public $captcha;

    public function rules()
    {
        return [
            [['message', 'author'], 'required'],
            [['author'], 'string', 'min' => 2, 'max' => 35],
            [['message'], 'string', 'min' => 5],
            [['captcha'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'author' => 'Имя автора',
            'captcha' => 'Код с картинки:',
        ];
    }

}