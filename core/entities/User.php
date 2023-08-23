<?php

namespace app\core\entities;

use Faker\Factory;
use Yii;
use yii\base\Exception;

/**
 *
 * @property-write mixed $admin
 * @property-write mixed $used
 */
class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $isAdmin;
    public $isUsed;
    public $balance;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::getUsers()[$id]) ? new static(self::getUsers()[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::getUsers() as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        foreach (self::getUsers() as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function withdrawBalance($sum)
    {
        // Загружаем данные из файла в переменную $data
        $data = self::getUsers();


        if ($sum > $this->balance) {
            throw new \DomainException("Недостаточно средств на счету");
        }

        $this->balance -= $sum;
        $data[$this->id]['balance'] = $this->balance;

        // Сохраняем измененный массив в файл
        $this->saveDataInFile($data);
    }

    public function getPrettyBalance()
    {
        return Yii::$app->formatter->asCurrency($this->balance, "USD");
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->authKey === $authKey;
    }

    public static function getUsers()
    {
        return require Yii::getAlias("@app/config/users.php");
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateAuthKey(): string
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateApiKey(): string
    {
        return hash("sha1", Yii::$app->security->generateRandomString() . time());
    }

    /**
     * @param $userId
     * @return void
     * @throws Exception
     */
    public function make($userId)
    {
        $faker = Factory::create("ru_RU");

        // Загружаем данные из файла в переменную $data
        $data = self::getUsers();

        // Создаем новый элемент
        $newItem['id'] = $userId;
        $newItem['username'] = $faker->userName;
        $newItem['password'] = $faker->password;
        $newItem['authKey'] = $this->generateAuthKey();
        $newItem['accessToken'] = $this->generateApiKey();
        $newItem['isAdmin'] = 0;
        $newItem['isUsed'] = 0;

        // Добавляем новый элемент в конец массива
        $data[$userId] = $newItem;

        // Сохраняем измененный массив в файл
        $this->saveDataInFile($data);
    }

    /**
     * @param $userId
     * @param $isAdmin
     * @return void
     */
    public function setIsAdmin($userId, $isAdmin)
    {
        // Загружаем данные из файла в переменную $data
        $data = self::getUsers();

        $data[$userId]['isAdmin'] = $isAdmin ? 0 : 1;

        // Сохраняем измененный массив в файл
        $this->saveDataInFile($data);
    }

    /**
     * @param $userId
     * @param $isUsed
     * @return void
     */
    public function setIsUsed($userId, $isUsed)
    {
        // Загружаем данные из файла в переменную $data
        $data = self::getUsers();

        $data[$userId]['isUsed'] = $isUsed ? 0 : 1;

        // Сохраняем измененный массив в файл
        $this->saveDataInFile($data);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $password;
    }

    /**
     * @param $data
     * @return void
     */
    private function saveDataInFile($data)
    {
        file_put_contents(Yii::getAlias("@app/config/users.php"), '<?php return ' . var_export($data, true) . ';');
    }
}
