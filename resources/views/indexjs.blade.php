<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Paginação</title>
        <meta name="csrf-token" content="{{csrf_token()}}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <style>
        body {
            padding: 20px;
        }
    </style>
    </head>
    <body>
        <div class="container">
            <div class="card text-center">
                <div class="card-header">
                    Tabela de Clientes
                </div>
                <div class="card-body">
                    <h5 class="card-title" id="cardTitle"></h5>
                    <table class="table table-hover" id="tabelaClientes">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Sobrenome</th>
                            <th scope="col">E-mail</th>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Marcel</td>
                                    <td>Teodoro</td>
                                    <td>marcel@gmail.com</td>
                                </tr>
                            
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                    <nav id="paginator">
                        <ul class="pagination">
                          {{-- <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">1</a></li>
                          <li class="page-item active">
                            <a class="page-link" href="#">2</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                          <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                          </li> --}}



                        </ul>
                      </nav>

                </div>
            </div>
        </div>
    <script src="{{asset('js/app.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        function getItemProximo(data) {
            i = data.current_page + 1;
            if ( data.last_page == data.current_page)
                s = '<li class="page-item disabled">';
            else
                s = '<li class="page-item">';
                s += '<a class="page-link" ' + ' pagina="' + i + '" href="javascript:void(0);">Próximo</a></li>';
            return s;    
        }

        function getItemAnterior(data) {
            i = data.current_page - 1;
            if ( 1 == data.current_page)
                s = '<li class="page-item disabled">';
            else
                s = '<li class="page-item">';
                s += '<a class="page-link" ' + ' pagina="' + i + '" href="javascript:void(0);">Anterior</a></li>';
            return s;    
        }

        function getItem(data, i) {
            if ( i == data.current_page)
                s = '<li class="page-item active">';
            else
                s = '<li class="page-item">';
                s += '<a class="page-link" ' + ' pagina="' + i + '" href="javascript:void(0);"   >'+ i +'</a></li>';
            return s;    
        }

        function montarPaginator (data) {
            $("#paginator>ul>li").remove();
            $("#paginator>ul").append(getItemAnterior(data));

            n=10;
            if(data.current_page - n/2 <= 1)
                inicio = 1;
            else if (data.last_page - data.current_page < n)
                inicio = data.last_page -n +1;
            else
                inicio = data.current_page - n/2;

            fim = inicio + n - 1;
            for(i=inicio;i<=fim;i++) {
                s = getItem(data, i);
                $("#paginator>ul").append(s);
            }
            $("#paginator>ul").append(getItemProximo(data));
        }

        function montarLinha(cliente) { 
            return '<tr>' +
            '<td>' + cliente.id + '</td>' +
            '<td>' + cliente.nome + '</td>' +
            '<td>' + cliente.sobrenome + '</td>' +
            '<td>' + cliente.email + '</td>' +
            '</tr>';    
        }

        function montarTabela(data) {
            $("#tabelaClientes>tbody>tr").remove();
            for (i=0;i<data.data.length;i++) {
                s = montarLinha(data.data[i]);
                $("#tabelaClientes>tbody").append(s);
            }
        }

        function carregarClientes(pagina) {
            $.get('/json', {page: pagina}, function(resp) {
                montarTabela(resp);
                montarPaginator(resp);
                $("#paginator>ul>li>a").click(function() { 
                    carregarClientes($(this).attr('pagina')
                ); 
                });
                $("#cardTitle").html(

                    "Exibindo " + resp.per_page 
                    +" clientes de " + resp.total + " (" 
                    + resp.from + " a " 
                    + resp.to + ")"
                );
            });
        }

        $(function(){
            carregarClientes(1);
        });
    </script>
    </body>
</html>
