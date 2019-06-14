<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeVoted;
use Overtrue\LaravelFollow\Traits\CanBeBookmarked;
use Laravel\Scout\Searchable;
use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;

class Article extends Model implements Commentable
{
    use CanBeLiked, CanBeFavorited, CanBeVoted, CanBeBookmarked;
    use SoftDeletes;
    use Searchable, HasComments;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','author_id','topic_id',
    ];

    /**
     * 作家
     */
    public function author()
    {
        return $this->belongsTo(Author::class,'author_id');
    }
    /**
     * 自定义分享
     */
    public function share()
    {
        return $this->belongsTo(Share::class,'share_id');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title'=>$this->title,
            'intro'=>$this->intro,
        ];
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    
    // public function canBeRated(): bool
    // {
    //     return true; // default false
    // }
}
