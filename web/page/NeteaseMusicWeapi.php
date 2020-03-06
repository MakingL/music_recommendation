<?php

require_once('BigInteger.php');

class NeteaseMusicWEAPI{

    // General
    protected $_MINI_MODE=false;
    protected $_MODULUS='00e0b509f6259df8642dbc35662901477df22677ec152b5ff68ace615bb7b725152b3ab17a876aea8a5aa76d2e417629ec4ee341f56135fccf695280104e0312ecbda92557c93870114af6c9d05c4f7f0c3685b7a46bee255932575cce10b424d813cfe4875d3e82047b97ddef52741d546b8e289dc6935b3ece0462db0a22b8e7';
    protected $_NONCE='0CoJUm6Qyw8W8jud';
    protected $_PUBKEY='010001';
    protected $_VI='0102030405060708';
    protected $_USERAGENT='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36';
    protected $_COOKIE='os=pc; osver=Microsoft-Windows-10-Professional-build-10586-64bit; appver=2.0.3.131777; channel=netease; __remember_me=true';
    protected $_REFERER='http://music.163.com/';
    // use keygen secretKey
    protected $_secretKey='';
    protected $_encSecKey='';

    public function __construct(){
        $this->_secretKey=$this->createSecretKey(16);
    }

    // encrypt mod
    protected function createSecretKey($length){
      $str='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $r='';
        for($i=0;$i<$length;$i++){
            $r.=$str[rand(0,strlen($str)-1)];
        }
        return $r;
    }

    // 数据加密
    protected function prepare($raw){
        $data['params']=$this->aes_encode(json_encode($raw),$this->_NONCE);
        $data['params']=$this->aes_encode($data['params'],$this->_secretKey);
        $data['encSecKey']=$this->rsa_encode($this->_secretKey);
        return $data;
    }
    protected function aes_encode($secretData,$secret){
        return openssl_encrypt($secretData,'aes-128-cbc',$secret,false,$this->_VI);
    }
    protected function rsa_encode($text){
      $rtext=strrev(utf8_encode($text));
      $keytext=$this->bchexdec($this->strToHex($rtext));
        $a=new Math_BigInteger($keytext);
        $b=new Math_BigInteger($this->bchexdec($this->_PUBKEY));
        $c=new Math_BigInteger($this->bchexdec($this->_MODULUS));
        $key=$a->modPow($b,$c)->toHex();
        return str_pad($key,256,'0',STR_PAD_LEFT);
    }
    protected function bchexdec($hex){
        $dec=0;
        $len=strlen($hex);
        for($i=0;$i<$len;$i++) {
            $dec=bcadd($dec,bcmul(strval(hexdec($hex[$i])),bcpow('16',strval($len-$i-1))));
        }
        return $dec;
    }
    protected function strToHex($str){
        $hex='';
        for($i=0;$i<strlen($str);$i++){
            $hex.=dechex(ord($str[$i]));
        }
        return $hex;
    }

    // CURL
    protected function curl($url,$data=null){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        if($data){
            if(is_array($data))$data=http_build_query($data);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
            curl_setopt($curl,CURLOPT_POST,1);
        }
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl,CURLOPT_REFERER,$this->_REFERER);
        curl_setopt($curl,CURLOPT_COOKIE,$this->_COOKIE);
        curl_setopt($curl,CURLOPT_USERAGENT,$this->_USERAGENT);
        // $server_url='http://makepace.top:6010/get/';
        // $proxy_ip = file_get_contents($server_url);
        // curl_setopt ($curl, CURLOPT_PROXY, $proxy_ip);
        // curl_setopt ($curl, CURLOPT_PROXY, '60.218.135.189:80');
        $result=curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    // main function
    // 搜索
    public function search($s,$limit=30,$offset=0,$type=1){
        $url='http://music.163.com/weapi/cloudsearch/get/web?csrf_token=';
        $data=array(
            's'=>$s,
            'type'=>$type,
            'limit'=>$limit,
            'total'=>'true',
            'offset'=>$offset,
            'csrf_token'=>'',
        );
        $raw=$this->curl($url,$this->prepare($data));
        if($this->_MINI_MODE){
            $this->_MINI_MODE=false;
            $raw=json_decode($raw,1);
            return json_encode($this->clear_data($raw["result"]["songs"]));
        }
        else return $raw;
    }

