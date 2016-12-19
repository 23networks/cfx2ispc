<?php
// TODO
$my_hostname = "server23.example.com";


// -----------------------------------------------------
$username = 'admin';
$password = 'sososecret';
$soap_location = 'https://ispc23.example.com:8080/remote/index.php';
$soap_uri = 'http://ispc23.example.com:8080/remote/';

$sqlconn = new mysqli("localhost", "confixx", "sososecret", "confixx");
$sqlconn->set_charset("utf8");

$create_res=false;
$create_usr=false;
$create_domain=true;
$create_maildomain=false;
$create_mbox=false;

$client = new SoapClient(null, array('location' => $soap_location,'uri' => $soap_uri));
try {
    //* Login to the remote server
    if($session_id = $client->login($username,$password)) {
        echo 'Logged into remote server sucessfully. The SessionID is '.$session_id."\n";
    }

    //////////////////////////
    /// Add Resellers
    //////////////////////////
    $result = $sqlconn->query("select * from anbieter");
    while($create_res && $row = $result->fetch_assoc()) {
        #print_r($row);   # $row[""]

        $params = array(
            #'company_name' => 'awesomecompany',
            'contact_name' => $row["name"] ? $row["name"] : $row["firstname"],
            #'customer_no' => '1',
            #'vat_id' => '1',
            #'street' => 'fleetstreet',
            #'zip' => '21337',
            #'city' => 'london',
            #'state' => 'bavaria',
            #'country' => 'UK',
            #'telephone' => '123456789',
            #'mobile' => '987654321',
            #'fax' => '546718293',
            'email' => $row["emailadresse"] ? $row["emailadresse"] : "hostmaster@example.com",
            #'internet' => '',
            #'icq' => '111111111',
            #'notes' => 'awesome',
            'limit_maildomain' => $row["maxdomains"],
            'limit_mailbox' => $row["maxpop"],
            'limit_mailalias' => -1,
            'limit_mailaliasdomain' => -1,
            'limit_mailforward' => -1,
            'limit_mailcatchall' => -1,
            #'limit_mailrouting' => 0,
            'limit_mailfilter' => -1,
            'limit_fetchmail' => -1,
            'limit_mailquota' => -1,
            #'limit_spamfilter_wblist' => 0,
            #'limit_spamfilter_user' => 0,
            #'limit_spamfilter_policy' => 1,
            'default_webserver' => 1,
            #'limit_web_ip' => '',
            #'limit_web_domain' => -1,
            #'limit_web_quota' => -1,
            'web_php_options' => 'no,fast-cgi,cgi,mod,suphp',
            #'limit_web_subdomain' => -1,
            #'limit_web_aliasdomain' => -1,
            #'limit_ftp_user' => -1,
            #'limit_shell_user' => 0,
            'ssh_chroot' => 'no,jailkit,ssh-chroot',
            #'limit_webdav_user' => 0,
            #'default_dnsserver' => 1,
            #'limit_dns_zone' => -1,
            #'limit_dns_slave_zone' => -1,
            #'limit_dns_record' => -1,
            'default_dbserver' => 1,
            'default_mailserver' => 1,
            #'limit_database' => -1,
            #'limit_cron' => 0,
            #'limit_cron_type' => 'url',
            #'limit_cron_frequency' => 5,
            #'limit_traffic_quota' => -1,
            'limit_client' => 1,    # 0=normal 1=reseller
            #'parent_client_id' => 0,
            'username' => $row["anbieter"],
            'password' => $row["longpw"],
            '_ispconfig_pw_crypted' => 1,
            'language' => 'de',
            'usertheme' => 'default',
            'template_master' => 0,
            'template_additional' => '',
            #'created_at' => 0
            );
        $affected_rows = $client->client_add($session_id, 0, $params);
        echo "Added reseller ".$row["anbieter"].", ID ".$affected_rows."\n";
    }

    //////////////////////////
    /// Add Users
    //////////////////////////
    $result = $sqlconn->query("select * from kunden");
    while($create_usr && $row = $result->fetch_assoc()) {
        $row["kunde"] = str_replace("web","usr",$row["kunde"]);
        #print_r($row);   # $row[""]

        echo "Adding ".$row["kunde"]."... ";

        $res = $client->client_get_by_username($session_id, $row["anbieter"]);
        $params = array(
            #'company_name' => 'awesomecompany',
            'contact_name' => $row["name"] ? $row["name"] : ($row["firstname"] ? $row["firstname"] : $row["kunde"]),
            #'customer_no' => '1',
            #'vat_id' => '1',
            #'street' => 'fleetstreet',
            #'zip' => '21337',
            #'city' => 'london',
            #'state' => 'bavaria',
            #'country' => 'UK',
            #'telephone' => '123456789',
            #'mobile' => '987654321',
            #'fax' => '546718293',
            'email' => $row["emailadresse"] ? $row["emailadresse"] : "hostmaster@example.com",
            #'internet' => '',
            #'icq' => '111111111',
            #'notes' => 'awesome',
            'limit_maildomain' => $row["maxdomains"],
            'limit_mailbox' => $row["maxpop"],
            'limit_mailalias' => -1,
            'limit_mailaliasdomain' => -1,
            'limit_mailforward' => -1,
            'limit_mailcatchall' => -1,
            #'limit_mailrouting' => 0,
            'limit_mailfilter' => -1,
            'limit_fetchmail' => -1,
            'limit_mailquota' => -1,
            #'limit_spamfilter_wblist' => 0,
            #'limit_spamfilter_user' => 0,
            #'limit_spamfilter_policy' => 1,
            'default_webserver' => 1,
            #'limit_web_ip' => '',
            #'limit_web_domain' => -1,
            #'limit_web_quota' => -1,
            'web_php_options' => 'no,fast-cgi,cgi,mod,suphp',
            #'limit_web_subdomain' => -1,
            #'limit_web_aliasdomain' => -1,
            #'limit_ftp_user' => -1,
            #'limit_shell_user' => 0,
            'ssh_chroot' => 'no,jailkit,ssh-chroot',
            #'limit_webdav_user' => 0,
            #'default_dnsserver' => 1,
            #'limit_dns_zone' => -1,
            #'limit_dns_slave_zone' => -1,
            #'limit_dns_record' => -1,
            'default_dbserver' => 1,
            'default_mailserver' => 1,
            #'limit_database' => -1,
            #'limit_cron' => 0,
            #'limit_cron_type' => 'url',
            #'limit_cron_frequency' => 5,
            #'limit_traffic_quota' => -1,
            'limit_client' => 0,    # 0=normal 1=reseller
            'parent_client_id' => $res["client_id"],
            'username' => $row["kunde"],
            'password' => $row["longpw"],
            '_ispconfig_pw_crypted' => 1,
            'language' => 'de',
            'usertheme' => 'default',
            'template_master' => 0,
            'template_additional' => '',
            #'created_at' => 0
            );
        $affected_rows = $client->client_add($session_id, $res["client_id"], $params);
        echo "Added client ".$row["kunde"]." (".$row["anbieter"]."), ID ".$affected_rows."\n";
    }


    //////////////////////////
    /// Add Domains
    //////////////////////////
    $result = $sqlconn->query("select domain,replace(kunde,\"web\",\"usr\") as kunde from domains where richtigedomain=1");
    while($create_domain && $row = $result->fetch_assoc()) {
      //print_r($row);
      echo "adding domain ".$row["domain"]." for ".$row["kunde"]."... ";
      $res = $client->client_get_by_username($session_id, $row["kunde"]);
      $params = array(
        'domain' => $row["domain"],
      );
      $domain_id = $client->domains_domain_add($session_id, $res["client_id"], $params);
      echo "Domain ID: ".$domain_id."\n";
    }


    //////////////////////////
    /// Add Maildomains
    //////////////////////////
    $params = array(
        'server_id' => 1,
        'domain' => $my_hostname,
        'active' => 'y'
    );
    if($create_maildomain) {
      $domain_id = $client->mail_domain_add($session_id, 1, $params);
      echo "added main mail domain id: ".$domain_id."\n";
    }
    $result = $sqlconn->query("select distinct replace(kunde,\"web\",\"usr\") as kunde,domain from email union select replace(kunde,\"web\",\"usr\") as kunde,domain from domains where richtigedomain=1;");
    while($create_maildomain && $row = $result->fetch_assoc()) {
      //print_r($row);
      echo "adding domain ".$row["domain"]." for ".$row["kunde"]."... ";
      $res = $client->client_get_by_username($session_id, $row["kunde"]);
      $params = array(
        'server_id' => 1,
        'domain' => $row["domain"],
        'active' => 'y'
      );
      $domain_id = $client->mail_domain_add($session_id, $res["client_id"], $params);
    }

    //////////////////////////
    /// Add Mailboxes
    //////////////////////////
    // mailboxes without forwards: select pop3.account,domains.domain,pop3.longpw from pop3,domains left join email_forward on pop3.account = email_forward.pop3 where email_forward.pop3 is null and domains.kunde=pop3.kunde and domains.richtigedomain = 1 group by pop3.account;
    // mailboxes with forwards: $result = $sqlconn->query("select distinct email_forward.pop3,email.domain,pop3.longpw from email_forward,email,domains,pop3 where email_forward.email_ident = email.ident and email_forward.pop3 not like \"%@%\" and email.domain=domains.domain and email_forward.pop3 = pop3.account group by pop3");
    $result = $sqlconn->query("select pop3.account,domains.domain,pop3.longpw,replace(pop3.kunde,\"web\",\"usr\") as kunde from pop3,domains left join email_forward on pop3.account = email_forward.pop3 where email_forward.pop3 is null and domains.kunde=pop3.kunde and domains.richtigedomain = 1 group by pop3.account union select distinct email_forward.pop3 as account,email.domain,pop3.longpw,replace(pop3.kunde,\"web\",\"usr\") as kunde from email_forward,email,domains,pop3 where email_forward.email_ident = email.ident and email_forward.pop3 not like \"%@%\" and email.domain=domains.domain and email_forward.pop3 = pop3.account group by pop3;");
    while($create_mbox && $row = $result->fetch_assoc()) {
      echo "Creating new mailuser: ".$row["account"].'@'.$row["domain"]."...";

      $res = $client->client_get_by_username($session_id, $row["kunde"]);
      $params = array(
        'server_id' => 1,
        'email' => $row["account"].'@'.$row["domain"],
        'login' => $row["account"],
        'password' => $row["longpw"],
        '_ispconfig_pw_crypted' => 1,
        #'name' => 'joe',
        #'uid' => 5000,
        #'gid' => 5000,
        #'maildir' => '/var/vmail/test.int/joe',
        'quota' => 5242880,
        #'cc' => '',
        #'homedir' => '/var/vmail',
        #'autoresponder' => 'n',
        #'autoresponder_start_date' => array('day' => 1, 'month' => 7, 'year' => 2012, 'hour' => 0, 'minute' => 0),
        #'autoresponder_end_date' => array('day' => 20, 'month' => 7, 'year' => 2012, 'hour' => 0, 'minute' => 0),
        #'autoresponder_text' => 'hallo',
        #'move_junk' => 'n',
        #'custom_mailfilter' => 'spam',
        #'postfix' => 'n',
        'access' => 'y',
        'disableimap' => 'n',
        'disablepop3' => 'n',
        'disabledeliver' => 'n',
        'disablesmtp' => 'n'
      );
      //$affected_rows = $client->mail_user_add($session_id, $res["client_id"], $params);
      echo "New mailuser: ".$row["account"].'@'.$row["domain"].": ".$affected_rows."\n";

      // Add default aliase web1p2@server3 -> web1p2@mydomain
      $params = array(
        'server_id' => 1,
        'source' => $row["account"].'@'.$my_hostname,
        'destination' => $row["account"].'@'.$row["domain"],
        'type' => 'alias',
        'active' => 'y'
      );
      echo "Adding ".$row["account"].'@'.$my_hostname." -> ". $row["account"].'@'.$row["domain"]."... ";
      //$affected_rows = $client->mail_alias_add($session_id, $res["client_id"], $params);
      echo "Alias ID:  ".$affected_rows."\n";

      // Add mailadresses for this mailbox
      $resfwd = $sqlconn->query("select email.prefix,email.domain from email,email_forward where email_forward.email_ident=email.ident and email_forward.pop3 = \"".$row["account"]."\"");
      while($fwd = $resfwd->fetch_assoc()) {
        echo "Adding alias ".$fwd["prefix"]."@".$fwd["domain"]." to ".$row["account"].'@'.$row["domain"]."...";
        $params = array(
          'server_id' => 1,
          'source' => $fwd["prefix"]."@".$fwd["domain"],
          'destination' => $row["account"].'@'.$row["domain"],
          'type' => 'alias',
          'active' => 'y'
        );
        //$affected_rows = $client->mail_alias_add($session_id, $res["client_id"], $params);
        echo "Alias ID:  ".$affected_rows."\n";

      }

    }

    $result = $sqlconn->query("select replace(email.kunde,\"web\",\"usr\") as kunde,email.prefix,email.domain,email_forward.pop3 from email,email_forward where email_forward.email_ident=email.ident and email_forward.pop3 like \"%@%\"");
    while($create_mbox && $row = $result->fetch_assoc()) {
      $params = array(
        'server_id' => 1,
        'source' => $row["prefix"]."@".$row["domain"],
        'destination' => $row["pop3"],
        'type' => 'forward',
        'active' => 'y'
      );
      $res = $client->client_get_by_username($session_id, $row["kunde"]);
      $affected_rows = $client->mail_forward_add($session_id, $res["client_id"], $params);
      echo "Forwarding ".$row["prefix"]."@".$row["domain"]." -> ".$row["pop3"]." ID: ".$affected_rows."\n";
    }

    if($client->logout($session_id)) {
        echo "Logged out.\n";
    }

    } catch (SoapFault $e) {
        echo('SOAP Error: '.$e->getMessage()."\n");
    }
