<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{valor}}</span>
                        <!--<span v-if="titulos[chaveValor].tipo == 'data'">{{'...'+valor}}</span>-->
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{valor | formatDate }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/'+valor" width="30px;" height="30px;">
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="visualizar.dataBsToggle" :data-bs-target="visualizar.dataBsTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="atualizar.dataBsToggle" :data-bs-target="atualizar.dataBsTarget" @click="setStore(obj)">Atualizar</button>
                        <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remover.dataBsToggle" :data-bs-target="remover.dataBsTarget" @click="setStore(obj)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    
    export default {
        props: ['dados', 'titulos', 'atualizar', 'visualizar', 'remover'],
        methods: {
            setStore(obj) {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                this.$store.state.transacao.dados = '';
                this.$store.state.item = obj;
            }
        },
        computed: {
            dadosFiltrados() {

                let campos = Object.keys(this.titulos);
                let dadosFiltrados = [];
                
                this.dados.map((item, chave) => { 
                    
                    let itemFiltrado = {};

                    campos.forEach(campo => {

                        itemFiltrado[campo] = item[campo]; //utilizar a sintaxe de array para atribuir valores a objetos
                        
                    });
                    dadosFiltrados.push(itemFiltrado);
                });
                
                return dadosFiltrados; //retorne um array de objetos
            }
        }
    }
</script>
