<template>

  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de Marcas</div>

                    <div class="card-body">
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
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm" style="float: right;">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>


</script>