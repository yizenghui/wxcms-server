<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeVoted;
use Overtrue\LaravelFollow\Traits\CanBeBookmarked;
use Overtrue\LaravelFollow\Traits\CanBeSubscribed;
use Laravel\Scout\Searchable;
use App\Contracts\Commentable;

class Article extends Model implements Commentable 
{
    use CanBeLiked, CanBeFavorited, CanBeVoted, CanBeBookmarked, CanBeSubscribed;
    use SoftDeletes;
    use Searchable;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appid','title','author_id','topic_id', 'intro', 'cover','state','comment_status','recommend_at','body',
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


    public function readlogs()
    {
         return $this->bookmarkers(\App\Models\Article::class);
    }
    
    public function likelogs()
    {
         return $this->likers(\App\Models\Article::class);
    }
    public function rewardlogs(){
        return $this->subscribers(\App\Models\Fan::class);
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

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function primaryId(): string
    {
        return (string)$this->getAttribute($this->primaryKey);
    }

    public function totalCommentsCount(): int
    {
        if (0) {
            return $this->comments()->count();
        }

        return $this->comments()->approvedComments()->count();
    }

}
