<?php

use yii\db\Migration;

/**
 * Class m230823_110746_add_reactions_to_post
 */
class m230823_110746_add_reactions_to_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'reactions', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'reactions');
    }

}
