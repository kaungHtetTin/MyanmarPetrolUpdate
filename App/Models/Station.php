<?php
    namespace App\Models;
    
    use Core\Model;

    class Station extends Model{
        public $table = "stations";
        
        public function informations($id){
            $Information = new Information;
            return $this->hasMany($Information, 'station_id',$id);
        }

        public function phones($id){
            $Phone = new Phone;
            return $this->hasMany($Phone, 'station_id',$id);
        }

        public function township($station){
            $township_id =  $station['township_id'];
            $Township = new Township;
            return $this->belongsTo($Township,$township_id);
        }

        public function company($station){
            $company_id = $station['company_id'];
            $Company = new Company;
            return $this->belongsTo($Company,$company_id);
        }
    }
