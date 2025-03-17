<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GithubSocialiteController extends Controller
{
    /**
     * Redirect to GitHub authentication page
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle callback from GitHub
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Find existing user or create new one
            $user = User::updateOrCreate(
                ['github_id' => $githubUser->id],
                [
                    'name' => $githubUser->name ?? $githubUser->nickname,
                    'email' => $githubUser->email ?? "{$githubUser->id}@github.user",
                    'github_id' => $githubUser->id,
                    'password' => bcrypt(rand(1, 10000)), // Random password as it's not needed
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]
            );

            // Login the user
            Auth::login($user);

            return redirect('/dashboard');

        } catch (Exception $e) {
            Log::error('GitHub login error: ' . $e->getMessage());
            return redirect('login')->withErrors('GitHub authentication failed');
        }
    }
}
