<?php
namespace App;
use IlluminateDatabaseEloquentModel;
 class Lead extends Model { 
         protected $fillable = ['name','category_id','slug','price','weight','description'];
              protected $dates = [];
              protected $tables = 'lead';
                //    public static $rules = [         'name' => 'required',         'category_id' => 'required',         'slug' => 'required',         'price' => 'required',         'weight' => 'required',         'description' => 'required',     ];
                 } 
