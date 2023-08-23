<?php

namespace app\core\entities;

use Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use app\core\helpers\IPHideHelper;

/**
 * @property int $id
 * @property string $user_id Which user's space is the comment left in
 * @property string $author Author name
 * @property string $message
 * @property string $ip Author IP
 * @property string $reactions Reactions to the post as a JSON string
 * @property-read mixed $displayedIP
 * @property-read string $formattedDate
 * @property int $created_at
 */
class Post extends ActiveRecord
{
    /**
     * @param $userId
     * @param $author
     * @param $message
     * @param $ip
     * @return static
     */
    public static function make($userId, $author, $message, $ip): Post
    {
        $entity = new static();
        $entity->user_id = $userId;
        $entity->author = $author;
        $entity->message = $message;
        $entity->ip = $ip;
        $entity->created_at = time();

        return $entity;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return Html::encode($this->author);
    }

    public function getDisplayedIP()
    {
        return IPHideHelper::hide($this->ip);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getFormattedDate(): string
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }

    /**
     * Add or update a reaction.
     *
     * @param string $emoji
     * @param int $count
     */
    public function addReaction(string $emoji, int $count): void
    {
        $reactions = $this->getReactions();

        $found = false;
        foreach ($reactions as &$reaction) {
            if ($reaction['emoji'] === $emoji) {
                $reaction['count'] += $count;
                $found = true;
                break;
            }
        }

        // If the reaction doesn't exist, add it.
        if (!$found) {
            $reactions[] = ['emoji' => $emoji, 'count' => $count];
        }

        $this->reactions = json_encode($reactions);
    }

    /**
     * Get reactions as a PHP array.
     *
     * @return array[] An array of reactions, where each reaction is an associative array with:
     *                  - 'emoji' (string) The emoji representation of the reaction.
     *                  - 'count' (int) The number of times the reaction has been given.
     */
    public function getReactions(): array
    {
        return json_decode($this->reactions, true) ?: [];
    }


    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'post';
    }

}