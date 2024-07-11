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
    public static function trigger(App $app, ServerRequest $serverRequest, ServerResponse $serverResponse): void
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
                $serverResponse->getBody()->write(json_encode(["error" => "Database not found"]));
                $serverResponse->withStatus(500)->withHeader("Content-Type", "application/json");
            }
            $em = Container::getInstance()->get("entityManager");
            if (!$em) {
                $serverResponse->getBody()->write(json_encode(["error" => "Entity manager not found"]));
                $serverResponse->withStatus(500)->withHeader("Content-Type", "application/json");
            }
            $result = $em->query($userConfig->entity)
                ->where($userConfig->identifier)->is($decoded->identifier)
                ->where($userConfig->role)->is(self::$role)
                ->all();
            dd($result);
        } catch (\Exception $e) {
            $serverResponse->getBody()->write(json_encode(["error" => "Unable to decode token"]));
            $serverResponse->withStatus(401)->withHeader("Content-Type", "application/json");
        }




    }


}