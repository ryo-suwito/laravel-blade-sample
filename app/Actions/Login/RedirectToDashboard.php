<?php

namespace App\Actions\Login;

use App\Helpers\H;

class RedirectToDashboard
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        H::flashSuccess('You have logged in successfully.', true);

        return redirect()->route('cms.dashboard');
    }
}
