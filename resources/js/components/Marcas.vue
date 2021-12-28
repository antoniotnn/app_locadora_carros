<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Início do Card de Busca -->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                        <div class="col mb-3">
                            <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da Marca">
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                            </input-container-component>                          
                        </div>
                        <div class="col mb-3">
                            <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o Nome da Marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca">
                            </input-container-component>
                        </div>
                    </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm" style="float: right;">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Fim do card de busca -->

                <!-- Inicio do card de listagem de marcas -->
                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component 
                        :dados="marcas"
                        :titulos="['ID', 'Nome', 'Imagem']"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm" style="float: right;" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim do Card de listagem de marcas -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o Nome da Marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                    </input-container-component>
                    {{ nomeMarca }}
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato .png">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma Imagem" @change="carregarImagem($event)">
                    </input-container-component>
                    {{ arquivoImagem }}
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
    </div>

</template>

<script>

    export default {
        computed: {
            token() {
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=');
                });
                
                if (token) {
                    token = token.split('=')[1];
                    token = 'Bearer ' + token;
                    return token;
                }
                return null;
            }
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: []
            }
        },
        methods: {
            carregarLista() {

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.get(this.urlBase, config)
                    .then(response => {
                        this.marcas = response.data;
                        //console.log(this.marcas);
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            carregarImagem(event) {
                this.arquivoImagem = event.target.files;
            },
            salvar() {
               
                let formData = new FormData();
                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: '+ response.data.id
                        };
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro';
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        };
                    });
            }
        },
        mounted() {
            this.carregarLista();
        }

    }

</script>