    // 获取相似歌曲
    public function similar_song($id, $offset = 0, $limit = 50)
    {
        $url="http://music.163.com/weapi/discovery/simiSong?csrf_token=";
         $data=array(
            'songid' => $id,
            'offset' => $offset,
            'limit' => $limit,
            'csrf_token' => '',
        );
        $raw=$this->curl($url,$this->prepare($data));

        return $raw;
    }

    public function artist($artist_id){
        $url='http://music.163.com/weapi/v1/artist/'.$artist_id.'?csrf_token=';
        $data=array(
            'csrf_token'=>'',
        );
        $raw=$this->curl($url,$this->prepare($data));
        if($this->_MINI_MODE){
            $this->_MINI_MODE=false;
            $raw=json_decode($raw,1);
            return json_encode($this->clear_data($raw["hotSongs"]));
        }
        else return $raw;
    }

    public function album($album_id){
        $url='http://music.163.com/weapi/v1/album/'.$album_id.'?csrf_token=';
        $data=array(
            'csrf_token'=>'',
        );
        $raw=$this->curl($url,$this->prepare($data));
        if($this->_MINI_MODE){
            $this->_MINI_MODE=false;
            $raw=json_decode($raw,1);
            return json_encode($this->clear_data($raw["songs"]));
        }
        else return $raw;
    }

    // 歌曲详情
    public function detail($song_id){
        $url='http://music.163.com/weapi/v2/song/detail?csrf_token=';
        $data=array(
            'c'=>'['.json_encode(array('id'=>$song_id)).']',
            'csrf_token'=>'',
        );
        $raw=$this->curl($url,$this->prepare($data));
        if($this->_MINI_MODE){
            $this->_MINI_MODE=false;
            $raw=json_decode($raw,1);
            return json_encode($this->clear_data($raw["songs"]));
        }
        else return $raw;
    }

    // 获取歌曲 url
    public function url($song_id,$br=999000){
        $url='http://music.163.com/weapi/song/enhance/player/url?csrf_token=';
        if(!is_array($song_id))$song_id=array($song_id);
        $data=array(
            'ids'=>$song_id,
            'br'=>$br,
            'csrf_token'=>'',
        );
        return $this->curl($url,$this->prepare($data));
    }

    // 歌单详情
    public function playlist($playlist_id){
        $url='http://music.163.com/weapi/v3/playlist/detail?csrf_token=';
        $data=array(
            'id'=>$playlist_id,
            'n'=>1000,
            'csrf_token'=>'',
        );
        $raw=$this->curl($url,$this->prepare($data));
        if($this->_MINI_MODE){
            $this->_MINI_MODE=false;
            $raw=json_decode($raw,1);
            return json_encode($this->clear_data($raw["playlist"]["tracks"]));
        }
        else return $raw;
    }

    // 获取歌词
    public function lyric($song_id){
        $url='http://music.163.com/weapi/song/lyric?csrf_token=';
        $data=array(
            'id'=>$song_id,
            'os'=>'pc',
            'lv'=>-1,
            'kv'=>-1,
            'tv'=>-1,
            'csrf_token'=>'',
        );
        return $this->curl($url,$this->prepare($data));
    }

    // 获取MV
    public function mv($mv_id){
        $url='http://music.163.com/weapi/mv/detail?csrf_token=';
        $data=array(
            'id'=>$mv_id,
            'csrf_token'=>'',
        );
        return $this->curl($url,$this->prepare($data));
    }

