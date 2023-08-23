<?php

namespace app\core\forms;

use yii\base\Model;

class ReactionForm extends Model
{
    const REACTION_PRICE_PER_ONE = 1;


    public $reaction;
    public $quantity;

    public function rules()
    {
        return [
            [['reaction', 'quantity'], 'required'],
            [['quantity'], 'integer'],
            ['quantity', 'compare', 'compareValue' => 0, 'operator' => '!=', 'message' => 'Quantity cannot be zero.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'reaction' => 'Реакция',
            'quantity' => 'Количество',
        ];
    }

    public function calculateReactionCost()
    {
        return $this->quantity * self::REACTION_PRICE_PER_ONE;
    }

}