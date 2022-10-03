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
    <div style="position: relative; top:5px"><b>Reporte:</b> Dirigentes por Grupo</div>
    <div style="position: relative; top:5px"><b>Fecha: </b>{{ $date }}</div>

    <div style="position:relative; top: 20px;" class="row">
        <table style="position: relative; ">
            <thead>
                <tr>
                    <th>Grupo</th>
                    <th>Cantidad de Dirigentes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>
                            {{ $group->name }}
                        </td>
                        <td>
                            {{ $group->size }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</script>
</script>

</html>
