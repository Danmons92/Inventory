<?php

namespace App\Admin\Controllers;

use App\Warehouse;
use App\Inventory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WarehouseController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Almacenes')
            ->description('.')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Editar Almacen')
            ->description('.')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Agregar Almacen')
            ->description('.')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Warehouse);

        $grid->address1('Direccion 1');
        $grid->address2('Direccion 2');
        $grid->updated_at('Actualizado en');

        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Warehouse::findOrFail($id));

        $show->id('Id');
        $show->address1('Direccion 1');
        $show->address2('Direccion 2');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Warehouse);

        $form->text('address1', 'Direccion 1')->rules('required');
        $form->text('address2', 'Direccion 2');

        $form->tools(function ($tools) {
			$tools->disableDelete();
			$tools->disableView();
		});
		$form->disableViewCheck();
		$form->disableEditingCheck();
        $form->disableCreatingCheck();
        
        $form->deleting(function () {

            $whid = str_replace('juan/inventory/warehouse/', '', request()->path());

            $inventory = Inventory::where('warehouse_id', $whid)->first();

           if ($inventory) {

            return response()->json([
                'status'  => false,
                'message' => 'No se puede eliminar. Existen articulos cargados con este almacen',
            ]);
        }
        });

        return $form;
    }
}
