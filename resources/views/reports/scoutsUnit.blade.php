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
    <div style="position: relative; top:5px"><b>Reporte:</b> Inscritos por unidad</div>
    <div style="position: relative; top:5px"><b>Grupo: </b> {{ $group->name }} </div>
    <div style="position: relative; top:5px"><b>Fecha: </b>{{ $date }}</div>

    <table style="position: relative; bottom:-75px">
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Cantidad de Inscritos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as  $u)
            <tr>
                <td>
                    {{ $u->type }}
                </td>
                <td>
                    {{ $u->size }}
                </td>
            </tr>    
            @endforeach
            
         
        </tbody>
    </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

</html>