    /**
     * 排行榜
     * 说明:调用此接口,传入数字 idx, 可获取不同排行榜
     *
     * 必选参数:
     * idx: 对象 key, 对应以下排行榜
     *
     * "0": 云音乐新歌榜,
     * "1": 云音乐热歌榜,
     * "2": 网易原创歌曲榜,
     * "3": 云音乐飙升榜,
     * "4": 云音乐电音榜,
     * "5": UK排行榜周榜,
     * "6": 美国Billboard周榜
     * "7": KTV嗨榜,
     * "8": iTunes榜,
     * "9": Hit FM Top榜,
     * "10": 日本Oricon周榜
     * "11": 韩国Melon排行榜周榜,
     * "12": 韩国Mnet排行榜周榜,
     * "13": 韩国Melon原声周榜,
     * "14": 中国TOP排行榜(港台榜),
     * "15": 中国TOP排行榜(内地榜)
     * "16": 香港电台中文歌曲龙虎榜,
     * "17": 华语金曲榜,
     * "18": 中国嘻哈榜,
     * "19": 法国 NRJ EuroHot 30周榜,
     * "20": 台湾Hito排行榜,
     * "21": Beatport全球电子舞曲榜
     * 接口地址:
     * /top/list
     *
     * 调用例子:
     * /top/list?idx=6
     *
     * @route GET /top/list
     * @param int $idx
     * @return string json
     */
    public function topList($idx=1)
    {
        $top_list_all = array(
            array('云音乐新歌榜', '3779629'),
            array('云音乐热歌榜', '3778678'),
            array('网易原创歌曲榜', '2884035'),
            array('云音乐飙升榜', '19723756'),
            array('云音乐电音榜', '10520166'),
            array('UK排行榜周榜', '180106'),
            array('美国Billboard周榜', '60198'),
            array('KTV嗨榜', '21845217'),
            array('iTunes榜', '11641012'),
            array('Hit FM Top榜', '120001'),
            array('日本Oricon周榜', '60131'),
            array('韩国Melon排行榜周榜', '3733003'),
            array('韩国Mnet排行榜周榜', '60255'),
            array('韩国Melon原声周榜', '46772709'),
            array('中国TOP排行榜(港台榜)', '112504'),
            array('中国TOP排行榜(内地榜)', '64016'),
            array('香港电台中文歌曲龙虎榜', '10169002'),
            array('华语金曲榜', '4395559'),
            array('中国嘻哈榜', '1899724'),
            array('法国 NRJ EuroHot 30周榜', '27135204'),
            array('台湾Hito排行榜', '112463'),
            array('Beatport全球电子舞曲榜', '3812895'),
        );
        $url= 'http://music.163.com/weapi/playlist/detail?csrf_token=';
        $id = $top_list_all[$idx][1];
        $data=array(
            'id'=>$id,
            'csrf_token'=>'',
        );
        return $this->curl($url,$this->prepare($data));
        // return $url;
    }


    protected function clear_data($result){
        // you can modify it by yourself, change to your API?!
        foreach($result as $key=>$vo){
            $data[$key]=array(
                'id'=>$key,
                'songid'=>$vo["id"],
                'name'=>$vo["name"],
                'cover'=>'https://p4.music.126.net/'.self::Id2Url($vo['al']["pic_str"]?:$vo['al']["pic"]).'/'.($vo['al']["pic_str"]?:$vo['al']["pic"]).'.jpg',
                'url'=>'http://music.163.com/song/media/outer/url?id='.$vo["id"],
                //'lyric'=>$vo["id"],
                'artist'=>array(),
            );
            foreach($vo['ar'] as $vvo)$data[$key]['artist'][]=$vvo['name'];
            $data[$key]['artist']=implode('/',$data[$key]['artist']);
        }
        return $data;
    }

    public function mini(){
        $this->_MINI_MODE=true;
        return $this;
    }

    /* static url encrypt, use for pic*/
    public function Id2Url($id){
        $byte1[]=$this->Str2Arr('3go8&$8*3*3h0k(2)2');
        $byte2[]=$this->Str2Arr($id);
        $magic=$byte1[0];
        $song_id=$byte2[0];
        for($i=0;$i<count($song_id);$i++)$song_id[$i]=$song_id[$i]^$magic[$i%count($magic)];
        $result=base64_encode(md5($this->Arr2Str($song_id),1));
        $result=str_replace('/','_',$result);
        $result=str_replace('+','-',$result);
        return $result;
    }
    protected function Str2Arr($string){
        $bytes=array();
        for($i=0;$i<strlen($string);$i++)$bytes[]=ord($string[$i]);
        return $bytes;
    }
    protected function Arr2Str($bytes){
        $str='';
        for($i=0;$i<count($bytes);$i++)$str.=chr($bytes[$i]);
        return $str;
    }
}


 ?>
