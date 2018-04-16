<?php 

class Statistics {
    public $sortby='';
    public $sortdir='';
    public $user_id='';
    public $start_date='';
    public $end_date='';
    public $ret= array(
        'firme_sunate'=>0,
        'discutii'=>0,
        'interesate'=>0,
        'vrea_contract'=>0,
        'users'=>array()
    );
    public $status_contract_statistics;
    
   
    function user_statistics_contract() {
        global $db,$config;


        foreach ($config['status_contract'] as $key => $value)
        if ($key>9) {
            $status[$key]['nr_free']=0;
            $status[$key]['val_free']=0;
            $status[$key]['val_tel']=0;
            $status[$key]['val_bo']=0;
            $status[$key]['serv_fixe']=0;
            $status[$key]['val_prod_rate']=0;
            $status[$key]['val_cloud']=0;
        }
        

        $sql = "SELECT rac.*,ra.user_id,ra.firma_id,sc.status,sc.date,f.nume,f.judet,f.localitate,f.cui FROM rezultat_apel ra  ";
        //$sql .= " LEFT JOIN  rezultat_apel_contract rac ON rac.rezultat_apel_id = ra.id ";
        $sql .= " LEFT JOIN  rezultat_apel_contract rac ON rac.id = (SELECT id FROM rezultat_apel_contract WHERE rezultat_apel_id = ra.id ORDER BY id DESC LIMIT 1) ";
        $sql .= " LEFT JOIN  firma f ON ra.firma_id = f.firma_id ";
        $sql .= " LEFT JOIN  status_contract sc ON sc.firma_id = ra.firma_id AND sc.last='1' ";
        $sql .= " WHERE ra.last='1' AND ra.rezultat='9' AND ra.date>'2018-01-28'";
        if ($this->user_id !='')
            $sql.= " AND  ra.user_id='".$this->user_id."'";
        if ($this->start_date !='')
            $sql.= " AND  sc.date >= '".$this->start_date."'";
        if ($this->end_date !='')
            $sql.= " AND  sc.date <= '".$this->end_date."'";
        $db->Query($sql);
        if ($db->iNumRows) { 
            $res=$db->ArrayResult;
            foreach ($res as $value){
                $status_details ='';
                if (is_numeric($value['nr_free5']) && is_numeric($value['nr_free10'])){
                    $status[$value['status']]['nr_free']+=$value['nr_free5']+$value['nr_free10'];
                    $status[$value['status']]['val_free']+=5*$value['nr_free5']+10*$value['nr_free10'];
                    if ($value['nr_free5'] != 0)
                        $status_details .='Nr Free5: '.$value['nr_free5'];
                    if ($value['nr_free10'] != 0)    
                        $status_details .=', Nr Free10: '.$value['nr_free10'];
                }
                if (is_numeric($value['val_tel']) && $value['val_tel'] !=0){                    
                    $status[$value['status']]['val_tel']+=$value['val_tel'];
                    $status_details.=', Val Tel: '.$value['val_tel'];
                }
                if (is_numeric($value['val_bo']) && $value['val_bo'] !=0) {                  
                    $status[$value['status']]['val_bo']+=$value['val_bo'];
                    $status_details.=', Val Bo: '.$value['val_bo'];
                }
                if (is_numeric($value['serv_fixe']) && $value['serv_fixe'] !=0) {                   
                    $status[$value['status']]['serv_fixe']+=$value['serv_fixe'];
                    $status_details.=', Serv fixe: '.$value['serv_fixe'];
                }
                if (is_numeric($value['val_prod_rate']) && $value['val_prod_rate'] !=0){                    
                    $status[$value['status']]['val_prod_rate']+=$value['val_prod_rate'];
                    $status_details.=', Val Prod Rate: '.$value['val_prod_rate'];
                }
                if (is_numeric($value['val_cloud']) && $value['val_cloud'] !=0)  {                  
                    $status[$value['status']]['val_cloud']+=$value['val_cloud'];
                    $status_details.=', Val cloud: '.$value['val_cloud'];
                }
                
                if ($value['nume'] != '')
                    $status[$value['status']]['firme'][$value['firma_id']]=$value['nume'].','.$value['judet'].','.$value['localitate'].','.$value['cui'];
                    else $status[$value['status']]['firme'][$value['firma_id']]='Firma inexistenta';
                
                $status[$value['status']]['firme'][$value['firma_id']].=' - '.$value['date'].'<br>'.$status_details.'<hr class="hr_small">';
                
            }
            $this->status_contract_statistics=$status;
        }

    }

    function user_statistics() {
        global $db;

        $sql = "SELECT ra.* FROM rezultat_apel ra  ";
        $sql .= " LEFT JOIN contactare_firma cf ON cf.id = ra.contactare_id ";
        $sql .= " WHERE ra.last='1'";
        if ($this->user_id !='')
            $sql.= " AND  cf.user_id='".$this->user_id."'";
        if ($this->start_date !='')
            $sql.= " AND  cf.date >= '".$this->start_date."'";
        if ($this->end_date !='')
            $sql.= " AND  cf.date <= '".$this->end_date."'";
        $sql .= " ORDER BY cf.id ";
        $db->Query($sql);
        if ($db->iNumRows) { 
            $this->ret['firme_sunate']=$db->iNumRows;
            $res=$db->ArrayResult;
            foreach ($res as $value){
                $this->ret['users'][$value['user_id']]['firme_sunate']++;
                if ($value['rezultat'] != '1' && $value['rezultat'] != '2' ){
                    $this->ret['discutii']++;
                    $this->ret['users'][$value['user_id']]['discutii']++;
                }
                if ($value['rezultat']==7){
                    $this->ret['interesate']++;
                    $this->ret['users'][$value['user_id']]['interesate']++;
                }
                if ($value['rezultat']==9){
                    $this->ret['vrea_contract']++;
                    $this->ret['users'][$value['user_id']]['vrea_contract']++;
                }

                $curr_date=$value['date'];
                if (!isset($min_date) || $min_date>$curr_date)
                    $min_date=$curr_date;
                if (!isset($max_date) || $max_date<$curr_date)
                    $max_date=$curr_date;
                
            }
            
            $days=date_diff(date_create($min_date),date_create($max_date));
            $this->ret['days']=$days->format("%a");
            
            if ($this->ret['days']==0) $this->ret['days']=1;
            $this->ret['medii_discutii_zi']=round($this->ret['discutii']/$this->ret['days']);

            if (isset($this->ret['users'])){
                foreach($this->ret['users'] as $user_id=>$value){
                    $this->ret['users'][$user_id]['user_id']=$user_id;
                    $this->ret['users'][$user_id]['medii_discutii_zi']=round($this->ret['users'][$user_id]['discutii']/$this->ret['days']);
                }
                
                if ($this->sortby != '')
                    array_sort_by_column($this->ret['users'],$this->sortby);
                
            }

        }

    }
}

?>