<?php

namespace App\Admin\Controllers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;
use Encore\Admin\Widgets\InfoBox;
use App\Inventory;
use App\Borrowed;

class Dashboard
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('admin::dashboard.title');
    }


    /**
 * Informacion de todos los items en inventario
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public static function infoArt() {
        $infoBox = new InfoBox('Articulo(s) en Inventario', 'suitcase', 'aqua', admin_url('/inventory/inventory'), Inventory::all()->count());
        return $infoBox->render();
    }

            /**
 * Informacion de articulos prestados
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public static function infoBorrow() {
        $infoBox = new InfoBox('Articulo(s) prestados', 'warning', 'yellow', admin_url('/inventory/inventory'), Borrowed::all()->count());
        return $infoBox->render();
    }

                /**
 * Informacion de articulos prestados con mas de 7 dias
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public static function infoBorrowLong($count) {
        $infoBox = new InfoBox('Articulo(s) prestados con mas de 7 dias', 'warning', 'red', admin_url('/inventory/inventory'), $count);
        return $infoBox->render();
    }
    
}
