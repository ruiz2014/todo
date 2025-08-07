<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tablas</title>

    <!-- <link rel="stylesheet" href="estilos.css"> -->
     <style>
        body {
            font-family: monospace;
        }

        /* .container {
            width: 100%;
            max-width: 1000px;
            margin: auto;
        } */

        .table_shop {
            width: 100%;
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            table-layout: fixed;
        }

        .table_shop caption {
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            margin: 8px 0px;
        }

        .table_shop tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
        }

        .table_shop th, .table_shop td {
            font-size: 16px;
            padding: 8px;
            text-align: center;
        }

        .table_shop thead th{ 
            text-transform: uppercase;
            background-color: #ddd;
        }

        .table_shop tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .table_shop tbody td:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }

        @media screen and (max-width: 600px) {
            .table_shop {
                border: 0px;
            }
            .table_shop caption {
                font-size: 22px;
            }
            .table_shop thead {
                display: none;
            }
            .table_shop tr {
                margin-bottom: 8px;
                border-bottom: 4px solid #ddd;
                display: block;
            }
            .table_shop th, .table_shop td {
                font-size: 12px;
            }
            .table_shop td {
                display: block;
                border-bottom: 1px solid #ddd;
                text-align: right;
            }
            .table_shop  td:last-child {
                border-bottom: 0px;
            }
            .table_shop td::before {
                content: attr(data-label);
                font-weight: bold;
                text-transform: uppercase;
                float: left;
            }
        }
     </style>
</head>
<body>
    <div class="container">
        <table class="table_shop">
            <caption>Consolas</caption>
            <thead>
                <tr>
                    <th>Consola</th>
                    <th>Precio</th>
                    <th>Ventas</th>
                    <th>Fecha de lanzamiento</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <tr>
                    <td data-label="Consola">Play Station 3</td>
                    <td data-label="Precio">$8000</td>
                    <td data-label="Ventas">800000</td>
                    <td data-label="Fecha de lanzamiento">10/01/2012</td>
                </tr>
                <tr>
                    <td data-label="Consola">Play Station 3</td>
                    <td data-label="Precio">$8000</td>
                    <td data-label="Ventas">800000</td>
                    <td data-label="Fecha de lanzamiento">10/01/2012</td>
                </tr>
                <tr>
                    <td data-label="Consola">Play Station 3</td>
                    <td data-label="Precio">$8000</td>
                    <td data-label="Ventas">800000</td>
                    <td data-label="Fecha de lanzamiento">10/01/2012</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>