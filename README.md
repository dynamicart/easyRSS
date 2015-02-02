# easyRSS - php parser v0.2
----------
This is a minimal tool for parse RSS feed in PHP. 

**Usage**

    include_once "lib/easyRss.php";

    $RSS = new easyRss();
    $RSS->load("https://news.google.com/?output=rss", true);
    var_dump($RSS->channel, $RSS->items);

 - CDATA content fixed
 - UTF-8 chars fixed
 - Random browser context available on load url

