<?php

namespace App\Admin\Controllers;

use App\Inventory;
use App\Warehouse;
use App\Category;
use App\Borrowed;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class InventoryController extends Controller
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
            ->header('Articulos')
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
            ->header('Editar Articulo')
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
            ->header('Crear Articulo')
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
        $grid = new Grid(new Inventory);

        $grid->name('Articulo');
        $grid->description('Descripcion');
        $grid->warehouse()->address1('Almacen');
        $grid->img('Img')->lightbox(['zooming' => true, 'width' => 50, 'height' => 50, 'class' => 'rounded']);
        $grid->category()->name('Categoria');
        $grid->price('Precio(USD)');

        $grid->filter(function($filter){

            $filter->equal('warehouse_id', 'Almacen')->select(Warehouse::all()->pluck('address1', 'id'));

            $filter->equal('category_id', 'Categoria')->select(Category::all()->pluck('name', 'id'));
         });

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
        $show = new Show(Inventory::findOrFail($id));

        $show->id('Id');
        $show->name('Articulo');
        $show->description('Descripcion');
        $show->warehouse()->address1('Almacen');
        $show->img('Img');
        $show->category()->name('Categoria');
        $show->price('Precio(USD)');
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
        $form = new Form(new Inventory);

        $form->text('name', 'Articulo');
        $form->text('description', 'Descripcion');
        $form->select('warehouse_id', 'Almacen')->rules('required')->options(Warehouse::orderBy('address1', 'asc')->pluck('address1', 'id'));
        $form->image('img', 'Img');
        $form->select('category_id', 'Categoria')->rules('required')->options(Category::orderBy('name', 'asc')->pluck('name', 'id'));
        $form->number('price', 'Precio(USD)');

        $form->tools(function ($tools) {
			$tools->disableDelete();
			$tools->disableView();
		});
		$form->disableViewCheck();
		$form->disableEditingCheck();
		$form->disableCreatingCheck();

        $form->deleting(function () {

            $itemid = str_replace('juan/inventory/inventory/', '', request()->path());

            $borrowed = Borrowed::where('item_id', $itemid)->first();

           if ($borrowed) {

            return response()->json([
                'status'  => false,
                'message' => 'No puedes borrar un articulo que ya este prestado',
            ]);
        }
        });

        return $form;
    }
}
