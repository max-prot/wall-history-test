<?php

use yii\db\Migration;

/**
 * Class m230201_173729_add_user_id_column_post_table
 */
class m230201_173729_add_user_id_column_post_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'user_id', $this->integer(11)->after('id'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'user_id');
    }

}
