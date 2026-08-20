<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SurveysController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $secure_hash = md5($user->id.'-'.Config::get('services.cpx.secure_hash'));

        $cpxUrl = 'https://offers.cpx-research.com/index.php?'
            .http_build_query([
                'app_id' => Config::get('services.cpx.app_id'),
                'ext_user_id' => $user->id,
                'secure_hash' => $secure_hash,
                'username' => $user->name,
                'email' => $user->email,
            ]);

        $timewallUrl = 'https://timewall.io/offerwall?'
            .http_build_query([
                'site' => Config::get('services.timewall.placement_id'),
                'user' => $user->id,
            ]);

        $bitlabsToken = Config::get('services.bitlabs.app_token');

        return view('surveys.index', compact('user', 'cpxUrl', 'timewallUrl', 'bitlabsToken'));
    }

    public function bitlabs()
    {
        $user = Auth::user();
        $token = Config::get('services.bitlabs.app_token');
        $iframeUrl = 'https://web.bitlabs.ai/?token='.$token.'&uid='.$user->id;

        return view('surveys.bitlabs', compact('user', 'iframeUrl'));
    }
}
