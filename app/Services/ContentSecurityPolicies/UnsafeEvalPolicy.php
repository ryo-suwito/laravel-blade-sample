<?php

namespace App\Services\ContentSecurityPolicies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class UnsafeEvalPolicy extends WebPolicy {

    public function configure() {
        parent::configure();
        $this->addDirective(Directive::SCRIPT, [Keyword::UNSAFE_EVAL]);
    }

}
