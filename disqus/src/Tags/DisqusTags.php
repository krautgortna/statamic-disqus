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
     * @return string|array
     */
    public function comments() 
    {
        $id = $this->params->get('id');
        $url = $this->context->get('permalink');

        $this->shortname = env('DISQUS_SHORTNAME');



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
}