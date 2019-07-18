<?php

namespace App\Admin\Controllers;

use App\Borrowed;
use App\Persons;
use App\Inventory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class BorrowedController extends Controller
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
            ->header('Articulos Prestados')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Borrowed);

        $grid->inventory()->name('Artiulo');
        $grid->inventory()->description('Descripcion');
        $grid->persons()->name('Persona');
        $grid->persons()->last_name('Apellido');
        $grid->description('Nota');
        $grid->created_at('Feha registro');

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
        $show = new Show(Borrowed::findOrFail($id));

        $show->inventory()->name('Artiulo');
        $show->persons()->name('Persona');
        $show->description('Nota');
        $show->created_at('Feha registro');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Borrowed);

        $form->select('item_id', 'Articulo')->rules('required')->options(Inventory::orderBy('name', 'asc')->pluck('name', 'id'));

        $form->select('person_id', 'Persona')->rules('required')->options(Persons::orderBy('name', 'asc')->pluck('name', 'id'));

        $form->text('description', 'Nota');

        $form->tools(function ($tools) {
			$tools->disableDelete();
			$tools->disableView();
		});
		$form->disableViewCheck();
		$form->disableEditingCheck();
		$form->disableCreatingCheck();


        $form->saving(function (Form $form) {

            $borrowed = Borrowed::where('item_id', $form->item_id)->first();

            \Debugbar::info($borrowed);

            if ($borrowed) {
                $error = new MessageBag([
                    'title'   => 'Error...',
                    'message' => 'Este articulo ya ha sido prestado. ',
                ]);
            
                return back()->with(compact('error'));
            }
            


            
        });

        return $form;
    }

}
