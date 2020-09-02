<?php

  class Lingua_Stem_Ru
  {
      var $VERSION = "0.02";
      var $VOWEL = '/аеиоуыэюя/';
      var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';


     var $REFLEXIVE = '/(с[яь])$/';
      var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/';
      var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
      var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])
  (ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
      var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
      var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
      var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';


      function s(&$s, $re, $to)
      {
          $orig = $s;
          $s = preg_replace($re, $to, $s);
          return $orig !== $s;
      }

      function m($s, $re)
      {
          return preg_match($re,$s);
      }

      function stem_word($word)
      {
          $word = mb_strtolower($word, mb_detect_encoding($word));
          //$word = mb_strtolower($word, '');
          $word = str_replace('ё', 'е', $word);
          $stem = $word;
          do {
            if (!preg_match($this->RVRE, $word, $p)) break;
            $start = $p[1];
            $RV = $p[2];
            if (!$RV) break;

            # Step 1
            if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
                $this->s($RV, $this->REFLEXIVE, '');
                if ($this->s($RV, $this->ADJECTIVE, '')) {
                    $this->s($RV, $this->PARTICIPLE, '');
                } else {
                    if (!$this->s($RV, $this->VERB, ''))
                        $this->s($RV, $this->NOUN, '');
                }
            }

            # Step 2
            $this->s($RV, '/и$/', '');

            # Step 3
            if ($this->m($RV, $this->DERIVATIONAL))
                $this->s($RV, '/ость?$/', '');

            # Step 4
            if (!$this->s($RV, '/ь$/', '')) {
                $this->s($RV, '/ейше?/', '');
                $this->s($RV, '/нн$/', 'н');
            }

            $stem = $start.$RV;
          } while(false);
          return $stem;
      }

  }



/**
* функция получения идентификатора слова
* @author Zheltiy
* @copyright
* @version 1.0
* @date 14.01.2013
*/  
function get_id_slovoform($str)
{
	$query = "SELECT * FROM search_slovoform_test
					   WHERE word = '$str'
		               LIMIT 1";
	$result = mysqlQuery($query);
		
	if ($res = mysql_fetch_assoc($result)) 
	{
		return $res['id_slovoform'];
	}
	else 
	{
		return 0;
	}
}
  
/**
* функция разбиения предложения на слова
* @author Zheltiy
* @copyright
* @version 1.0
* @date 14.01.2013
*/ 
function get_words_list($str)
{
    // удаляем двоичные символы 
    if (strpos($str, chr(160))) { $str = str_replace(chr(160), " ", $str); }
    if (strpos($str, "-")) { $str = str_replace("-", " ", $str); }
    if (strpos($str, ",")) { $str = str_replace(",", " ", $str); }
    if (strpos($str, ".")) { $str = str_replace(".", " ", $str); }
    if (strpos($str, "+")) { $str = str_replace("+", " ", $str); }
    if (strpos($str, ";")) { $str = str_replace(";", " ", $str); }
    if (strpos($str, ":")) { $str = str_replace(":", " ", $str); }
	if (strpos($str, "/")) { $str = str_replace("/", " ", $str); }

    while (strpos($str, "  ")) { $str = str_replace("  ", " ", $str); }

    // Разбиваем предложение на слова
    return explode(" ", $str);
}











  
  
  if (!empty($_POST['OK']))

  {
		$word = $_POST['words'];
		dbg(get_words_list($word));  }

?>