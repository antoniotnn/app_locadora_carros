<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected  $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg'
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
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG ou JPG',
            'nome.unique' => 'O nome da Marca já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }
}
