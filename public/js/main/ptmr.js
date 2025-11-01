window.addEventListener("DOMContentLoaded", function(){
    const add = document.getElementById('btn-add');
    const af = document.getElementById('amount_form');
    const product_id = document.getElementById('product_id');
    const tb_data = document.getElementById('tbody');

    let idSelect = null;
    let textSelect = null;
    let priceSelect = null;
    let productos = new Array();
    showResponse(temp_result, 'joder');
    $('#product_id').change( function(){
        idSelect = $(this).val(); // window.modifyAmount = (id, price, op)=>{

    //     let amount = $("#amount_"+id).text();
    //     if(op == 'add')
    //         amount ++;
    //     else
    //         amount --;

    //     if(amount < 1){
    //         resul = ++amount;
    //         $("#amount_"+id).text(resul.toFixed(2));
    //         Swal.fire({
    //             icon: "error",
    //             title: "Oops...",
    //             text: "No se puede disminuir cantidad",
    //         });
    //         return 0;
    //     }

    //     var data = { id: id, amount: amount }; 
    //     console.log(data)
    //     fetch(`${url_modify}`, {
    //         method: "POST",
    //         headers: { 
    //             'Content-Type': 'application/json',
    //             "X-CSRF-Token": document.querySelector('input[name=_token]').value
    //         },
    //         body: JSON.stringify(data)
    //     })
    //     .then(response => response.json()) 
    //     .then(datos => {
    //         console.log(datos)
    //         if(datos.ok){
    //             $("#amount_"+id).text(amount.toFixed(2));
    //             $("#operation_"+id).html(price * amount)
    //             tax(price * 1, op)
    //         }else{
    //             alert("no se pudo realizar el cambio de cantidad")
    //             resul = (op === 'add' ? --amount : ++amount);
    //             $("#amount_"+id).text(resul.toFixed(2));
    //         }
    //     });   
    // }
        // alert(idSelect)
        textSelect = $(this).find('option:selected').text();
        let getPrice = textSelect.split(' ').reverse();
        priceSelect  = getPrice[0];
        $('#amount_form').val(1);
                // console.log(priceSelect);
    })

    

    add.addEventListener('click', ()=>{
        alert(format);
        let qty  = af.value
        qty = 1;

        if(!$.isNumeric(idSelect) || !$.isNumeric(qty)){
            Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Debe seleccionar un producto primero o la cantidad",
            });
            return 0;
        }

        tb_data.innerHTML=''
        let producto = {code:code.value, id:idSelect, name:textSelect, amount:qty, price:priceSelect}
        productos.push(producto) 
        var data = { order: producto, format: format };
        tb_data.innerHTML = '';
        let body = ''

        fetch(`${url_add}`, {
                    method: "POST",
                    headers: { 
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(data)
        })
                .then(response => response.json())
                .then(datos => {
                    console.log(datos)

                    if(datos.ok){
                        console.log(datos)
                        showResponse(datos['orders'], 'new');
                        // $('#send-kitchen').prop('disabled', false);
                    }else{
                        console.log(datos)
                    }
        });

        idSelect = null;
        textSelect = null;
        priceSelect = null;
        $('#product_id').val("");
        $('#product_id').change();
        $('#amount_form').val("");

        alert(url_add)
        alert(af.value)
    })

    window.eliminarFila = (id) => {
        var data = { id: id, format: format };
        let body = ''
        tb_data.innerHTML = '';
        fetch(`${url_delete}`, {
            method: "POST",
            headers: { 
                'Content-Type': 'application/json',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(datos => {
            console.log(datos)
            if(datos.ok){
                if(datos['orders'].length === 0){
                    tax(0, 'nothy')
                    return 0
                }
                showResponse(datos['orders'], 'delete');
            }else{
                console.log(datos)
            }
        });
            alert("eliminar");
    }

    // window.modifyAmount = (id, price, op)=>{

    //     let amount = $("#amount_"+id).text();
    //     if(op == 'add')
    //         amount ++;
    //     else
    //         amount --;

    //     if(amount < 1){
    //         resul = ++amount;
    //         $("#amount_"+id).text(resul.toFixed(2));
    //         Swal.fire({
    //             icon: "error",
    //             title: "Oops...",
    //             text: "No se puede disminuir cantidad",
    //         });
    //         return 0;
    //     }

    //     var data = { id: id, amount: amount }; 
    //     console.log(data)
    //     fetch(`${url_modify}`, {
    //         method: "POST",
    //         headers: { 
    //             'Content-Type': 'application/json',
    //             "X-CSRF-Token": document.querySelector('input[name=_token]').value
    //         },
    //         body: JSON.stringify(data)
    //     })
    //     .then(response => response.json()) 
    //     .then(datos => {
    //         console.log(datos)
    //         if(datos.ok){
    //             $("#amount_"+id).text(amount.toFixed(2));
    //             $("#operation_"+id).html(price * amount)
    //             tax(price * 1, op)
    //         }else{
    //             alert("no se pudo realizar el cambio de cantidad")
    //             resul = (op === 'add' ? --amount : ++amount);
    //             $("#amount_"+id).text(resul.toFixed(2));
    //         }
    //     });   
    // }

     window.modifyAmount = async (id, price, op)=>{
        alert(format);
                const addBtn = document.getElementById(`btn_add_${id}`);
                const subBtn = document.getElementById(`btn_sub_${id}`);
                // console.log(`btn_add_${id}`, subBtn);
                addBtn.disabled = true; // Deshabilita el botón
                subBtn.disabled = true; // Deshabilita el botón
        

                let amount = $("#amount_"+id).text();
                console.log(amount)
                if(op == 'add')
                    amount ++;
                else
                    amount --;

                if(amount < 1){
                    resul = ++amount;
                    $("#amount_"+id).text(resul.toFixed(2));
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No se puede disminuir cantidad",
                    });
                    return 0;
                }

                var data = { id: id, amount: amount, format: format }; 
                // console.log(data)
                
                try {
                    response = await fetch(`${url_modify}`, {
                        method: "POST",
                        headers: { 
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": document.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify(data)
                    })

                    const dataJson = await response.json();
                    console.log(dataJson)

                    if(dataJson.ok){
                            $("#amount_"+id).text(amount.toFixed(2));
                            $("#operation_"+id).html(price * amount)
                            tax(price * 1, op)
                    }else{
                            alert("no se pudo realizar el cambio de cantidad")
                            resul = (op === 'add' ? --amount : ++amount);
                            $("#amount_"+id).text(resul.toFixed(2));
                    }

                } catch (error) {
                    console.error('Error al obtener los datos:', error);
                } finally {
                    addBtn.disabled = false; // Deshabilita el botón
                    subBtn.disabled = false; 
                }
                // .then(response => response.json()) 
                // .then(datos => {
                //     console.log(datos)
                //     if(datos.ok){
                //         $("#amount_"+id).text(amount.toFixed(2));
                //         $("#operation_"+id).html(price * amount)
                //         tax(price * 1, op)
                //     }else{
                //         alert("no se pudo realizar el cambio de cantidad")
                //         resul = (op === 'add' ? --amount : ++amount);
                //         $("#amount_"+id).text(resul.toFixed(2));
                //     }
                // });   
            }

    function showResponse(data, op){
                let body = ''
                total = 0;
                // alert("este problema");
                data.forEach( i =>{
                    console.log(i) 
                    tax(i.price * i.amount, op);
                    body += `<tr id="temp_${i.temp_id}">
                            <td data-label="Producto">
                                ${i.name}
                            </td>
                            <td data-label="Cantidad" class="td-amount">
                                <button class="btn btn-outline-secondary btn-amount" id="btn_add_${i.id}" onclick="modifyAmount(${i.id}, ${i.price}, 'add')" style="position:relative;top:2px;"><ion-icon name="add-outline"></ion-icon></button>
                                    <span id="amount_${i.id}">${i.amount}</span>
                                <button class="btn btn-outline-secondary btn-amount" id="btn_sub_${i.id}" onclick="modifyAmount(${i.id}, ${i.price}, 'sub')" style="position:relative;top:2px;"> <ion-icon name="remove-outline"></ion-icon> </button>
                            </td>
                            <td data-label="Precio uni."> 
                                ${i.price}
                            </td>        
                            <td data-label="Total" id="operation_${i.id}">
                            ${i.price * i.amount}
                            </td>
                            <td>
                                <div class="btn-group d-block">
                                    <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFila(${i.id})"><ion-icon name="trash-outline" style="position:relative;top:3px;left:0px;"></ion-icon></button>  
                                </div>
                            </td>
                        </tr>`; 
                })
                $('#tbody').append(body)  
            }

            function tax(value, op){
                // let total = 0;
                switch (op) {
                    case "nothy":
                        total = 0;
                        break;
                    case "add":
                        total += value; 
                        break;
                    case "sub":
                        total -= value; 
                        break;    
                    default:
                        total += value; 
                }
                igv = total * 0.18;
                subtotal = total - igv;
                $('#total').html(total.toFixed(2))
                $('#subtotal').html(subtotal.toFixed(2))
                $('#igv').html(igv.toFixed(2))
            }
})