<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">





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
            width: 25%;
        }

        caption {
            font-weight: bold;
            font-style: italic;
        }
    </style>
</head>

<body>
    <img style="position: relative; top:25px; height:125px"
        src="http://localhost:8080/img/logo.88337e3d.png">
    <div style="position:relative; left:210px; bottom: 65px; font-size: 25px">Asociación de Scouts del Guayas</div>
    <div style="position: relative; top:35px"><b>Reporte:</b> Dirigentes por grupo</div>
    <div style="position: relative; top:35px"><b>Fecha: </b>{{ $date }}</div>

    <div style="position:relative; top: 20px;" class="row">
        @foreach ($groups as $g)
            <h4>Grupo: {{ $g->name }}</h4>
            <table style="position: relative; ">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Unidad</th>
                        <th>Cargo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($g->units as $u)
                        @foreach ($u->directings as $d)
                            <tr>
                                <td>
                                    {{ $d->person->name }} {{ $d->person->last_name }}
                                </td>
                                <td>
                                    {{ $u->name }}
                                </td>
                                <td>
                                    {{ $d->person->user->roles[0]->nombre }}

                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</body>
</script>

</html>
