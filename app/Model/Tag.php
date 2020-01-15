<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $first_create_user_id 
 * @property string $tag_name 
 * @property string $tag_title 
 * @property string $tag_desc 
 * @property string $tag_keyword 
 * @property int $is_hot 
 * @property int $tag_type 
 * @property int $status 
 * @property int $used_count 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Tag extends Model
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'first_create_user_id', 'tag_name', 'tag_title', 'tag_desc', 'tag_keyword', 'is_hot', 'tag_type', 'status', 'used_count', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'first_create_user_id' => 'integer', 'is_hot' => 'integer', 'tag_type' => 'integer', 'status' => 'integer', 'used_count' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}