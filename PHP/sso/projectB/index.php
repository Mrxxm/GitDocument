<?php

$config = [
    'auth_server' => [
        'login_url' => 'http://auth.actself.me/index.php',
        'logout_url' => 'http://auth.actself.me/index.php?action=logout',
        'validate_ticket_url' => 'http://auth.actself.me/index.php?action=validate_ticket',
    ],
    'project' => [
        'name' => 'projectB',
        'index_url' => 'http://projectb.actself.me/index.php',
        'logout_url' => 'http://projectb.actself.me/index.php?action=local_logout',
    ],
];

$action = $_GET['action'] ?? null;
if ($action === null) {

    $user_info = get_session();

    if ($user_info !== false) {
        echo '已经登录, 您的昵称是:', $user_info['nickname'];
        ?>
        <a href="<?= $config['project']['logout_url'] ?>">退出</a>
        <?php
        exit();
    } else {
        $ticket = $_GET['ticket'] ?? null;
        if ($ticket !== null) {
            //1,去认证中心校验ticket
            $ticket = $_GET['ticket'];
            $url = $config['auth_server']['validate_ticket_url'];
            $url .= "&ticket=$ticket";

            $response_body = curl_get($url);
            if ($response_body === false) {
                show_error('ticket校验失败。');
                exit();
            }

            $user_info = json_decode($response_body, true);

            //2,生成局部会话
            save_session($user_info);
            $random = bin2hex(random_bytes(16));
            $user_id = $user_info['user_id'];
            $token = "${user_id}|${random}";
            $expire = time() + 24 * 60 * 60;
            get_cache()->set("token:$user_id", $token, $expire);
            setcookie('token', $token, $expire);

            echo '登录成功, 您的昵称是:', $user_info['nickname'];
            ?>
            <a href="<?= $config['project']['logout_url'] ?>">退出</a>
            <?php
            exit();
        } else {
            ?>
            <a href="<?= $config['auth_server']['login_url'] . '?source=' . $config['project']['name'] . '&return_url=' . urlencode($config['project']['index_url']) ?>">登录</a>
            <?php
        }
    }
} else if ($action === 'logout') {
    // 接收到认证中心要求注销的请求,注销局部会话
    $user_id = $_GET['user_id'] ?? null;
    if ($user_id) {
        $token_key = "token:$user_id";
        $user_info_key = "user_info:$user_id";
        $memcache = get_cache();
        $memcache->deleteMulti([$token_key, $user_info_key]);
    }
    exit();
} else if ($action === 'local_logout') {
    $user_info = get_session();
    $user_id = $user_info['user_id'];
    if ($user_info !== false) {
        // 本地退出。
        // 1,注销局部会话。

        $token_key = "token:$user_id";
        $user_info_key = "user_info:$user_id";
        $memcache = get_cache();
        $memcache->deleteMulti([$token_key, $user_info_key]);

        // 2,并跳转到认证中心统一退出
        $url = $config['auth_server']['logout_url'] . "&return_url=" . urlencode($config['project']['index_url']);
        redirect($url);
    } else {
        redirect($config['project']['index_url']);
    }
} else {
    show_error('非法请求。');
}

function get_session()
{
    $token = $_COOKIE['token'] ?? null;

    if ($token !== null) {
        list($user_id, $random) = explode('|', $token);
        $memcache = get_cache();
        if ($memcache->get("token:$user_id") !== $token) {
            return false;
        } else {
            $user_info = $memcache->get("user_info:$user_id");
            return $user_info ?? false;
        }
    };
    return false;
}

function save_session($user_info)
{
    $user_id = $user_info['user_id'];
    $memcache = get_cache();
    $memcache->set("user_info:$user_id", $user_info, time() + 24 * 60 * 60);
}

function get_cache()
{
    $memcache = new \Memcached();
    $memcache->addServer('127.0.0.1', 11212);
    return $memcache;
}

function curl_get($url)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT_MS => 200,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $response_body = curl_exec($ch);
    if (curl_errno($ch) !== 0) {
        // 记录日志,方便debug
    }
    curl_close($ch);
    return $response_body;
}

function redirect($url)
{
    header('Location: ' . $url, true, 302);
}

function show_error($message)
{
    echo $message;
    exit();
}