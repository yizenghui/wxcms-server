<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Article;
use App\Models\Comment;
use App\Http\Resources\CarouselResource;
use App\Http\Resources\CommentResource;
use Carbon\Carbon;


class CommentController extends Controller
{


    public function index(Request $request){
        $data = Carousel::where('appid','=',$request->get('appid'))->where('state','=',1)
        ->orderBy('priority','desc')
        ->simplePaginate(10);
        $articles = CarouselResource::collection($data);
        return response()->json($articles);
    }

    
    public function article($commentable_id, Request $request){
        $request->user();
        $article = Article::findOrFail($commentable_id);
        $sort = $request->get('sort');
        $sort = $sort == 'rank'?'rank':'id';
        $data = $article->comments()->orderBy($sort,'desc')->with('commented')->simplePaginate(10);
        $comments = CommentResource::collection($data);
        return response()->json($comments);
    }

    // 提交评论
    public function postArticle($commentable_id, Request $request){
        $user = $request->user();
        $article = Article::findOrFail($commentable_id);
        $comment = $request->get('comment');
        // dd($comment);
        $reply_id = intval($request->get('reply_id'));
        // return response()->json($user);
        // $user->comment($article, $comment, $reply_id);
        $new_comment = $user->comment($article, $comment, $reply_id);
        if($reply_id){
            $quote = $new_comment->quote;
            $quote->reply ++;
            $quote->save();
        }
        $article->commented ++;
        $article->commented_at = Carbon::now();
        $article->save();
        return response()->json(new CommentResource($new_comment));
    }


    public function top(Request $request){

        $data = Carousel::where('appid','=',$request->get('appid'))->where('state','=',1)
        ->orderBy('priority','desc')
        ->simplePaginate(10);
        $articles = CarouselResource::collection($data);
        return response()->json($articles);
    }

    
    public function like($id,Request $request){
        $user = $request->user();
        $comment = Comment::findOrFail($id);
        if(!$user->hasLiked($comment)){
            $user->like($comment);
            $comment->like ++;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        }
        return response()->json(['ok']);
    }
    
    public function unlike($id,Request $request){
        $user = $request->user();
        $comment = Comment::findOrFail($id);
        if($user->hasLiked($comment)){
            $user->unlike($comment);
            $comment->like --;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        }
        return response()->json(['ok']);
    }
    
    // 取消投票
    public function cancelVote($id,Request $request){
        $user = $request->user();
        $comment = Comment::findOrFail($id);
        if($user->hasUpvoted($comment)){ //如果已经投了认同票
            $user->cancelVote($comment); 
            $comment->upvote --;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        }elseif($user->hasDownvoted($comment)){
            $user->cancelVote($comment); //如果已经投了否定票
            $comment->downvote --;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        }
        return response()->json(['ok']);
    }
    //认同投票
    public function upvote($id,Request $request){
        $user = $request->user();
        $comment = Comment::findOrFail($id);
        // $comment->upvoters()->get();
        // $comment->downvoters()->get();
        if(!$user->hasUpvoted($comment)){
            $user->upvote($comment);
            $comment->upvote ++;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        } 
        return response()->json(['ok']);
    }
    //否定投票
    public function downvote($id,Request $request){
        $user = $request->user();
        $comment = Comment::findOrFail($id);
        if(!$user->hasDownvoted($comment)){
            $user->downvote($comment);
            $comment->downvote ++;
            // 排序等于喜欢人数+认同人数+否定人数(暂时性的)
            $comment->rank = $comment->like + $comment->upvote + $comment->downvote;
            $comment->save();
        } 
        return response()->json(['ok']);
    }
}
