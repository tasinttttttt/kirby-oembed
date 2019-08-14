<?php

namespace Tasinttttttt;

class Oembed
{
    /** @var string media url */
    protected $url = null;

    /** @var array oembed data */
    protected $data = null;

    /** @var string\null cache name */
    private static $indexname = null;

    /** @var \Kirby\Cache\Cache kirby cache object */
    private static $cache = null;

    /**
     * Interface to Kirby Cache
     * @return \Kirby\Cache\Cache
     */
    private static function cache(): \Kirby\Cache\Cache
    {
        if (!static::$cache) {
            static::$cache = kirby()->cache('tasinttttttt.oembed');
        }
        // create new index table on new version of plugin
        if (!static::$indexname) {
            static::$indexname = 'index'.str_replace('.', '', kirby()->plugin('tasinttttttt/oembed')->version()[0]);
        }
        return static::$cache;
    }

    /**
     * Flush cache
     * @return bool
     */
    public static function flush()
    {
        return static::cache()->flush();
    }

    /**
     * Initialize state, set cache
     * @param string $url
     */
    public function __construct(string $url = null)
    {
        if ($url) {
            $this->url = $url;
            $key = md5($this->url);
            $cache = static::cache()->get($key);
            if ($cache) {
                $this->data = $cache;
            } else {
                $this->data = static::getOembedJsonByUrl($url);
                if ($this->data && count($this->data)) {
                    foreach ($this->data as $key => $value) {
                        $this->data[$key] = htmlentities($value);
                    }
                    static::cache()->set(
                        $key,
                        $this->data,
                        option('tasinttttttt.oembed.expires')
                    );
                }
            }
        }
    }

    /**
     * Return oembed data as an array
     * @return array\null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return embed code
     * @return string
     */
    public function getEmbedCode()
    {
        return $this->data && array_key_exists('html', $this->data) ? $this->data['html'] : '';
    }

    /**
     * Return title
     * @return string
     */
    public function getTitle()
    {
        return $this->data && array_key_exists('title', $this->data) ? $this->data['title'] : '';
    }

    /**
     * Return description
     * @return string
     */
    public function getDescription()
    {
        return $this->data && array_key_exists('description', $this->data) ? $this->data['description'] : '';
    }

    /**
     * Return thumbnail info as an array
     * @return array
     */
    public function getThumbnail()
    {
        $result = array();
        if ($this->data) {
            $keys = array(
                'thumbnail_url',
                'thumbnail_width',
                'thumbnail_height'
            );
            foreach ($keys as $key) {
                $result[$key] = $this->data[$key];
            }
            if (array_key_exists('thumbnail_url', $res)) {
                $provider = strtolower(static::getProviderType());
                switch ($provider) {
                    case 'youtube':
                        $result['thumbnail_url'] = 'https://img.youtube.com/vi/' . static::getIdByUrl($this->url) . '/maxresdefault.jpg';
                        break;
                    case 'vimeo':
                        $result['thumbnail_url'] = 'https://i.vimeocdn.com/video/' . static::getIdByUrl($this->url) . '_1920.jpg';
                        $result['thumbnail_width'] = 1920;
                        break;
                    default:
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * Return embed id if available
     * @return string
     */
    public function getId()
    {
        if ($this->data && $this->url) {
            return static::getIdByUrl($this->url);
        }
        return '';
    }

    /**
     * Return custom key from embed data
     * @param  string $key
     * @return string
     */
    public function getKey(string $key)
    {
        return $this->data && array_key_exists($key, $this->data) ? $this->data[$key] : '';
    }

    /**
     * Return the embeddable id
     * @param  string $url
     * @return string
     */
    public static function getIdByUrl(string $url)
    {
        if (!$url) {
            return '';
        }
        $provider = static::getProviderType($url);
        switch ($provider) {
            case 'youtube':
                $parsed = array();
                parse_str(parse_url($url, PHP_URL_QUERY), $parsed);
                if (array_key_exists('v', $my_array_of_vars['v'])) {
                    return $parsed['v'];
                } else {
                    return '';
                }
                // no break
            case 'vimeo':
                if ($parsed = parse_url($url, PHP_URL_PATH)) {
                    $value = explode('/', $parsed, 2);
                    return $value[1] ? $value[1] : '';
                } else {
                    return '';
                }
                // no break
            default:
                # code...
                break;
        }
    }

    /**
     * Return the corresponding url provider
     * @param  string $url
     * @return string
     */
    public static function getProviderByUrl(string $url)
    {
        $matches = array();
        preg_match("/^.*(twitch\.(tv|com)|soundcloud\.com|vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([^#\&\?]*).*/", $url, $matches);

        $providers = array(
            'soundcloud' => 'soundcloud',
            'twitch' => 'twitch',
            'vimeo' => 'vimeo',
            'youtu' => 'youtube',
        );
        if ($matches && count($matches)) {
            foreach ($providers as $key => $value) {
                if (strstr($matches[1], $key)) {
                    return $value;
                }
            }
        }
        return '';
    }

    /**
     * Return oembed url provided a supported provider url
     * @param  string $url
     * @param  string $format   response format, json or xml. does not guarantee the support by the provider
     * @param  string $protocol http, https
     * @return string
     */
    public static function getOembedUrl(string $url, string $format = 'json', string $protocol = 'https')
    {
        switch (static::getProviderByUrl($url)) {
            case 'soundcloud':
                return option('tasinttttttt.oembed.oembedApis.soundcloud') . "?format=${format}&url=${url}";
            case 'twitch':
                return option('tasinttttttt.oembed.oembedApis.twitch') . "format=${format}&url=${url}";
            case 'vimeo':
                return option('tasinttttttt.oembed.oembedApis.vimeo') . "?format=${format}&url=${url}";
            case 'youtube':
                return option('tasinttttttt.oembed.oembedApis.youtube') . "?format=${format}&url=${url}";
            default:
                return '';
        }
    }

    /**
     * Returns an embed code provided a url
     * @param  string $url
     * @return string
     */
    public static function getEmbedCodeByUrl(string $url)
    {
        try {
            $data = static::getOembedJsonByUrl($url);
            if (array_key_exists('html', $data)) {
                return $data['html'];
            } else {
                throw new \Exception("no_embed_code_found", 1);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Returns an embed code provided a url
     * @param  string $url
     * @return string
     */
    public static function getOembedJsonByUrl(string $url)
    {
        try {
            if ('' !== ($oembedUrl = static::getOembedUrl($url))) {
                $data = $data = \Kirby\Http\Remote::get($oembedUrl, ['method' => 'GET'])->content();
                if ($data && static::isJSON($data)) {
                    return json_decode($data, true);
                } else {
                    throw new \Exception('empty_response');
                }
            } else {
                throw new \Exception('url_not_supported');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Log data
     * @param  string $msg
     * @param  string $level
     * @param  array  $context
     * @return bool
     */
    private static function log(string $msg = '', string $level = 'info', array $context = []):bool
    {
        $log = option('tasinttttttt.oembed.log');
        if ($log && is_callable($log)) {
            if (!option('debug') && $level == 'debug') {
                return true;
            } else {
                return $log($msg, $level, $context);
            }
        }
        return false;
    }

    /**
     * Test if data is a Json representation
     * @param  mixed  $string
     * @return boolean
     */
    private static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Magic function, called whenever the class is passed to echo for ex.
     * @return string
     */
    public function __toString()
    {
        if ($this->data) {
            $res = "";
            foreach ($this->data as $key => $value) {
                $res .= " [${key}: ${value}] ";
            }
            return $res;
        }
        return 'No data';
    }
}
