<?php
define('DEBUG_ON','1');
define('USE_MYSQL_ESCAPE',true);

define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'].'/crm/');
define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/crm/'); // URL
define('LIB_PATH', '/devcrm/lib/');

$config['db_server']	= 'localhost';
$config['db_user']	= 'r53254next_crm';
$config['db_password']	= '!Marher123';
$config['db_db']	= 'r53254next_crm';

$config['email_from'] = 'info@nextcall.ro';

$config['user_role'] = array(1=>'Operator',2=>'Administrator',3=>'Vanzator',4=>'Derulator',5=>'Hunter',6=>'Incepator');

$config['no_per_page']=array('10'=>10,'20'=>20,'50'=>50,'100'=>100,'500'=>500,'1000'=>'1000');

$config['judet']=array("bc"=>"Bacau","br"=>"Braila","bt"=>"Botosani","gl"=>"Galati","is"=>"Iasi",'nt'=>"Neamt",'sv'=>'Suceava','vs'=>"Vaslui","vn"=>"Vrancea");
//$config['judet']=array('nt'=>"Neamt");

$config['editare_firma_fields']=array('nume','judet','localitate','adresa','numar','cui','activitate','cifra_afaceri','nume_contact','telefon','mobil','mobil2','email','web','nume_mobil','nr_simuri','factura','data_final','obs'
);

$config['editare_user_fields']=array('email','fname','lname','password','user_role');

$config['noyes']=array(
    0=>'No',
    1=>'Yes',
);

$config['rezultat_apel']=array(
            1=>'Nu raspunde',
            2=>'Numar gresit',
            3=>'De revenit',
            4=>'Firma inchisa',
            5=>'Telekom juridic',
            6=>'Telekom fizica',
            //7=>'Interesat',
            8=>'Doreste eMAIL',
            11=>'Negociere',
            9=>'Vrea contract',
            10=>'Neinteresat'
            );
/*
$config['status_contract'] = array(
    '1' => 'Verificare credit risk',
    //'2' => 'Credit risk ok',
    '3' => 'Credit risk depozit',
    '4' => 'Intocmire documente',
    '5' => 'Semnare documente',
    '6' => 'Implementare',
    '7' => 'Activat',
    '8' => 'Respins'
);
*/
$config['tip_livrare']= array(
    '0'=>'',
    '1'=>'Curier',
    '2'=>'Magazin',
    '3'=>'Dispatch'
);

$config['tip_ctr']= array(
    '0'=>'',
    '1'=>'TKR',
    '2'=>'TKRM',
    '3'=>'Rate',
    '4'=>'Cloud',
    '5'=>'Resemnare'
);

$config['status_contract'] = array(
    /*'1' => 'Verificare credit risk',
    '2' => 'Credit risk ok',
    '3' => 'Credit risk depozit',
    '4' => 'Intocmire documente',
    '5' => 'Semnare documente',
    '6' => 'Implementare',
    '7' => 'Activat',
    '8' => 'Respins',*/
    '11' => 'Rezolvata',
    '12' => 'Emisa',
    '13' => 'In asteptare plata depozit avans',
    '14' => 'In semn contract magazin',
    '15' => 'In analiza frauda',
    '16' => 'In evaluare credit risc',
    '17' => 'In asteptare resurse xdsl',
    '18' => 'Respinsa',
    '19' => 'Anulata'

);


$config['rezultat_apel_email']=array(
    1=>'Solutie Rate Telekom',
    2=>'Telekom - internet, Tv',
    3=>'Voce Mobila Telekom',
    //4=>'Solutie GPS',
    5=>'Solutie GPS promo',
    6=>'Solutie Office 365',
    //7=>'Freedom Mobile 10',
    8=>'Freedom Mobile 5'
);

$config['email_attach']=array(
    1=>BASE_PATH.'mail/Solutie_Rate_Telekom.pdf',
    2=>BASE_PATH.'mail/Telekom_internet_tv.pdf',
    3=>BASE_PATH.'mail/Voce_Mobila_Telekom.pdf',
    //4=>BASE_PATH.'mail/Solutie_GPS.pdf',
    5=>BASE_PATH.'mail/Solutie_GPS_promo.pdf',
    6=>BASE_PATH.'mail/Solutie_Office_365.pdf',        
    //7=>BASE_PATH.'mail/Freedom_Mobile_10.pdf',        
    8=>BASE_PATH.'mail/Freedom_Mobile_5.pdf'        
);

$config['vrea_contract']=array(
    'nr_free5' => 'Nr Free 5',
    'nr_free10' => 'Nr Free 10',
    'val_tel' => 'Valoare Tel',
    'serv_fixe' => 'Servicii fixe',
    'val_prod_rate' => 'Val produse rate',
    'val_cloud' => 'Valoare cloud',
    'val_bo' => 'Val Bo',
    //'detalii_bo' => 'Detalii Bo',
    'obs' => 'Observatii'
);

