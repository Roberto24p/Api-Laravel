<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">



    <title>Document</title>
    <style>
        table {
            width: 100%;
            border: 1px solid #999;
            text-align: left;
            border-collapse: collapse;
            margin: 0 0 1em 0;
            caption-side: top;
        }

        caption,
        td,
        th {
            padding: 0.3em;
            text-align: center
        }

        th,
        td {
            border-bottom: 1px solid #999;
            width: 5%;
        }

        caption {
            font-weight: bold;
            font-style: italic;
        }

        .state {
            background-color: rgb(102, 174, 102);
            display: inline-block;
            border-radius: 10px;
            padding: 5px;
            margin-top: 10px
        }
    </style>
</head>

<body>
    <img style="position: relative; top:25px; height:125px"
        src="http://localhost:8080/img/logo.88337e3d.png">
    <div style="position:relative; left:210px; bottom: 65px; font-size: 25px">Asociación de Scouts del Guayas</div>
    <div style="position: relative; top:5px"><b>Reporte:</b> Plan de Adelanto</div>
    <div style="position: relative; top:5px"><b>Nombres: </b> {{ $scout->name }} {{ $scout->last_name }}</div>
    <div style="position: relative; top:5px"><b>Cédula: </b> {{ $scout->dni }} </div>
    <div style="position: relative; top:5px; text-transform: capitalize"><b>Unidad: </b> {{ $scout->scout->type }}
    </div>

    <div style="position: relative; top:5px"><b>Fecha: </b>{{ $date }}</div>

    <div style="position:relative; top: 20px;" class="row">
        @foreach ($advancePlan as $adv)
            @php
                $aux = false;
            @endphp
            <h4>Reconocimiento: {{ $adv->name_recognition }}</h4>
            <table style="position: relative; ">
                <thead>
                    <tr>
                        <th>Tema</th>
                        <th>Estado</th>
                        <th>Fecha de Obtención</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adv->topics as $topic)
                        @php
                            if ($topic->status) {
                                $aux = true;
                            }
                        @endphp
                        <tr>

                            <td>
                                {{ $topic->name }}
                            </td>
                            <td>
                                @if ($topic->status)
                                    <p class="state">
                                        Completado
                                    </p>
                                @elseif($aux == true)
                                    <p class="state" style="background-color: rgb(246, 210, 109)">
                                        En proceso
                                    </p>
                                @else
                                    <p class="state" style="background-color: rgb(143, 125, 246)">
                                        No iniciado
                                    </p>
                                @endif
                            </td>
                            <td>
                                @if (isset($topic->dateGet))
                                    <p>{{ $topic->dateGet }}</p>
                                @else
                                    -------
                                @endif
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        @endforeach
    </div>
</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
    integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous">
</script>
</script>

</html>
