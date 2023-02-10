<?php

namespace Krautgortna\Disqus\Tags;

use Statamic\Tags\Tags;

abstract class Metric {
    const LIKES = 1;
    const POSTS = 2;
};

class DisqusTags extends Tags
{
    protected static $handle = 'disqus';

    private $options = [
        Metric::POSTS => ["prefix" => "disqus_like_count", "function" => "getCounts", "attr" => "posts"], 
        Metric::LIKES => ["prefix" => "disqus_comment_count", "function" => "getLikes", "attr" => "likes"]
    ];

    private $shortname, $api_key, $api_secret, $api_method;

    function __construct() {
        $this->shortname = env('DISQUS_SHORTNAME');
        $this->api_key = env('DISQUS_API_KEY');
        $this->api_secret = env('DISQUS_SECRET');
        $this->api_method = env('DISQUS_METHOD', 'client');
    }

    /**
     * The {{ disqus:comments id="uniqueid" }} tag
     *
     * @return string
     */
    public function comments() 
    {
        $id = $this->params->get('id');
        $url = $this->context->get('permalink');

        if(empty($this->shortname)) return '';

        $code = '
            <div id="disqus_thread"></div>
            <script>
            var disqus_config = function () {
                this.page.url = \'' . addslashes($url) . '\';
                this.page.identifier = \'' . addslashes($id) . '\';
            };
            
            (function() { // DON\'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement(\'script\');
            s.src = \'https://' . $this->shortname . '.disqus.com/embed.js\';
            s.setAttribute(\'data-timestamp\', +new Date());
            (d.head || d.body).appendChild(s);
            })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        ';

        return $code;
    }

    private function callDisqusApi($url){
        if(empty($this->api_secret)) return null;

        $url .= "&api_secret=" . $this->api_secret;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    private function getThreadDetails($id){
        $apiUrl = "https://disqus.com/api/3.0/threads/details.json?thread:ident=" . $id . "&forum=" . $this->shortname;
        $response = $this->callDisqusApi($apiUrl);
        if( ! $response) null;

        return json_decode($response, true);
    }

    private function getPHPMetric($id, $metric){
        $json = $this->getThreadDetails($id);
        if ( ! $json ) return 0;

        $code = $json["code"]; 
        $option = $this->options[$metric]["attr"];

        if($code == 0 && is_array($json["response"])) {
            return $json["response"][$option];
        }

        return 0;
    }

    private function insertJSGetMetric($id, $metric){
        if(empty($this->shortname) || empty($this->api_key)) return '';

        $prefix = $this->options[$metric]["prefix"];
        $jsFunction = $this->options[$metric]["function"];

        $span = "<span id=\"${prefix}_${id}\">0</span>";
        $script = '<script src="/vendor/statamic-disqus/js/disqus.js"></script>';
        $script .= "<script>
                        DISQUS_ADDON.init({'api_key': '$this->api_key'})
                        DISQUS_ADDON.${jsFunction}(document.getElementById('${prefix}_${id}'), '$id', '$this->shortname')
                    </script>";

        return $span . $script;
    }

    // inserts the JS API call if 'client' or inits cUrl PHP call if 'server'
    private function getMetric($id, $metric) {
        switch($this->api_method){
            case "client":  return $this->insertJSGetMetric($id, $metric);
                            break;
            case "server":  return $this->getPHPMetric($id, $metric);
                            break;

            default: return '';
        }
 
    }
    
    /**
     * The {{ disqus:count id="uniqueid" }} tag
     *
     * @return string
     */
    public function count() 
    {
        $id = $this->params->get('id');
        return $this->getMetric($id, Metric::POSTS);
    }

    /**
     * The {{ disqus:likes id="uniqueid" }} tag
     *
     * @return string
     */
    public function likes() 
    {
        $id = $this->params->get('id');
        return $this->getMetric($id, Metric::LIKES);
    }  

}