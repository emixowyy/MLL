<?php
header('Content-Type: application/json');
require 'db.php';

class Auth {
    public static function createSessionToken($uid, $pdo) {
        $sessiontoken = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 86400);
        $sessiontokenin = $pdo->prepare("INSERT INTO user_sessions (uid,token,expires_at) VALUES (?,?,?)");
        $sessiontokenin -> execute([$uid,$sessiontoken,$expires]);
        return $sessiontoken;
    }

    public static function validateSessionToken($sessiontoken, $pdo) {
        $getuid = $pdo->prepare("SELECT uid FROM user_sessions WHERE token = ? AND expires_at > NOW()");
        $getuid -> execute([$sessiontoken]);
        $uid = $getuid->fetch(PDO::FETCH_ASSOC);
        return $uid ? $uid['uid'] : false;

    }
    public static function createRefreshToken($uid, $pdo) {
        $refreshtoken = bin2hex(random_bytes(64));
        $hashrefreshtoken = hash('sha256', $refreshtoken);
        $expires = date('Y-m-d H:i:s', time() + 14*24*3600);
        $refreshtokenin = $pdo->prepare("INSERT INTO user_refresh_tokens (uid, token, expires_at) VALUES (?,?,?)");
        $refreshtokenin->execute([$uid,$hashrefreshtoken,$expires]);
        return $refreshtoken;
    }
    public static function validateRefreshToken ($refreshtoken, $pdo) {
        $hashrefreshtoken = hash('sha256', $refreshtoken);
        $getuid = $pdo->prepare("SELECT uid FROM user_refresh_tokens WHERE token = ? AND expires_at > NOW()");
        $getuid->execute([$hashrefreshtoken]);
        $uid = $getuid->fetch(PDO::FETCH_ASSOC);
        return $uid ? $uid['uid'] : false;
    }
    public static function refreshSessionToken($refreshtoken, $pdo) {
        $uid = self::validateRefreshToken($refreshtoken, $pdo);
        if (!$uid) return false;

        $refreshsession = $pdo->prepare("DELETE FROM user_sessions WHERE uid = ?");
        $refreshsession->execute([$uid]);
        $newsessiontoken = self::createSessionToken($uid, $pdo);
        return ['uid' => $uid, 'sessiontoken' => $newsessiontoken ];
    }
    public static function removeExpiredRefreshTokens($pdo) {
        $removeToken = $pdo->prepare("DELETE FROM user_refresh_tokens WHERE expires_at <= NOW()");
        $removeToken->execute();
    }
}