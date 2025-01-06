<?php

namespace App\Http\Controllers;
use App\Services\ResponseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController {
    const TOKEN_NAME = 'Personal Access Token';
    const REGISTER_ERROR_BAG = 'register';
    const REGISTER_VALIDATION = [
        'name' => ['required', 'string'],
        'email' => ['required', 'string', 'unique:users'],
        'password' => ['required', 'string'],
        'c_password' => ['required', 'same:password'],
    ];

    const LOGIN_ERROR_BAG = 'login';
    const LOGIN_VALIDATION = [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
        'remember_me' => ['boolean'],
    ];

    const TOKEN_TYPE = 'Bearer';

    /**
     * Create user
     *
     * @api POST /api/auth/register
     *
     * @body {
     *  "name":       "string",
     *  "email":      "string",
     *  "password":   "string",
     *  "c_password": "string"
     * }
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-12-18
     */
    public function register(Request $request) {
        try {
            $request->validateWithBag(
                self::REGISTER_ERROR_BAG,
                self::REGISTER_VALIDATION
            );

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $expires = now()->addDay();

            return ResponseService::success(
                data: $this->generateUserTokenResponse($user, $expires),
                message: __('Successfully created user!'),
                code: 201
            );
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Get the authenticated User
     *
     * @api POST /api/auth/user
     *
     * @body {
     *  "email":       "string",
     *  "password":    "string",
     *  "remember_me": "boolean"
     * }
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-12-18
     */
    public function login(Request $request) {
        try {

            $request->validateWithBag(
                self::LOGIN_ERROR_BAG,
                self::LOGIN_VALIDATION
            );

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                throw new Exception(__('Unauthorized'), 401);
            }

            $user = Auth::user();
            $user->tokens()->delete();

            $expires = $request->remember_me ? now()->addMonth() : now()->addDay();

            return ResponseService::success(
                data: $this->generateUserTokenResponse($user, $expires),
                message: __('Successfully logged in!')
            );
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Get the authenticated User
     *
     * @api GET /api/auth/user
     *
     * @header Authorization: Bearer {token}
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-12-18
     */
    public function user(Request $request) {
        try {
            return ResponseService::success($request->user());
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }


    /**
     * Log the user out (Invalidate the token)
     *
     * @api GET /api/auth/logout
     *
     * @header Authorization: Bearer {token}
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-12-18
     */
    public function logout(Request $request) {
        try {
            $request->user()->tokens()->delete();
            return ResponseService::success(data: [], message: __('Successfully logged out'));
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Refresh a token
     *
     * @param  Authenticatable $user
     * @param  Carbon $expires
     *
     * @return array{
     *  user: array{
     *      id: int,
     *      name: string,
     *      email: string,
     *      token: array{
     *          access_token: string,
     *          token_type: string,
     *          expires_at: string
     *      }
     *  }
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2025-01-04
     */
    private function generateUserTokenResponse(Authenticatable $user, Carbon $expires) {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->rolesList,
                'permissions' => $user->permissionsList,
                'token' => [
                    'access_token' => $user->createToken(self::TOKEN_NAME, ['*'], $expires)->plainTextToken,
                    'token_type' => self::TOKEN_TYPE,
                    'expires_at' => $expires->toDateTimeString(),
                ],
            ],
        ];
    }
}
