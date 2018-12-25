<?php
/*
 * php code obfuscation project
 *
 * author:00theway
 *
 * */
$f = file_get_contents('sourcecode/help.php');
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function RandomStr($n)
{
    $characters = '0oO';
    $randstring = 'o';
    for ($i = 0; $i < $n; $i++) {
        $randstring .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randstring;
}

$tokens = token_get_all($f);

$r = '';
$k = '中文编码亦或测试中文编码亦或测试';

$randomvar = array(
    '$_GET'=>'$_GET',
    '$_POST'=>'$_POST',
    '$_REQUEST'=>'$_REQUEST',
    '$_COOKIE'=>'$_COOKIE',
    '$GLOBALS'=>'$GLOBALS',
    '$_SERVER'=>'$_SERVER',
    '$_FILES'=>'$_FILES',
    '$_SESSION'=>'$_SESSION',
    '$_ENV'=>'$_ENV');


foreach ($tokens as $token) {
    if (is_array($token)) {
        $tokenline = $token[2];
        $tokenstr = $token[1];
        $tokenname = token_name($token[0]);
        switch ($tokenname){
            case 'T_STRING':
                if(is_callable($tokenstr) && !endsWith($r,'->')){
                    $t = $k ^ $tokenstr;
                    $tokenstr = "($t^\$GLOBALS['o'])";

                }
                break;
            case 'T_OPEN_TAG':
                $tokenstr = $tokenstr . "\$GLOBALS['o']='$k';".PHP_EOL;
                break;
            case 'T_VARIABLE':
                if(!array_key_exists($tokenstr,$randomvar)){
                    $randomvar[$tokenstr] = '$'.RandomStr(10);
                }
                $tokenstr = $randomvar[$tokenstr];
                break;
        }

        $r .= $tokenstr;

    }else{
        $r .= $token;
    }
}

file_put_contents('out/dd.php',$r);


$t = 'str_replace' ^ $k;

eval("echo ($t^\$k)('123','456','123www123');");

?>