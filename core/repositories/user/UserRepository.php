<?php

namespace app\core\repositories\user;

use app\core\entities\User;
use yii\base\Exception;
use yii\web\IdentityInterface;

class UserRepository
{
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     * @return User|IdentityInterface|null
     */
    public function getById($id)
    {
       return User::findIdentity($id);
    }


    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->user::getUsers();
    }

    /**
     * @param $userId
     * @return void
     * @throws Exception
     */
    public function save($userId)
    {
        $this->user->make($userId);
    }
}