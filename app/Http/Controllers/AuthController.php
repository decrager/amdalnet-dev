<?php
/**
 * File AuthController.php
 *
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    public function resetPasswordRedirect(Request $request)
    {
        return redirect(route('password.reset.redirect').'?token='.$request->token.'&email='.$request->email);
    }
}
