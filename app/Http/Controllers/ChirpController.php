<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return view('chirps.index',['chirps' => Chirp::orderBy('created_at','desc')->get()]);
        // o usando el metodo latest()
        // Para evitar el problema N+1, hay que precarcar con with el usuairo al cual queremos consultar, para no pegarle al desempeño
        return view('chirps.index',['chirps' => Chirp::with('user')->latest()->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // aplicamos validaciones a los campos:
        // Con el $validated, pasamos ya los datos validades de los campos que se introducen y son validadeos
        // por el controlador que se mandan en esa variable

        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255']   // reglas de validacion para cada campo
        ]);

        //return auth()->id();  // solo regresa solo el id del usuario que intenta crear el chirp
        //return auth()->user();  // solo regresa todos los datos  del usuario que intenta crear el chirp
        // Asi quedaria sin el problema de asignacion masiva usando las relaciones HasMany

        // Otra forma es con $request en lugar de auth()
        //auth()->user()->chirps()->create([
        /*
        $request->user()->chirps()->create([
            'message' => $request->get('message'),
        ]);
        */
        // Tambien se pueden pasar los datos validados!!!
        $request->user()->chirps()->create($validated);
        /*
        Chirp::create([
            'message' => $request->get('message'),
            'user_id' => auth()->id(),                    // Dará problema de seguidad de asignacion masiva
        ]);
        //session()->flash('status','Chirp creada satisfactoriamente!!!');
        */

        return to_route('chirps.index')->with('status',__('Chirp created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    //Si le quitamos el Type Hint Chirp, solo devolverà el numero de chirp
    {
        //$chirp = Chirp::findOrFail($chirp);

        $this->authorize('update', $chirp);

//        if (auth()->user()->isNot($chirp->user)) {
//            abort(403);
//        }
        return view('chirps.edit', [
            'chirp' => $chirp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        /*
        if (auth()->user()->isNot($chirp->user)) {
            abort(403);
        }
        */

        $this->authorize('update', $chirp);  // Usando centralizacion de politicas de acceso de ChirpPolicy

        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255']   // reglas de validacion para cada campo
        ]);
        $chirp->update($validated);
        return to_route('chirps.index')->with('status', __('Chirp updated successfully!'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        // autorizamos la eliminacion
        $this->authorize('delete', $chirp);
        // Borramos en la Tabla
        $chirp->delete();
        // Volvemos al listado de los chirps y avisamos que hemos borrado el chirp

        return to_route('chirps.index')->with('status', __('Chirp deleted successfully!'));
    }
}
