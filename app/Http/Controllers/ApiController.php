<?php

namespace App\Http\Controllers;

use App\MyClass\PaginatorMy;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $book = '[
  {
    "item": [
      {
        "id": 0,
        "isActive": true,
        "age": 35,
        "eyeColor": "brown",
        "name": "Isabel Noel",
        "gender": "female",
        "company": "RUBADUB",
        "email": "isabelnoel@rubadub.com",
        "phone": "+1 (905) 515-2287",
        "address": "353 Vanderveer Place, Welda, Texas, 3873"
      }
    ]
  },
  {
    "item": [
      {
        "id": 1,
        "isActive": true,
        "age": 28,
        "eyeColor": "green",
        "name": "Campos Pittman",
        "gender": "male",
        "company": "EVENTIX",
        "email": "campospittman@eventix.com",
        "phone": "+1 (847) 543-3337",
        "address": "860 Ralph Avenue, Yettem, Louisiana, 726"
      }
    ]
  },
  {
    "item": [
      {
        "id": 2,
        "isActive": true,
        "age": 28,
        "eyeColor": "brown",
        "name": "Sharpe French",
        "gender": "male",
        "company": "QUAREX",
        "email": "sharpefrench@quarex.com",
        "phone": "+1 (869) 472-3624",
        "address": "131 Hinsdale Street, Gasquet, Federated States Of Micronesia, 5052"
      }
    ]
  },
  {
    "item": [
      {
        "id": 3,
        "isActive": false,
        "age": 31,
        "eyeColor": "brown",
        "name": "Teresa Bush",
        "gender": "female",
        "company": "OVIUM",
        "email": "teresabush@ovium.com",
        "phone": "+1 (957) 433-3564",
        "address": "257 Williams Place, Elizaville, North Carolina, 2007"
      }
    ]
  },
  {
    "item": [
      {
        "id": 4,
        "isActive": true,
        "age": 33,
        "eyeColor": "green",
        "name": "Lowe Horne",
        "gender": "male",
        "company": "BARKARAMA",
        "email": "lowehorne@barkarama.com",
        "phone": "+1 (967) 560-3576",
        "address": "894 Amboy Street, Skyland, Connecticut, 156"
      }
    ]
  },
  {
    "item": [
      {
        "id": 5,
        "isActive": true,
        "age": 21,
        "eyeColor": "blue",
        "name": "Minnie Reese",
        "gender": "female",
        "company": "EPLODE",
        "email": "minniereese@eplode.com",
        "phone": "+1 (855) 481-3494",
        "address": "703 Prospect Place, Wilmington, Indiana, 1827"
      }
    ]
  }
]';

    private $pageSize = 2;
    private $sortDesc = 1;
    private $isActive = true;
    private $sorted = 'age';
    private $max_count_at_page = false;

    private function flattenJson($a){
        foreach ($a as $key => $el){
            if(is_object($el)){
                $a[$key] = (array)$el;
            }
        }
        return $a;
    }

    private function getBook(){
        $json_decode = json_decode($this->book);
        $collection = collect($json_decode);
        $flatten = $this->flattenJson($collection)->flatten();
        return $flatten->where('isActive', $this->isActive);
    }

    public function gotest(Request $request){
        if($request->sortDesc){
            $this->sortDesc = $request->sortDesc;
        }
        if($request->pageSize){
            $this->pageSize = $request->pageSize;
        }
        if($request->sorted) {
            $this->sorted = $request->sorted;
        }
        if($request->sortDesc){
            $this->sortDesc = $request->sortDesc;
        }
        if($request->isActive){
            $this->isActive = $request->isActive;
        }

        $activeItemAtBook = $this->getBook();
        if($this->sortDesc == 1){
            $active_and_sorted = $activeItemAtBook->sortBy($this->sorted)->values();
        }else{
            $active_and_sorted = $activeItemAtBook->sortByDesc($this->sorted)->values();
        }

        if($this->pageSize >= $active_and_sorted->count()){
            $this->max_count_at_page = true;
        }

        $book = $active_and_sorted->take($this->pageSize);

        return [
            'active_and_sorted' => $book,
            'sorted' => $this->sorted,
            'pageSize' => $this->pageSize,
            'max_count_at_page' => $this->max_count_at_page,
            'sortDesc' => $this->sortDesc,
        ];
    }
}
