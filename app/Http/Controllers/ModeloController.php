<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return response()->json($this->modelo->all(), 200);
        /*
            ->all()  cria um OBJ de consulta e em seguida executando o método get(), retornando collection
            ->get()  permite modificar a consulta, retornando tb uma collection,
            então por isso o return abaixo não pode utilizar o ->all(), pois utiliza um ->with() que 
            modifica a consulta.
        */
        $modelos = array();

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            //$modelos = $this->modelo->selectRaw('id', 'nome', 'imagem')->get();
            $modelos = $this->modelo->selectRaw($atributos)->with('marca')->get(); // para o parametro marca_id nao vir null no endpoint ao passar na url o parametro, marca_id tem que ser passado no parametro da url
            //selectRaw aceita string unica separada por virgulas (para identificar as colunas)

            //'id', 'nome', 'imagem'   ---  usar select()
            //"id,nome,imagem" -- usar selectRaw()



            //dd($request->atributos);
            
        } else {
            $modelos = $this->modelo->with('marca')->get();
        }
        
        //return response()->json($this->modelo->with('marca')->get(), 200);
        return response()->json($modelos, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback()); //passar Accept application json no Header da requisição 
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        
        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
    
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id); //chamando o metodo marca de modelo, para trazer o relacionamento belongsTo
        if($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        //return $marca;
        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($modelo->rules() as $input => $regra) {
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição patch
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());
        } else {
            $request->validate($modelo->rules(), $modelo->feedback());
        }
        
        //remmove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        
        // para isso funcionar, necessário usar o POST  e passar no body da requisicao o parametro _method com value put ou patch
        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();
        /*
            $modelo->update([
                'marca_id' => $request->marca_id,
                'nome' => $request->nome,
                'imagem' => $imagem_urn,
                'numero_portas' => $request->numero_portas,
                'lugares' => $request->lugares,
                'air_bag' => $request->air_bag,
                'abs' => $request->abs
            ]);
        */
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);
        
        $modelo->delete();

        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
