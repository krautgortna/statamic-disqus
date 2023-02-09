<?php

namespace Krautgortna\Disqus\Tags;

use Statamic\Tags\Tags;

class DisqusTags extends Tags
{
    private string $shortname;
    protected static $handle = 'disqus';


    /**
     * The {{ disqus:comments id="uniqueid" }} tag
     *
     * @return string
     */
    public function comments() 
    {
        $id = $this->params->get('id');
        $url = $this->context->get('permalink');

        $this->shortname = env('DISQUS_SHORTNAME');

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
        if(empty(env('DISQUS_SECRET'))) {
            return null;
        }

        $url .= "&api_secret=" . env('DISQUS_SECRET');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    private function getThreadDetails($id){
        $this->shortname = env('DISQUS_SHORTNAME');

        $apiUrl = "https://disqus.com/api/3.0/threads/details.json?thread:ident=" . $id . "&forum=" . $this->shortname;
        $response = $this->callDisqusApi($apiUrl);
        return json_decode($response, true);
    }
    
    /**
     * The {{ disqus:count id="uniqueid" }} tag
     *
     * @return string
     */
    public function count() 
    {
        $id = $this->params->get('id');

        $json = $this->getThreadDetails($id);
        $code = $json["code"];        

        if($code == 0 && is_array($json["response"])) {
            return $json["response"]["posts"];
        }

        return 0;
    }

    /**
     * The {{ disqus:likes id="uniqueid" }} tag
     *
     * @return string
     */
    public function likes() 
    {
        $id = $this->params->get('id');

        $json = $this->getThreadDetails($id);
        $code = $json["code"];        

        if($code == 0 && is_array($json["response"])) {
            return $json["response"]["likes"];
        }

        return 0;
    }
    

}