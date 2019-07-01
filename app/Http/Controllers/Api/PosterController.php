<?php

namespace App\Http\Controllers\Api;

use App\Models\Fan;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Auth;
use JWTAuth;
use EasyWeChat\Factory;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Str;



/**
 * 海报数据接口
 */
class PosterController extends Controller
{
  

  
  public function article($id, Request $request){
    $article = Article::findOrFail($id);
    return response()->json($this->getArticlePoster($request->get('appid'),$request->user(),$article));

  }

  public function getArticlePoster($appid,$user,$article){
    $encode_qrcode = Hashids::encode( $appid, $user->id, $article->id, date("ymdHi") );
    $article->qrcode = $encode_qrcode;
    return $this->defalutArticlePoster([], $user, $article);
  }

  public function defalutArticlePoster($app, $user, $article){
    // 
    $title_length = Str::length($article->title,'UTF-8');
    // dd($title_length);
    $poster = [
      'width'=>460,
      'height'=> 500,
      'clear'=> true,
      'views'=>[
		[
          'type'=> 'rect',
          'background'=> '#fff7f9',
          'top'=> 0,
          'width'=> 460,
          'height'=> 500,
          'left'=> 0
        ],
        [
          'type'=> 'rect',
          'background'=> '#ffffff',
          'top'=> 2,
          'width'=> 456,
          'height'=> 496,
          'left'=> 2
        ],
        [
          'type'=> 'image',
          'url'=>  $article->cover? $article->cover:'https://wx1.wechatrank.com/base64img/20190402233111.jpeg',
          'top'=> 70,
          'left'=> 28,
          'width'=> 400,
          'height'=> 320
        ],
        [
          'type'=> 'text',
          'content'=>  $article->title,
          'fontSize'=> 18,
          'lineHeight'=> 24,
          'color'=> '#333',
          'textAlign'=> 'left',
          'top'=> $title_length>22?16:26,
          'left'=> 28,
          'width'=> 387,
          'MaxLineNumber'=> 2,
          'breakWord'=> true,
          'bolder'=> true
        ],
		
        [
          'type'=> 'text',
          'content'=> str_replace(array(" ", "　", "\t", "\n", "\r", "\r\n", PHP_EOL), '', $article->intro),
          'fontSize'=> 18,
          'lineHeight'=> 24,
          'color'=> '#666',
          'textAlign'=> 'left',
          'top'=> 406,
          'left'=> 28,
          'width'=> 310,
          'MaxLineNumber'=> 3,
          'breakWord'=> true,
          'bolder'=> true
        ],
        [
          'type'=> 'image',
          'url'=> url('/qrcode/article/'.$article->qrcode),
          'top'=> 406,
          'left'=> 360,
          'width'=> 68,
          'height'=> 68
        ]
      ],
    ];
    
    return $poster;
  }

  
  public function defalutArticlePosterb($app, $user, $article){
    // 
    $poster = [
      'width'=>500,
      'height'=> 600,
      'clear'=> true,
      'views'=>[
        [
          'type'=> 'rect',
          'background'=> '#fff',
          'top'=> 2,
          'width'=> 496,
          'height'=> 596,
          'left'=> 2
        ],
        [
          'type'=> 'image',
          'url'=>  $article->cover? $article->cover:'https://wx1.wechatrank.com/base64img/20190402233111.jpeg',
          'top'=> 100,
          'left'=> 44,
          'width'=> 400,
          'height'=> 320
        ],
        [
          'type'=> 'text',
          'content'=>  $article->title,
          'fontSize'=> 18,
          'lineHeight'=> 21,
          'color'=> '#333',
          'textAlign'=> 'left',
          'top'=> 36,
          'left'=> 44,
          'width'=> 387,
          'MaxLineNumber'=> 2,
          'breakWord'=> true,
          'bolder'=> true
        ],
        [
          'type'=> 'text',
          'content'=> str_replace(array(" ","　","\t","\n","\r"), '', $article->intro),
          'fontSize'=> 18,
          'lineHeight'=> 21,
          'color'=> '#666',
          'textAlign'=> 'left',
          'top'=> 436,
          'left'=> 44,
          'width'=> 310,
          'MaxLineNumber'=> 3,
          'breakWord'=> true,
          'bolder'=> true
        ],
        [
          'type'=> 'image',
          'url'=> url('/qrcode/article/'.$article->qrcode),
          'top'=> 436,
          'left'=> 375,
          'width'=> 68,
          'height'=> 68
        ]
      ],
    ];
    
    return $poster;
  }
}