/*$config['vrea_contract']=array(
    'nr_free5' => 'Nr Free 5',
    'nr_free10' => 'Nr Free 10',
    'pret_produse' => 'Pret produse',
    'perioada' => 'Perioada',
    'val_internet' => 'Valoare internet',
    'val_tel_fix' => 'Valoare tel fix',
    'val_tv' => 'Valoare TV',
    'nr_stb' => 'Nr Stb',
    'val_cloud' => 'Valoare Cloud',
    'val_rate' => 'Valoare Rate',
    'produse' => 'Produse',
    'adresa_livrare' => 'Adresa Livrare',
    'obs' => 'Observatii'
);*/



$config['default_emails'][1]="
Stimate Partener,
<br>Daca v-ati saturat sa tot contorizati si sa platiti pentru traficul de net suplimentar facut de dvs sau de vreun angajat...
<br>Daca v-ati saturat de perioade contractuale, taxe de reziliere si vreti sa fiti intradevar liber...
<br>De <b>ACUM</b> aveti totul inclus in abonament, si totul <b>NELIMITAT</b> cu un abonament <b>LIBER DE CONTRACT</b>.
<br><br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> date mobile 4G
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute si sms nationale
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute si sms roaming UE
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute internationale zona 1
<br><br><b>TOTUL</b> la doar <b><font color=red>5 euro/luna</font></b>
<br><br>Suplimentar va puteti lua si telefon, fara nicio dobanda, pe orice perioada cuprinsa intre 1-36 luni:
<br> - Huawei P10 Lite, incepand de la 5 &euro;/luna<br> - Samsung S8, incepand de la 14 &euro;/luna<br> - Iphone 8, incepand de la 18 &euro;/luna
<br><br>
O zi frumoasa,
<br><br><b>HERDES MARIUS</b><br><br>National Sales Manager<br>B2B ALTERNATIVE CHANNELS<br>NBS Partener Telekom<br>Comercial Division<br>
Mobile: +40 786.716.323<br>E-mail: petru.herdes@dealers.telekom.ro<br>Web: www.telekom.ro<br>
<img src='".BASE_URL."mail/footer_email_img.jpg'>";

$config['default_emails'][2]="
Stimate Partener,
<br>Daca v-ati saturat sa tot contorizati si sa platiti pentru traficul de net suplimentar facut de dvs sau de vreun angajat...
<br>Daca v-ati saturat de perioade contractuale, taxe de reziliere si vreti sa fiti intradevar liber...
<br>De <b>ACUM</b> aveti totul inclus in abonament, si totul <b>NELIMITAT</b> cu un abonament <b>LIBER DE CONTRACT</b>.
<br><br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> date mobile 4G
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute si sms nationale
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute si sms roaming UE
<br>&nbsp;&nbsp;&nbsp;&#10004;&nbsp;&nbsp;<u><b>Nelimitat</b></u> minute internationale zona 1
<br><br><b>TOTUL</b> la doar <b><font color=red>5 euro/luna</font></b>
<br><br>Suplimentar va puteti lua si telefon, fara nicio dobanda, pe orice perioada cuprinsa intre 1-36 luni:
<br> - Huawei P10 Lite, incepand de la 5 &euro;/luna<br> - Samsung S8, incepand de la 14 &euro;/luna<br> - Iphone 8, incepand de la 18 &euro;/luna
<br>Atasat gasiti oferte detaliate!<br><br>
O zi frumoasa,
<br><br><b>HERDES MARIUS</b><br><br>National Sales Manager<br>B2B ALTERNATIVE CHANNELS<br>NBS Partener Telekom<br>Comercial Division<br>
Mobile: +40 786.716.323<br>E-mail: petru.herdes@dealers.telekom.ro<br>Web: www.telekom.ro<br>
<img src='".BASE_URL."mail/footer_email_img.jpg'>";
//$config['default_emails'][1]="
//Stimate Partener,<br><br>Iti multumim pentru atentia acordata Telekom Romania si avem deosebita placere sa iti aducem la cunostinta oferta noastra, oferta atasata acestui mail.<br><br>O zi frumoasa,<br><br><b>HERDES MARIUS</b><br><br>National Sales Manager<br>B2B ALTERNATIVE CHANNELS<br>NBS Partener Telekom<br>Comercial Division<br>
//Mobile: +40 786.716.323<br>E-mail: petru.herdes@dealers.telekom.ro<br>Web: www.telekom.ro<br>
//";

$config['email_from_sales']="Telekom Romania<solutii.telekom@gmail.com>";
$config['email_subject_from_sales']="Solutii Telekom pentru afacerea ta";
?>