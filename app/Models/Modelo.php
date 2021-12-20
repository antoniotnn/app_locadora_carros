<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected  $fillable = [ 'marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs' ];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5', // digitos entre 1 e 5 aceitos , incluindo eles
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean' //true, false, 1, 0, "1", "0"
        ];

        /*
            Importante sobre validação unique principalmente em apis, rest, com stateless, fazendo uma atualiza-
            cao com PUT. Ocorrerá um erro e o framework entenderá que estamos adicionando outro registro com mesmo nome.
            Para corrigir isso o unique tem 3 parâmetros:

            1) tabela onde será feita a pesquisa da existência unica do valor que estamos passando (marcas)
            2) (Parametro omitido), colocado após virgula: Nome da coluna que será pesquisada na tabela. (nome)
                Por padrão o nome da coluna na tabela pesquisada é a coluna que tem o mesmo nome do input 
                (quando omitido).
            3) id do registro que será desconsiderado na pesquisa

        */
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG, JPEG ou JPG',
            'nome.unique' => 'O nome do Modelo já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'numero_portas.digits_between' => 'O Campo aceita apenas valores de 1 a 5',
            'lugares.integer' => 'O Campo aceita apenas valores inteiros',
            'lugares.digits_between' => 'O Campo aceita apenas valores de 1 a 20',
            'air_bag.boolean' => 'O Campo aceita somente valores true, false, 1 ou 0',
            'abs.boolean' => 'O Campo aceita somente valores true, false, 1 ou 0' 
        ];
    }

    public function marca() {
        //um modelo pertence a UMA marca
        //return $this->belongsTo(Marca::class);
        return $this->belongsTo('App\Models\Marca');
    }
}
