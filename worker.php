<?php
$domains = array();

$list = getList();
if($list){
    $i = 1;
    $c = count($domains);

    //domains_total
    file_put_contents('/app/data/domains_total', $c);

    foreach($domains as $domain){
        $isAvailable = isAvailable($domain['domain'], $domain['tld']);
        
        if($isAvailable){
            echo 'Y|'.$domain['domain']."\n";
            file_get_contents('http://intern.kat2.net/api/domaining/add-domain/?domain='.$domain['domain']);
        }else{
            echo 'N|'.$domain['domain']."\n";
        }

        //domains_done
        file_put_contents('/app/data/domains_done', $i);
        
        //domains_left
        file_put_contents('/app/data/domains_left', $c - $i);

        $i++;
    }

    file_get_contents('http://intern.kat2.net/api/domaining/list-done/?id='.$list);

    //remove lock
    unlink('/app/data/lock');
}else{
    echo 'no list found';
}

function getList(){
    $data = file_get_contents('http://intern.kat2.net/api/domaining/get-list/?worker='.getenv('name'));
    $array = json_decode($data, true);

    if($array['success'] == true){
        getDomains($array['url']);

        //list_id
        file_put_contents('/app/data/list_id', $array['id']);

        return $array['id'];
    }else{
        return false;
    }
}

function getDomains($url){
    global $domains;
    
    $list = file_get_contents($url);
    $lines = explode("\n", $list);

    $domains = array();
    foreach($lines as $line){
        $length = strlen($line);

        if(($length > 5) && ($length < 13)){
            if(strstr($line, '.')){
                $tld = getTLD($line);

                if(
                    ($tld == 'org')
                ){
                    $domains[] = array(
                        'domain' => $line,
                        'tld' => $tld,
                        'length' => $length
                    );
                }
            }
        }
    }

    return $domains;
}

function getTLD($domain){
    return str_replace(explode('.', $domain)[0].'.', '', $domain);
}

function isAvailable($domain, $tld){
    $finders = array(
        'com' => array('whois.crsnic.net', 'No match for'),
        'net' => array('whois.crsnic.net', 'No match for'),
        'org' => array('whois.publicinterestregistry.net', 'NOT FOUND')
    );

    $server = $finders[$tld][0];
    $finder = $finders[$tld][1];

    if(
        ($server == 'whois.publicinterestregistry.net')
    ){
        sleep(15);
    }

	$fp = @fsockopen($server, 43, $errno, $errstr, 10);
	if($server == "whois.verisign-grs.com"){
        $domain = "domain ".$domain;
    }
	fputs($fp, $domain . "\r\n");
	$out = "";
	while(!feof($fp)){$out .= fgets($fp);}
	fclose($fp);

    if(strstr($out, $finder)){
        return true;
    }else{
        return false;
    }
}