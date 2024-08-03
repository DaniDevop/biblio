<?php

namespace App\Http\Controllers;

use App\Models\Livres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    


    public function index(){

         $livresAll=Livres::orderBy('id','desc')->paginate(5);
        return view('dashboard',[
            'livresAll'=> $livresAll
        ]);
    }

    public function addLivres(){

       return view('store',[
           
       ]);
   }

   public function store(Request $request){

    $request->validate([
        'titre'=> 'required',
        'couverture'=> 'required|image|mimes:jpg,jpeg,png',
        'livres'=> 'required|mimes:pdf',
    ],[
        'titre.required'=> 'Le nom du livres est requis !',
        'couverture.required'=> 'La photo de couverture est requis !',
        'couverture.image'=> 'La couverture doit etre de type image',
        'couverture.mimes'=> 'Le type de l image n est pas correct Ex : png,jpeg,jpg',
        'livres.required'=> 'Le livres est requis pour valider',
        'livres.mimes'=> 'Le livres est de type pdf',
    ]);

    $livres=new Livres();
    $livres->designation=$request->titre;
    $livres->couverture=$request->file('couverture')->store('livres','public');
    $livres->livres=$request->file('livres')->store('livres','public');
    $livres->user_id=Auth::user()->id;
    $livres->save();
    return back()->with('success','Livre ajouté avec succèss !');
   }



   public function show($id){
    $livres = Livres::find($id);
    if(!$livres){
        return back()->with('error','Le livres est introuvable');
    }
    return view('edit',[
        'livres'=> $livres
    ]);
   }



   
   public function update(Request $request){

    $request->validate([
        'titre'=> 'required',
        'couverture'=> 'nullable|image|mimes:jpg,jpeg,png',
        'livres'=> 'nullable|mimes:pdf',
        'id'=> 'required',

    ],[
        'titre.required'=> 'Le nom du livres est requis !',
        'couverture.required'=> 'La photo de couverture est requis !',
        'couverture.image'=> 'La couverture doit etre de type image',
        'couverture.mimes'=> 'Le type de l image n est pas correct Ex : png,jpeg,jpg',
        'livres.required'=> 'Le livres est requis pour valider',
        'livres.mimes'=> 'Le livres est de type pdf',
    ]);

    $livres=Livres::find( $request->id );
    if(!$livres){
        return back()->with('error','Le livres est introuvable');
    }
    
    $livres->designation=$request->titre;
    if($request->hasFile('couverture')){

        $livres->couverture=$request->file('couverture')->store('livres','public');
    }
    if($request->hasFile('livres')){

        $livres->livres=$request->file('livres')->store('livres','public');
    }
    $livres->user_id=Auth::user()->id;
    $livres->save();
    return redirect()->route('dashboard')->with('success','Livre Mise à jour avec success !');
   }

   
   public function delete($id){
    $livres = Livres::find($id);
    if(!$livres){
        return back()->with('error','Le livres est introuvable');
    }
    $livres->delete();

    return back()->with('success','Livre supprimé avec success !');
   }

}
