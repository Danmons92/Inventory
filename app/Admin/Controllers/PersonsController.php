<?php

namespace App\Admin\Controllers;

use App\Persons;
use App\Borrowed;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PersonsController extends Controller
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
            ->header('Personas')
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
            ->header('Editar Persona')
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
            ->header('Crear Persona')
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
        $grid = new Grid(new Persons);

        $grid->name('Nombre');
        $grid->last_name('Apellido');
        $grid->phone('Telefono');
        $grid->address('Direccion');
        $grid->created_at('Creado en');
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
        $show = new Show(Persons::findOrFail($id));

        $show->id('Id');
        $show->name('Nombre');
        $show->last_name('Apellido');
        $show->phone('Telefono');
        $show->address('Direccion');
        $show->created_at('Creado en');
        $show->updated_at('Actualizado en');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Persons);

        $form->text('name', 'Nombre');
        $form->text('last_name', 'Apellido');
        $form->mobile('phone', 'Telefono');
        $form->text('address', 'Direccion');

        $form->tools(function ($tools) {
			$tools->disableDelete();
			$tools->disableView();
		});
		$form->disableViewCheck();
		$form->disableEditingCheck();
        $form->disableCreatingCheck();
        
        $form->deleting(function () {

            $userid = str_replace('admin/inventory/users/', '', request()->path());

            $borrowed = Borrowed::where('person_id', $userid)->first();

           if ($borrowed) {

            return response()->json([
                'status'  => false,
                'message' => 'No se puede eliminar. Existe(n) registro(s) con esta persona en Prestamos',
            ]);
        }
        });

        return $form;
    }
}
