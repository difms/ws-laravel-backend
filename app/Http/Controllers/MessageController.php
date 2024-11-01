<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Events\PublicMessageEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class MessageController extends Controller
{
    /**
     * Отправка приватного сообщения.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $data = [
            'user_id' => $request->user_id,
            'data' => $request->data,
            'channel' => 'private'
        ];

        $publicData = [
            'data' => $request->data, // Сообщение для публичного канала
            'channel' => 'public'
        ];

        // Публикация в приватный канал
        Redis::publish('private_channel_' . $request->user_id, json_encode($data));

        Redis::publish('private_work_' . $request->user_id, json_encode($data));

        // Публикация в публичный канал
        Redis::publish('public_channel', json_encode($publicData));
    }

    // public function login(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
    //     }

    //     // Создаем payload для JWT  
    //     $payload = [
    //         'iss' => 'backend', // Issuer
    //         'sub' => $user->id, // Subject
    //         'exp' => time() + 3600, // Время истечения
    //         'iat' => time(),
    //         'app_id' => 'backend',
    //         'user_id' => $user->id,
    //     ];

    //     // Генерируем JWT токен с использованием секретного ключа
    //     $jwt = JWT::encode($payload, env('WEBSOCKET_SECRET_KEY'), 'HS256');

    //     return response()->json(['token' => $jwt]);
    // }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Создание токена и получение JWT
        $jwt = $user->createToken('token-name', ['server:update'])->plainTextToken;

        return response()->json(['token' => $jwt]);
    }
}
