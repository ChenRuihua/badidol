<?php

namespace App\Console\Commands;

use App\Models\Topic;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HotspotRobots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badidol:hotspot-robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '热点机器人';

    protected $decoration = [
        '是为什么呢?',
        '到底是因为什么?',
        '的真实原因是什么?',
        '是因为什么原因呢?',
        '我们应该了解些什么?',
        '其中有什么需要注意的?',
    ];

    protected $contentPrefix = [
        '这消息有些朋友不知道,',
        '此新闻许多网友不了解,',
        '这资讯你看过吗,',
        '此信息很多人没收到,',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Client $client)
    {
        if (!$hotspotRobots = Cache::get('hotspot_robots')) {
            $hotspotRobots = DB::table('users')
                ->where('email', 'like', '%@badidol.com')
                ->pluck('id')
                ->toArray();
            if (!$hotspotRobots) return;

            Cache::put('hotspot_robots', $hotspotRobots, 10);
        }
        $randEnd = count($hotspotRobots) - 1;

        $siteName = setting('site_name', 'BadIdol.com 坏偶像') . ' | ';

        $crawler = $client->request('GET', 'http://top.baidu.com/buzz?b=1&fr=20811');
        $content = $crawler->filter('.list-table > .item-tr');
        $ids = [];
        foreach ($content as $k => $v) {
            $tmp = explode("\r\n", trim($v->textContent));
            $title = trim($tmp[0]);
            $body = trim($tmp[1]);
            $href = $siteName . $title;

            $topic = new Topic();
            $topic->user_id = $hotspotRobots[rand(0, $randEnd)];
            $topic->category_id = 3;
            $topic->title = $title . $this->randomDecoration();
            $topic->body = $this->randomContentPrefix()
                . $body
                . "<a href='https://www.baidu.com/s?ie=UTF-8&wd=$href'>查看全部</a>";
            $topic->save();

            $ids[] = $topic->id;
        }

        $this->info('爬取成功');

        $this->pushBaidu($ids);
    }

    protected function pushBaidu($ids)
    {
        $baseUrl = env('APP_URL') . '/topics/';

        foreach ($ids as $k => $v) {
            $ids[$k] = $baseUrl . $v;
        }

        $api = 'http://data.zz.baidu.com/urls?site=badidol.com&token=j2uKCZxtd8ieGZTc';
        $ch = curl_init();
        $options = [
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $ids),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        ];
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        $this->info("推送成功\r\n" . $result);
    }

    protected function randomDecoration()
    {
        $decoration = $this->mixCommonPrefix($this->decoration);

        return $decoration[rand(0, count($decoration) - 1)];
    }

    protected function randomContentPrefix()
    {
        $contentPrefix = $this->mixCommonPrefix($this->contentPrefix);

        return $contentPrefix[rand(0, count($contentPrefix) - 1)]
            . '来[BadIdol坏偶像]看看:<br><br>';
    }

    protected function mixCommonPrefix($waitMix)
    {
        $commonPrefix = [
            '怎么回事?什么情况?',
            '网友发表了自己的看法.',
            '当时发生了什么?现在情况如何?',
            '咱们要如何看待这条资讯?',
            '我们应如何评论此新闻?',
            '网友如何评价这一事件?',
        ];

        return array_merge($waitMix, $commonPrefix);
    }
}
