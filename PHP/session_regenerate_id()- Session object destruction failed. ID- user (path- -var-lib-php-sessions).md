## session_regenerate_id(): Session object destruction failed. ID: user (path: /var/lib/php/sessions)

报错位置：

`/var/www/api/vendor/symfony/http-foundation/Session/Storage/NativeSessionStorage.php in handleError at line 197`

```
 public function regenerate($destroy = false, $lifetime = null)
    {
        // Cannot regenerate the session ID for non-active sessions.
        if (\PHP_SESSION_ACTIVE !== session_status()) {
            return false;
        }

        if (null !== $lifetime) {
            ini_set('session.cookie_lifetime', $lifetime);
        }

        if ($destroy) {
            $this->metadataBag->stampNew();
        }

######## 这一行报错
        $isRegenerated = session_regenerate_id($destroy);

        // The reference to $_SESSION in session bags is lost in PHP7 and we need to re-create it.
        // @see https://bugs.php.net/bug.php?id=70013
        $this->loadSession();

        return $isRegenerated;
    }
```

问题定位(session存储在redis缓存中)：

```
$app->register(new \Silex\Provider\SessionServiceProvider());
$app['session.storage.handler'] = function () use ($biz) {
    return new AdminUI\Session\RedisSessionHandler($biz['redis'], 3600, [
        'key_prefix' => 'ADMIN_SESS:',
    ]);
};
```

报错位置定位(当redis存活3600秒后，自动清除。再调用redis destroy方法将会返回false)：

```
	/**
     * {@inheritDoc}
     */
    public function destroy($sessionId)
    {
        $key = $this->getKey($sessionId);
        return 1 === $this->redis->delete($key);
    }

```