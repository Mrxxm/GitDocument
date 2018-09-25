## PHP爬取百度图片

```php
<?php

set_time_limit(0);

class Client
{
    public function main() {
        $keyword = 'spider'; // 爬取关键字
        $num = 60; // 爬取数量
        $path = "/Users/xuxiaomeng/Pictures/spiderPic/"; // 图片保存路径
        $referer = "https://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&ct=201326592&is=&fp=result&queryWord+=&cl=&lm=&ie=utf-8&oe=utf-8&adpicid=&st=&word=%E6%96%B0%E5%9E%A3%E7%BB%93%E8%A1%A3&z=&ic=&s=&se=&tab=&width=&height=&face=&istype=&qc=&nc=&fr=&step_word=%E6%96%B0%E5%9E%A3%E7%BB%93%E8%A1%A3&pn=120&rn=30&gsm=78&1506604653115=";
        $images = $this->spider($keyword, $num);
        $this->batchDownload($images, $path, $referer);
    }

    /**
     * [spider 爬取百度图片]
     * @param  [string] $keyword [搜索关键字]
     * @param  [int] $num        [图片数量]
     * @return [array]           [图片对应的地址]
     */
    public function spider($keyword, $num) {
        $keyword = urlencode($keyword);
        $res = array();
        for ($pn = 0; $pn <= $num; $pn += 30) {
            $url = "http://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&ct=201326592&fp=result&queryWord+=&ie=utf-8&oe=utf-8&word=".$keyword."=&pn=".$pn."&rn=30";
            $json = file_get_contents($url);
            $array = json_decode($json);
            foreach ($array->data as $key => $image) {
                if (!in_array($image, $res)) {
                    if (isset($image->middleURL)) {
                        $res[] = $image->middleURL;
                    }elseif (isset($image->thumbURL)){
                        $res[] = $image->thumbURL;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * [download 下载图片到本地]
     * @param  [string] $url     [图片地址]
     * @param  [string] $path    [存储路径]
     * @param  string $referer [HTTP Header中的referer值]
     */
    private function download($url, $path, $referer = '') {
        $filename = pathinfo($url, PATHINFO_BASENAME);
        if (!file_exists($path.$filename)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, $referer);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $file = curl_exec($ch);
            curl_close($ch);

            $resource = fopen($path.$filename, 'a');
            fwrite($resource, $file);
            fclose($resource);
        }
    }

    /**
     * [batchDownload 批量下载图片]
     * @param  [type] $imgUrls [图片地址数组]
     * @param  [type] $path    [目标路径]
     * @param  string $referer [HTTP Header中的referer值]
     */
    public function batchDownload($imgUrls, $path, $referer = '') {
        foreach ($imgUrls as $key => $imgUrl) {
            $this->download($imgUrl, $path, $referer);
        }
    }
}
$client = new Client();
$client->main();

```

* 相关链接 `http://twei.site/2017/09/28/PHP%E7%88%AC%E5%8F%96%E7%99%BE%E5%BA%A6%E5%9B%BE%E7%89%87/`