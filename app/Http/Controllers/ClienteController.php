<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{

      public function __construct(Cliente $cliente){

                $this->cliente = $cliente;

        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //http://127.0.0.1:8000/api/cliente?filtro=nome:LIKE:%gil%;numero:=:44
        if($request->has('filtro')){
             $filtros = explode(';',$request->filtro);
             //dd($condicoes);

             foreach($filtros as $key => $condicao){
                  $c = explode(':',$condicao);
                 $cliente = Cliente::where($c[0],$c[1],$c[2])->get();
                 return $cliente;
             }
                

        }else{
             $cliente = CLiente::all();
        return $cliente;
        }


       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
       
       $imagem = $request->file('imagem');
      $imagem_urn = $imagem->store('imagens','public');

        Cliente::create([
            'nome'=>$request->nome,
           'endereco'=>$request->endereco,
            'numero'=>$request->numero,
            'bairro'=>$request->bairro,
            'cidade'=>$request->cidade,
            'uf'=>$request->uf,
            'telefone'=>$request->telefone,
           'imagem' => $imagem_urn,




        ]);
       return ['msg'=>'Cliente inserido com sucesso'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente, Request $request)
    {
        $cliente = Cliente::find($cliente->id);
        return $cliente;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

  $cliente = $this->cliente->find($id);

        
       if($cliente === null)
        {
           return response()->json(['erro'=>'Não atualizamos, registro não encontrado!'],404);
        }
        else
        {
            $cliente->fill($request->all());
                //se a imagem foi enviada na requisicao
                if($request->file('imagem')){
                    Storage::disk('public')->delete($cliente->imagem);
                    $image = $request->file('imagem');
                    $imagem_urn = $image->store('imagens','public');
                    $cliente->imagem = $imagem_urn;

                }
                $cliente->save();
                 return $cliente;
             }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return ['msg'=>'O cliente foi removido com sucesso'];
    }
}
