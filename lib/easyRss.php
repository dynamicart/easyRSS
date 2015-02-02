<?php
/*
 * $Id: easyRSS.php,v 0.2 2015/02/02 12:00:00 Janos Szentgyorgyi $
 *
 *  Licensed under the Apache License.
 *    http://www.apache.org/licenses/
 *
 */
class easyRss {

    /**
     * The complete RSS document
     * @var
     */
    public $document;

    /**
     * The channel data
     * @var
     */
    public $channel;

    /**
     * The RSS items
     * @var
     */
    public $items;

    /**
     * Load the RSS from url
     * @param string $url
     * @param bool $useRandomContext
     * @return type
     */
    public function load($url, $useRandomContext=true) {
        if($useRandomContext) {
            $this->loadParser(file_get_contents($url, false, $this->randomContext()));
        } else {
            $this->loadParser(file_get_contents($url));
        }
    }

    /**
     * Load the RSS from string
     * @param string $rawxml
     * @return type
     */
    public function loadRSS($rawxml) {
        $this->loadParser($rawxml);
    }

    /**
     * Parse the RSS
     * @param string $rss
     * @return type
     */
    private function loadParser($rss) {
        $this->document = simplexml_load_string($rss, null, LIBXML_NOCDATA);
        $result = $this->iterateObj($this->document->channel);
        $this->items = $result['item'];
        unset($result['item']);
        $this->channel = $result;
    }

    /**
     * Object iterator
     * @param $obj
     * @return mixed
     */
    private function iterateObj($obj){
        $res = (array)$obj;
        foreach($res as $key => $value){
            if ((is_array($value) || is_object($value)) && count($value) > 0){
                $sub_item = (array)$value;
                foreach($sub_item as $sub_key => $sub_value){
                    if (!is_array($sub_value) && !is_object($sub_value)){
                        if (is_string($sub_key) && $sub_key != ''){
                            $result[$key][$sub_key] = $sub_value;
                        }else{
                            $result[$key][] = $sub_value;
                        }
                    }else{
                        $result[$key][] = $this->iterateObj($sub_value);
                    }
                }
            }else{
                if (!isset($result[$key])){
                    $result[$key] = (string)$value;
                }else{
                    $result[$key][] = (string)$value;
                }
            }
        }
        return $result;
    }

    /**
     * Context randomizer
     * @return resource
     */
    private function randomContext() {
		$headerstrings = array();
		$headerstrings['User-Agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.'.rand(0,2).'; en-US; rv:1.'.rand(2,9).'.'.rand(0,4).'.'.rand(1,9).') Gecko/2007'.rand(10,12).rand(10,30).' Firefox/2.0.'.rand(0,1).'.'.rand(1,9);
		$headerstrings['Accept-Charset'] = rand(0,1) ? 'en-gb,en;q=0.'.rand(3,8) : 'en-us,en;q=0.'.rand(3,8);
		$headerstrings['Accept-Language'] = 'en-us,en;q=0.'.rand(4,6);
		$setHeaders = 	'Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5'."\r\n".
						'Accept-Charset: '.$headerstrings['Accept-Charset']."\r\n".
						'Accept-Language: '.$headerstrings['Accept-Language']."\r\n".
						'User-Agent: '.$headerstrings['User-Agent']."\r\n";
		$contextOptions = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>$setHeaders
			)
		);
		return stream_context_create($contextOptions);
	}
	
}

?>