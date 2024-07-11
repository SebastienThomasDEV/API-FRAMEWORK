<?php

namespace Sthom\Back\Annotations\middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\App;
use Sthom\Back\Container;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Required implements RouteMiddlewareAnnotation
{

    static string $role;
    public function __construct(
        string $role
    ) {
        self::$role = $role;
    }

    public final function getRole(): string
    {
        return $this->role;
    }


    #[NoReturn]
    public static function trigger(App $app, ServerRequest $serverRequest, ServerResponse $serverResponse):  bool
    {
        $userConfig = Container::getInstance()->get("user");
        $token = $serverRequest->getHeader("Authorization");
        if (!$userConfig) {
            $serverResponse->getBody()->write(json_encode(["error" => "Unauthorized user not found"]));
            $serverResponse->withStatus(401)->withHeader("Content-Type", "application/json");
        }
        if (empty($token)) {
            $serverResponse->getBody()->write(json_encode(["error" => "Unauthorized token not found"]));
            $serverResponse->withStatus(401)->withHeader("Content-Type", "application/json");
        }
        $token = trim(str_replace("Bearer", "", $token[0]));
        $secretKey = $_ENV["JWT_SECRET_KEY"];
        $algorithm = $_ENV["JWT_ALGORITHM"]; // Assuming this is set to 'HS256', 'HS384', or 'HS512'

        try {
            $decoded = JWT::decode($token, new Key($secretKey, $algorithm));
            $db = Container::getInstance()->get("database");
            if (!$db) {
                $message = json_encode(["error" => "Database not found"]);
                $serverResponse->getBody()->write($message);
                return false;
            }
            $em = Container::getInstance()->get("entityManager");
            if (!$em) {
                $message = json_encode(["error" => "Entity manager not found"]);
                $serverResponse->getBody()->write($message);
                return false;
            }
            $result = $em->query($userConfig->entity)->find($decoded->id);
            if (!$result) {
                $message = json_encode(["error" => "User not found"]);
                $serverResponse->getBody()->write($message);
                return false;
            }
            if ($result->getRole() !== self::$role) {
                $message = json_encode(["error" => "Unauthorized role"]);
                $serverResponse->getBody()->write($message);
                return false;
            }
        } catch (\Exception $e) {
            $message = json_encode(["error" => "Unauthorized"]);
            $serverResponse->getBody()->write($message);
            return false;
        }
        return true;
    }


}