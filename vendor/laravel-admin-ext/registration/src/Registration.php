<?php

namespace Encore\Admin\Registration;

use Carbon\Carbon;
use Encore\Admin\Extension;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class Registration extends Extension
{
    public $name = 'registration';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    /**
     * 因为laravel v5.7版本中才有`URL::temporarySignedRoute()`方法，为了兼容更低的版本，这里要扩展这个方法
     */
    public static function macroUrlGenerator()
    {
        UrlGenerator::macro('temporarySignedRoute', function ($name, $expiration, $parameters = []) {

            $parameters = $this->formatParameters($parameters);

            if ($expiration) {

                if ($expiration instanceof \DateInterval) {
                    $expiration = Carbon::now()->add($expiration);
                }

                $expires = $expiration instanceof \DateTimeInterface
                    ? $expiration->getTimestamp()
                    : Carbon::now()->addSeconds($expiration)->getTimestamp();

                $parameters = $parameters + ['expires' => $expires];
            }

            ksort($parameters);

            return $this->route($name, $parameters + [
                    'signature' => hash_hmac('sha256', $this->route($name, $parameters), config('app.key')),
                ]);
        });

        Arr::macro('query', function ($array) {
            return http_build_query($array, null, '&', PHP_QUERY_RFC3986);
        });

        UrlGenerator::macro('hasValidSignature', function (Request $request, $absolute = true) {
            $url = $absolute ? $request->url() : '/'.$request->path();

            $original = rtrim($url.'?'.Arr::query(
                    Arr::except($request->query(), 'signature')
                ), '?');

            $expires = $request->query('expires');

            $signature = hash_hmac('sha256', $original, config('app.key'));

            return  hash_equals($signature, (string) $request->query('signature', '')) &&
                ! ($expires && Carbon::now()->getTimestamp() > $expires);
        });
    }
}