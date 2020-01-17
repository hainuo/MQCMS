<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\TagPostRelation;

class Post extends \App\Model\Post
{
    public function tagIds()
    {
        return $this->hasMany(TagPostRelation::class, 'post_id', 'id');
    }

    public function userInfo()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}