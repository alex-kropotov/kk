<?php
declare(strict_types=1);

namespace App\Domain\Service;

use Back\Domain\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    public function __construct(
        private readonly string $jwtSecret,
        private readonly int $jwtExpiration = 86400 // 24 hours
    ) {}

    public function authenticate(string $username, string $password): ?array
    {
        $user = User::where('username', $username)->first();

        if (!$user || !password_verify($password, $user->password_hash)) {
            return null;
        }

        $token = $this->generateToken($user);

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'agent_code' => $user->agent_code,
                'created_at' => $user->created_at->toIso8601String()
            ]
        ];
    }

    public function generateToken(User $user): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + $this->jwtExpiration,
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'agent_code' => $user->agent_code
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    public function validateToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
