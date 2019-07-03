<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\FanResource;
use Intervention\Image\ImageManagerStatic as Image;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class ArticleController extends Controller
{
    //
    public function index(Request $request){

        $authors_ids = Author::where('appid', '=', $request->get('appid'))->where('state','=',1)->pluck('id');
        // dd($authors_ids);
        $topic_id = $request->get('topic');
        if( $topic_id ){
            $data = Article::where('appid', '=', $request->get('appid'))->where('topic_id','=',$topic_id)->whereIn('author_id',$authors_ids)->orderBy('id','desc')->simplePaginate(10);
        }else{
            $data = Article::where('appid', '=', $request->get('appid'))->whereIn('author_id',$authors_ids)->orderBy('id','desc')->simplePaginate(10);
        }
        // dd($data);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    //
    public function search(Request $request){
        $data = Article::search($request->get('q'))->where('appid', $request->get('appid'))->paginate(10);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $article = Article::findOrFail($id);
        $article->author;
        // $article->author_reward_adid = $article->author->reward_adid;
        // $article->author_banner_adid = $article->author->banner_adid;

        $share = $article->share;
        $article->video = '';
        $article->share_title = $share?$share->oneTitle:'';
        $article->share_cover = $share?$share->oneCover:'';
        $article->userlikearticle = $request->user()->hasLiked($article);
        // 
        $encode_qrcode = Hashids::encode( $request->get('appid'), $request->user()->id, $article->id, date("ymdHi") );
        $article->qrcode = $encode_qrcode;
        return response()->json($article);
    }

    /**
     * 获取推荐文章(小程序首页用)
     */
    public function recommend(){
        $authors_ids = Author::where('appid', '=', request()->get('appid'))->where('state','=',1)->pluck('id')->toArray();
    //  dd($authors_ids);
        $data = Article::where('recommend_at','>',Carbon::now())->where('state','=',1)->where('appid', '=', request()->get('appid'))->whereIn('author_id',$authors_ids)->orderBy('id','desc')->simplePaginate(10); //toSql();  where('view', '>', 10)->
        // dd($data);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function likeusers($id,Request $request)
    {
        $article = Article::findOrFail($id);
        $fans = $article->likers()->get();
        return response()->json($fans);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rewardusers($id,Request $request)
    {
        $article = Article::findOrFail($id);
        // $fans = $article->subscribers()->simplePaginate(10);
        $fans = $article->likers()->get();
        return response()->json(FanResource::collection($fans));
    }

    public function getposter(Request $request){
        // dd($request->aid);
        $article = Article::findOrFail($request->aid);
        $img = Image::canvas(600, 600, '#ffffff');
        // $img->text('荐读阅读：',50,30, function($font) {
        //     $font->file(storage_path('font.ttf'));
        //     $font->size(36);
        //     // $font->color('#fdf6e3');
        //     $font->color('#000000');
        // //        $font->align('center');
        //     $font->valign('top');
        // //        $font->angle(90);
        // });

        $img->text(str_limit($article->title,44),50,30, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(24);
            // $font->color('#fdf6e3');
            $font->color('#000000');
        //        $font->align('center');
            $font->valign('top');
        //        $font->angle(90);
        });

        $img2 = Image::make(storage_path('wxcms.png'));
        $img2->resize(100, 100);
        $img->insert($img2, 'bottom-right',20,20);


        $img->text('长按图片识别小程序二维码',450,520, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(18);
            // $font->color('#fdf6e3');
            $font->color('#000000');
            $font->align('right');
            $font->valign('bottom');
        //        $font->angle(90);
        });
        $img->text('快来跟我一起阅读吧',450,560, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(18);
            // $font->color('#fdf6e3');
            $font->color('#000000');
            $font->align('right');
            $font->valign('bottom');
        //        $font->angle(90);
        });

        return  $img->response();
    }

    public function poster(Request $request){
        
        $img = Image::canvas(600, 600, '#ffffff');

        $data = Article::where('appid', '=', $request->get('appid'))->orderBy('id','desc')->simplePaginate(10);

        $img->text('荐读阅读：',50,30, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(36);
            // $font->color('#fdf6e3');
            $font->color('#000000');
        //        $font->align('center');
            $font->valign('top');
        //        $font->angle(90);
        });

        foreach($data as $k=>$article){
            $img->text(str_limit('* '.$article->title,44),50,80+40*$k, function($font) {
                $font->file(storage_path('font.ttf'));
                $font->size(24);
                // $font->color('#fdf6e3');
                $font->color('#000000');
            //        $font->align('center');
                $font->valign('top');
            //        $font->angle(90);
            });
        }

        $img2 = Image::make(storage_path('wxcms.png'));
        $img2->resize(100, 100);
        $img->insert($img2, 'bottom-right',20,20);


        $img->text('长按图片识别小程序二维码',450,520, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(18);
            // $font->color('#fdf6e3');
            $font->color('#000000');
            $font->align('right');
            $font->valign('bottom');
        //        $font->angle(90);
        });
        $img->text('快来跟我一起阅读吧',450,560, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(18);
            // $font->color('#fdf6e3');
            $font->color('#000000');
            $font->align('right');
            $font->valign('bottom');
        //        $font->angle(90);
        });

        return  $img->response();
    }

}
