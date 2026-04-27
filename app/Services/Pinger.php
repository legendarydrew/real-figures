<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Pinger.
 * Derived from the Garf\LaravelPinger package.
 */
class Pinger
{
    use Macroable;

    /**
     * Ping Yandex about a specific page.
     */
    public function pingYandex(string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing('https://ping.blogs.yandex.ru', $xml);
    }

    /**
     * Returns an XML document representing information about the page to ping.
     *
     * @param  string|null  $rss  [optional] Additional RSS information.
     */
    protected function getXml(string $title, string $url, ?string $rss = null): string
    {
        $data = [
            'title' => $title,
            'url' => $url,
        ];
        if ($rss !== null && $rss !== '') {
            $data['rss'] = $rss;
        }

        // The Blade template seems to have an issue (on production) with the xml tag.
        return "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n".view('xml.pinger')->with($data)->render();
    }

    /**
     * Sends a ping request to the specified URL.
     *
     * @return bool|string FALSE if unsuccessful, or the response from the service if successful.
     */
    protected function sendPing(string $url, string $xml): bool|string
    {
        if (App::hasDebugModeEnabled() || App::runningUnitTests()) {
            logger()->info(sprintf('Ping: %s', $url));

            return true;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Ping Google about a specific page.
     */
    public function pingGoogle(string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing('https://blogsearch.google.com/ping/RPC2', $xml);
    }

    /**
     * Ping Yahoo about a specific page.
     */
    public function pingYahoo(string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing('https://api.my.yahoo.com/RPC2', $xml);
    }

    /**
     * Ping Feedburner about a specific page.
     */
    public function pingFeedburner(string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing('https://ping.feedburner.com', $xml);
    }

    /**
     * Ping weblogs.se about a specific page.
     */
    public function pingWeblogs(string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing('https://ping.weblogs.se/', $xml);
    }

    /**
     * Ping PingOMatic about a specific page.
     */
    public function pingPingOMatic(string $page_title, string $page_url, ?string $rss = null, array $params = []): bool|string
    {
        $entity = [
            'chk_weblogscom' => 'on',
            'chk_blogs' => 'on',
            'chk_feedburner' => 'on',
            'chk_newsgator' => 'on',
            'chk_myyahoo' => 'on',
            'chk_pubsubcom' => 'on',
            'chk_blogdigger' => 'on',
            'chk_weblogalot' => 'on',
            'chk_newsisfree' => 'on',
            'chk_topicexchange' => 'on',
            'chk_google' => 'on',
            'chk_tailrank' => 'on',
            'chk_skygrid' => 'on',
            'chk_collecta' => 'on',
            'chk_superfeedr' => 'on',
        ];

        $entity['title'] = urlencode($page_title);
        $entity['blogurl'] = urlencode($page_url);
        $entity['rssurl'] = urlencode($rss);

        $query_string = http_build_query(array_merge($entity, $params));
        $service_url = 'https://pingomatic.com/ping/?'.$query_string;

        return $this->sendPing($service_url, $query_string);
    }

    /**
     * Ping a specific service URL about a specific page.
     */
    public function ping(string $service_url, string $page_title, string $page_url, ?string $rss = null): bool|string
    {
        $xml = $this->getXml($page_title, $page_url, $rss);

        return $this->sendPing($service_url, $xml);
    }

    /**
     * Ping configured URLs about a specific page.
     */
    public function configured(string $page_title, string $page_url, ?string $rss = null): bool
    {
        $xml = $this->getXml($page_title, $page_url, $rss);
        $services = config('post.ping');
        foreach ($services as $service) {
            $this->sendPing($service, $xml);
        }

        return true;
    }
}
