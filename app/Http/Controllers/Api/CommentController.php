<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Http\Resources\CommentResource;
use Intervention\Image\ImageManagerStatic as Image;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function articleComments($id,Request $request)
    {
        $article = Article::findOrFail($id);
        // $user = $request->user(); 

        $data = $article->comments()->with('commented')->orderBy('id','desc')->simplePaginate(10);
        
        $comments = CommentResource::collection($data);
        return response()->json($comments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newArticleComment($id,Request $request)
    {
        $article = Article::findOrFail($id);
        $user = $request->user();
        $user->comment($article, $request->get('comment'), 0);

        return response()->json('ok');
    }


}
