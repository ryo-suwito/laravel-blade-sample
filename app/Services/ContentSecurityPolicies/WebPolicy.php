<?php

namespace App\Services\ContentSecurityPolicies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class WebPolicy extends Basic {

    public function configure()
    {
        $this
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                //Keyword::UNSAFE_EVAL,
                'cdn.jsdelivr.net',
                'cdnjs.cloudflare.com',
                'https://maps.googleapis.com',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                'fonts.googleapis.com',
                'cdn.jsdelivr.net',
                'cdnjs.cloudflare.com',
            ])
            ->addDirective(Directive::IMG, [
                'data:', '*',
                'blob:',
            ])
            ->addDirective(Directive::FRAME, [
                Keyword::SELF,
            ]);
    }

}
