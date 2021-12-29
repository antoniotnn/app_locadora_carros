<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);
        
        if($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')) {
           $marcaRepository->filtro($request->filtro); 
        }

        if($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
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
        //$marca = Marca::create($request->all());
        //nome
        //imagem
        $request->validate($this->marca->rules(), $this->marca->feedback()); //parametrizar no header da requisição o Accept application/json , para que esse metodo validate não tente fazer um redirect pra rota anterior após informar erros de validacao
        
        //dd($request->nome);
        //dd($request->get('nome'));
        //dd($request->input('nome')); //somente para inputs do tipo text

        //dd($request->imagem);
        //dd($request->file('imagem'));
        $imagem = $request->file('imagem');
        
        //$image->store('imagens');//  , 'local'); // parâmetros: path, disco , o padrao é local, entao nesse caso o 2o parametro pode ser omitido
        // configurar disk em config/filesystems.php  (local, public ou amazon s3)
        /*
            disco local: storage/app 
            disco public: storage/app/public  (apesar desse nome, ele nao fica disponivel de forma publica), a nao ser com uma configuracao
        */ 
        $imagem_urn = $imagem->store('imagens', 'public');
        
        //dd($imagem_urn);   pathcompleto da imagem : imagens/9139uklansfdkl189yjafs.pnh
          
        //dd('Upload de arquivos');
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        /*
            OU
            $marca->nome = $request->nome;
            $marca->imagem = $imagem_urn;
            $marca->save();
        */

        //return $marca;
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id); //chamando o metodo modelos de marca, para trazer o relacionamento hasMany
        if($marca === null) {
            //return ['erro' => 'Recurso pesquisado não existe'];
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        //return $marca;
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*
        print_r($request->all()); //dados atualizados
        echo '<hr>';
        print_r($marca->getAttributes()); //dados antigos
        */
        //$marca->update($request->all());

        $marca = $this->marca->find($id);

        //dd($request->nome);   // Nos vrebos PUT E PATCH ao dar o dd os os parametros nao sao reconhecidos
                                // para resolver isso , usar o verbo POST pra atualizar, passando o parametro
                                // _method no body  e o valor put ou patch no valor.
        //dd($request->file('imagem'));

        //dd($marca);

        if($marca === null) {
            //return ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'];
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        //dd($request->method());

        if($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição patch
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }
        
        //remmove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        
        // para isso funcionar, necessário usar o POST  e passar no body da requisicao o parametro _method com value put ou patch
        //preencher o obj marca com os dados do request
        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        //dd($marca->getAttributes());
        $marca->save(); // método save consegue lidar tanto com inserção quanto update, desde q o id esteja no contexto.
        /*
            $marca->update([
                'nome' => $request->nome,
                'imagem' => $imagem_urn
            ]);
        */

        //return $marca;
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === null) {
            //return ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'];
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
            
        }

        Storage::disk('public')->delete($marca->imagem);
        
        $marca->delete();
        //return ['msg' => 'A marca foi removida com sucesso'];
        //return response()->noContent(); 204

        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
