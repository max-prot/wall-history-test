<?php

namespace app\core\services\user;

use app\core\forms\user\UserForm;
use Faker\Factory;
use Faker\Generator;
use Yii;
use app\core\repositories\user\UserRepository;
use yii\base\Exception;

class UserService
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $userId
     * @return void
     * @throws Exception
     */
    public function save($userId)
    {
        $this->userRepository->save($userId);
    }

    /**
     * @param $id
     * @param $isAdmin
     * @return void
     */
    public function setAdmin($id, $isAdmin)
    {

        $user = $this->userRepository->getById($id);
        $user->setIsAdmin($user->getId(), $isAdmin);

    }

    /**
     * @param $id
     * @param $isUsed
     * @return void
     */
    public function setUsed($id, $isUsed)
    {
        $user = $this->userRepository->getById($id);
        $user->setIsUsed($user->getId(), $isUsed);
    }
}