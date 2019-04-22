<?php

$users = [
    'user1' => ['user_id' => 'user1', 'nickname' => '张三', 'role' => 'admin', 'password' => 'password1'],
    'user2' => ['user_id' => 'user2', 'nickname' => '李四', 'role' => 'admin', 'password' => 'password2'],
];

$systems = [
    'projectA' => ['logout_url' => 'http://projecta.actself.me/index.php?action=logout'],
    'projectB' => ['logout_url' => 'http://projectb.actself.me/index.php?action=logout'],
];

session_start();

$action = $_GET['action'] ?? 'index';

if ($action === 'index') {
    // 首页
    $source = $_GET['source'];
    $return_url = $_GET['return_url'];

    if (isset($_SESSION['user_info'])) {
        $user_info = $_SESSION['user_info'];
        $ticket = generate_ticket($user_info);
        header('Location: ' . $return_url . '?ticket=' . $ticket, true, 302);
        exit();
    } else {
        ?>
        <form action="/index.php?action=login" method="post">
            <label>用户名</label><input type="text" title="用户名" name="user_id"/>
            <label>密码</label><input type="password" title="密码" name="password"/>
            <input type="hidden" name="return_url" value="<?= $return_url ?>"/>
            <input type="hidden" name="source" value="<?= $source ?>"/>
            <button type="submit">登录</button>
        </form>
        <?php
    }
}
else if ($action === 'login') {
    // 登录
    $return_url = $_POST['return_url'];
    $source = $_POST['source'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $user_info = validate($user_id, $password);
    if ($user_info !== false) {
        // 登录成功后

        // 1,生成全局会话
        $_SESSION['user_info'] = $user_info;

        // 2,记录一下用户已经登录了哪个系统
        save_logined_system($source);

        // 3,跳转回用户正在使用的系统
        $ticket = generate_ticket($user_info);
        header('Location: ' . $return_url . '?ticket=' . urlencode($ticket), true, 302);
        exit();
    } else {
        show_error('账号密码不正确。');
    }
}
else if ($action === 'validate_ticket') {
    // 校验ticket
    $ticket = $_GET['ticket'];
    $user_info = validate_ticket($ticket);
    if ($user_info !== false) {
        echo json_encode($user_info);
        exit();
    } else {
        show_error('ticket不正确。接收到的ticket:' . $ticket . ' 正确ticket:' . $_SESSION['ticket']);
    }
}
else if ($action === 'logout') {
    // 退出登录
    $return_url = $_GET['return_url'];

    $user_info = $_SESSION['user_info'] ?? null;
    if ($user_info === null) {
        header('Location: ' . $return_url, true, 302);
        exit();
    }

    // 1,注销全局会话
    unset($_SESSION['user_info']);

    // 2,注销所有已经登录系统的局部会话
    global $systems;
    $systems_logined = get_logined_system();
    foreach ($systems_logined as $system) {
        $system_info = $systems[$system];
        $logout_url = $system_info['logout_url'] . '&user_id=' . $user_info['user_id'];
        curl_get($logout_url);
    }

    // 跳转
    header('Location: ' . $return_url, true, 302);
    exit();
}
else {
    show_error('非法请求。');
}

function generate_ticket($user_info)
{
    $ticket = $user_info['user_id'] . '|' . bin2hex(random_bytes(16));
    $ticket_key = "ticket:" . $user_info['user_id'];
    $expire = time() + 5 * 60;
    get_cache()->set($ticket_key, $ticket, $expire);
    return $ticket;
}

function validate_ticket($ticket)
{
    list($user_id, $random) = explode('|', $ticket);
    $ticket_key = "ticket:$user_id";
    if (get_cache()->get($ticket_key) !== $ticket) {
        return false;
    }

    global $users;
    return $users[$user_id];
}

function validate($user_id, $password)
{
    global $users;
    if (!isset($users[$user_id])) {
        return false;
    }
    $user_info = $users[$user_id];
    return ($password === $user_info['password']) ? $user_info : false;
}

function save_logined_system($source)
{
    $systems_logined = $_SESSION['systems_logined'] ?? [];
    $systems_logined[] = $source;
    $_SESSION['systems_logined'] = $systems_logined;
}

function get_logined_system()
{
    return $_SESSION['systems_logined'] ?? [];
}

function curl_get($url)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT_MS => 200,
    ]);
    curl_exec($ch);
    if (curl_errno($ch) !== 0) {
        // 记录日志,方便debug
    }
    curl_close($ch);
}

function get_cache()
{
    $memcache = new \Memcached();
    $memcache->addServer('127.0.0.1', 11211);
    return $memcache;
}

function show_error($message)
{
    echo $message;
    exit();
}
