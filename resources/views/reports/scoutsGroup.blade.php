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
    <img style="position: relative; top:25px; height:125px" src="https://scontent.fgye1-1.fna.fbcdn.net/v/t1.6435-9/104264215_1375296942662790_2626700348287070741_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_eui2=AeG-fXYIpoC6dOwecteGSKH9890omTthj3Pz3SiZO2GPcweK1PzgZHKWpIFSNgq3m_O3faSVjaX1RH3J9KNcUTRq&_nc_ohc=fDdjdc8oUeYAX_Ltv4Q&_nc_ht=scontent.fgye1-1.fna&oh=00_AT8AeI-3nbuqiuFaUIxEBNUB0b2RyqQ3xQqAjZlBSAxHGw&oe=6339A185">
    <div style="position:relative; left:210px; bottom: 65px; font-size: 25px">Asociación de Scouts del Guayas</div>
    <div style="position: relative; top:35px"><b>Reporte:</b> Beneficiarios scouts inscritos por grupo</div>
    <div style="position: relative; top:35px"><b>Fecha: </b>{{ $date}}</div>
    <div style="position: relative; top:35px"><b>Descripción: </b>{{ $period->description}}</div>
    <div style="position: relative; top:35px"><b>Inicio de periodo: </b>{{ $period->date_start}}</div>
    <div style="position: relative; top:35px"><b>Fin de periodo: </b>{{ $period->date_end}}</div>

    <table style="position: relative; bottom:-75px">
        <thead>
            <tr>
                <th>Nombre de Grupo</th>
                <th>Cantidad de Inscritos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as  $g)
            <tr>
             
                <td>
                    {{ $g->name }}
                </td>
                <td>
                    {{ $g->size }}
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
