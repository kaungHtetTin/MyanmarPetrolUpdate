<?php
    namespace App\Models;
    
    use Core\Model;
    use App\Models\OilType;

    class Information extends Model{
        public $table = "informations";

        public function oilType($information){
            $oil_type_id = $information['oil_type_id'];
            $OilType = new OilType;
            return $this->belongsTo($OilType,$oil_type_id);
        }
        
    }
