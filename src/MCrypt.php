<?php 
namespace MCrypt\Crypt;

  class  MCrypt { 
      public $key="DASFDSA_ADSF";
      
      public static function encrypt($string,$key=$this->key){
          return self::_encrypt($string, "E",$key);
      }
      
      public static function decrypt($string,$key=$this->key){
          return self::_encrypt($string, "D",$key);
      }
      
      private static function _encrypt($string,$operation,$key=''){
          $key=md5($key);
          $key_length=strlen($key);
          $string=$operation=='D'?self::deBase64($string):substr(md5($string.$key),0,8).$string;
         
          $string_length=strlen($string);
          $rndkey=$box=array();
          $result='';
          for($i=0;$i<=127;$i++){
              $rndkey[$i]=ord($key[$i%$key_length]);
              $box[$i]=$i;
          } 
          for($j=$i=0;$i<128;$i++){
              $j=($j+$box[$i]+$rndkey[$i])%128; 
              $tmp=$box[$i];
              $box[$i]=$box[$j];
              $box[$j]=$tmp;
          }
          for($a=$j=$i=0;$i<$string_length;$i++){
              $a=($a+1)%128;
              $j=($j+$box[$a])%128;
              
              $tmp=$box[$a];
              $box[$a]=$box[$j];
              $box[$j]=$tmp; 
              $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%128]));  
          }
          if($operation=='D'){
              if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
                  return substr($result,8);
              }else{
                  return'';
              }
          }else{ 
              return str_replace('=','',self::enBase64($result));
          }
      }

      /**
       * base64编码替换特殊字符串
       * @param $str
       * @return mixed
       */
      private static function enBase64($str)
      {
          $str = base64_encode($str);
          $str = strtr($str,('/+'),('_-'));
          return $str;
      }

      /**
       * base64编码还原特殊字符串
       * @param $str
       * @return mixed
       */
      private static function deBase64($str)
      {
          $str = strtr($str,('_-'),('/+'));
          $str = base64_decode($str);
          return $str;
      }
    
	 
}
?>