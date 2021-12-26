<template>

  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <!-- Início do Card de Busca -->
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
                <!-- Fim do card de busca -->

                <!-- Inicio do card de listagem de marcas -->
                <div class="card">
                    <div class="card-header">Relação de Marcas</div>

                    <div class="card-body">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            </tr>
                            <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            </tr>
                            <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-sm" style="float: right;">Adicionar</button>
                    </div>
                </div>
                <!-- Fim do Card de listagem de marcas -->

            </div>
        </div>
    </div>

</template>

<script>


</script>