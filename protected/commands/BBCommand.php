<?php

class BBCommand extends CConsoleCommand {
    protected $categories = array();
    protected $manufacturers = array();
    
    public function run($args) {
        $categories = array();
        $category = Yii::app()->db->getCommandBuilder()->createFindCommand('Category', new CDbCriteria())->queryAll();
        foreach($category as $c){
           $this->categories[$c['BB']] = $c;
        }
        $manufacturers = array();
        $manufacturer = Yii::app()->db->getCommandBuilder()->createFindCommand('manufacturer', new CDbCriteria())->queryAll();
        foreach($manufacturer as $c){
           $this->manufacturers[$c['Name']] = $c;
        }
        
        
        foreach (Yii::app()->params['categories'] as $category){
            $page = 1;
            echo $category ."\n";
            $cnt = 0;
            do{
                $r = Yii::app()->bb->category($category)->page($page)->query();
                $fetch = $r['@attributes']['totalPages'] > $r['@attributes']['currentPage'];
                foreach($r['product'] as $p){
                    $data = array();
                    if(empty($p['name']))
                        continue;
                    
                    $data['Name'] = $p['name'];
                    $data['SKU'] = $p['sku'];
                    
                    $data['Category'] = $this->getCategory($p['categoryPath']['category']);
                    $data['Model'] = empty($p['modelNumber']) ? '' : $p['modelNumber'];
                    $data['Manufacturer'] = empty($p['manufacturer']) ? 0 : $this->getManufacturer($p['manufacturer']);
                    $data['Description'] = empty($p['longDescription'])?'':$p['longDescription'];
                    $data['DescriptionShort'] = empty($p['shortDescription'])?'':$p['shortDescription'];
                    
                    $images = array();
                    if(!empty($p['largeFrontImage']))
                        $images[] = $p['largeFrontImage'];
                    if(!empty($p['alternateViewsImage']))
                        $images[] = $p['alternateViewsImage'];
                    if(!empty($p['angleImage']))
                        $images[] = $p['angleImage'];
                    $data['Image'] = empty($images)?'':$images[0];
                    $data['Images'] = serialize($images);
                    
                    $data['Date'] = date('Y-m-d H:i:s');
                    
                    Yii::app()->db->getCommandBuilder()->createInsertCommand('listing', $data)->execute();
                    $cnt++;
                }
                $page++;
            }
            while($fetch);
            echo 'Count='.$cnt."\n";
        }
        
    }
    
    public function getManufacturer($name){
        if(!isset($this->manufacturers[$name])){
            Yii::app()->db->getCommandBuilder()->createInsertCommand('manufacturer', array('Name'=>$name))->execute();
            $this->manufacturers[$name] = array('Id'=>Yii::app()->db->getCommandBuilder()->getLastInsertID('manufacturer'));
        }
        return $this->manufacturers[$name];
    }
    
    public function getCategory($categories){
        $parentId = 0;
        foreach ($categories as $level => $c){
            if(!isset($this->categories[$c['id']])){
                $d = array('Name'=>$c['name'],'Level'=>$level,'BB'=>$c['id'],'PId' => $parentId);
                Yii::app()->db->getCommandBuilder()->createInsertCommand('Category', $d)->execute();
                $id = Yii::app()->db->getCommandBuilder()->getLastInsertID('Category');
                $d['Id'] = $id;
                $this->categories[$d['BB']] = $d;
            }else
                $id = $this->categories[$c['id']]['Id'];
            
            $parentId = $id;
        }
        
        return $id;
    }
}