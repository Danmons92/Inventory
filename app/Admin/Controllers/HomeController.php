<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Borrowed;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Inventario')
            ->description('.')
            ->row(function (Row $row) {
                
                $this->infoboxInventory($row);
                $this->infoboxBorrow($row);
                $this->infoboxLong($row);

            });
    }

    /*
    *Renderiza infobox en el dashboard para el inventario
    */
    public function infoboxInventory(Row $row){

        return $row->column(4, function (Column $column) {
            $column->append(Dashboard::infoArt());
        });
    }

    /*
    *Renderiza infobox en el dashboard para el inventario
    */
    public function infoboxBorrow(Row $row){

        $countborrowed = Borrowed::all()->count();

        if ($countborrowed) {
            return  $row->column(4, function (Column $column) {
                    $column->append(Dashboard::infoBorrow());
            });
        }else {
            return;
        }

    }

    /*
    *Renderiza infobox en el dashboard para el inventario
    */
    public function infoboxLong(Row $row){

        $count = $this->countLong();

        if ($count > 0) {
            return $row->column(4, function (Column $column) {
                $column->append(Dashboard::infoBorrowLong($this->countLong()));
            });
        }else{
            return;
        }

    }

    /*
    *Cuenta la cantidad de registros con mas de 7 dias de prestados
    */
    public function countLong(){

        $count = 0;
        $borrows = Borrowed::all();
          
        foreach ($borrows as $b) {
            $created = Carbon::parse($b->created_at);
            $now = Carbon::now();
            $diff = $created->diffInDays($now);
                        
            if ($diff > 7) {
                $count = $count + 1;
            }
        }
        return $count;

    }
}
