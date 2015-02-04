<?php
///secret code
$secretPass = 'lol#hxc*)&$';

/// what to encode
$encodeThis = "example";

function Encode($data,$pwd)
{
    $pwd_length = strlen($pwd);
    for ($i = 0; $i < 255; $i++) {
        $key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
        $counter[$i] = $i;
    }
    for ($i = 0; $i < 255; $i++) {
        $x = ($x + $counter[$i] + $key[$i]) % 256;
        $temp_swap = $counter[$i];
        $counter[$i] = $counter[$x];
        $counter[$x] = $temp_swap;
    }
    for ($i = 0; $i < strlen($data); $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $counter[$a]) % 256;
        $temp = $counter[$a];
        $counter[$a] = $counter[$j];
        $counter[$j] = $temp;
        $k = $counter[(($counter[$a] + $counter[$j]) % 256)];
        $Zcipher = ord(substr($data, $i, 1)) ^ $k;
        $Zcrypt .= chr($Zcipher);
    }
    return $Zcrypt;
}

function hex2bin($hexdata) {
    for ($i=0;$i<strlen($hexdata);$i+=2) {
        $bindata.=chr(hexdec(substr($hexdata,$i,2)));
    }  
    return $bindata;
} 


///to encode
///$encoded = bin2hex(Encode($encodeThis,$secretPass));


/// to decode
///$decoded = Encode(hex2bin($encoded),$secretPass); 


//Encoding URLs

function hxcenc($string){

    //include_once("/home/hxcmusi/public_html/crypt.php");
    $secretPass = 'lol#hxc*)&$ID';

    $encoded = bin2hex(Encode($string,$secretPass));

    return $encoded;

}


function hxcdec($string){

    //include_once("/home/hxcmusi/public_html/crypt.php");
    $secretPass = 'lol#hxc*)&$ID';

    $decoded = Encode(hex2bin($string),$secretPass);

    return $decoded;

}
?>
