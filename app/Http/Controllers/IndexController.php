<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Jul-21
 * Time: 14:04
 */

namespace App\Http\Controllers;

use App\Helpers\H;
use Illuminate\Http\Request;

class IndexController extends BaseController {

    public function index(Request $request) {
        if (H::isLogin()) {
            return redirect(route("cms.dashboard"));
        } else {
            return redirect(route("cms.login"));
        }
    }

}