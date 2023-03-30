<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;

class Productos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $Nombre, $Precio, $Stock;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.productos.view', [
            'productos' => Producto::latest()
						->orWhere('Nombre', 'LIKE', $keyWord)
						->orWhere('Precio', 'LIKE', $keyWord)
						->orWhere('Stock', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
    }
	
    private function resetInput()
    {		
		$this->Nombre = null;
		$this->Precio = null;
		$this->Stock = null;
    }

    public function store()
    {
        $this->validate([
		'Nombre' => 'required',
		'Precio' => 'required',
		'Stock' => 'required',
        ]);

        Producto::create([ 
			'Nombre' => $this-> Nombre,
			'Precio' => $this-> Precio,
			'Stock' => $this-> Stock
        ]);
        
        $this->resetInput();
		$this->dispatchBrowserEvent('closeModal');
		session()->flash('message', 'Producto Successfully created.');
    }

    public function edit($id)
    {
        $record = Producto::findOrFail($id);
        $this->selected_id = $id; 
		$this->Nombre = $record-> Nombre;
		$this->Precio = $record-> Precio;
		$this->Stock = $record-> Stock;
    }

    public function update()
    {
        $this->validate([
		'Nombre' => 'required',
		'Precio' => 'required',
		'Stock' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Producto::find($this->selected_id);
            $record->update([ 
			'Nombre' => $this-> Nombre,
			'Precio' => $this-> Precio,
			'Stock' => $this-> Stock
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('closeModal');
			session()->flash('message', 'Producto Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            Producto::where('id', $id)->delete();
        }
    }
}