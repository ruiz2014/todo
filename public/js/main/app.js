window.addEventListener("DOMContentLoaded", function(){

        const host = window.location.origin;
        
        const term = document.getElementById('term');
        const box = document.getElementById('box-search');
        const clear_btn = document.getElementById('clean');

        /**se pueden borrar**/
        const customer_doc = document.getElementById('customer_doc');
        const customer_id = document.getElementById('customer_id');
        const payMethod = document.getElementById('payMethod');
        const new_pay = document.getElementById('new_pay');

        const simple = document.querySelectorAll('#new_pay input[type=radio]');
        const simple_number = document.querySelectorAll('#new_pay input[type=number]');
        const detailed = document.querySelectorAll('#payMethod li input[type=checkbox]');
        const detailed_number = document.querySelectorAll('#payMethod li input[type=number]');
        const type_payment = document.getElementById('type_payment');

        // const valor = customer_doc?.id;
        
        clear_btn.addEventListener("click", clean)
        // let radioPay = null;

        if(type_payment){
            type_payment.selectedIndex = 0;
        }
        
        function clean(){
                term.value=''; 
                customer_id.value=''
                // customer_doc.value = '';
                box.innerHTML = "";
                box.style.height = '0px'
                term.onmousedown = function()
                {
                    return true;
                }
        }

        term.addEventListener('click', function(e){
            const path = `/tool/search?customer=`;
            let url_main = `${host}${path}`;
                // alert(url_main);
            if(box.childElementCount > 0){
                box.innerHTML = "";
                box.style.height = '0px'
                return
            }
            box.style.height = '150px'
            search(e, url_main )
        })

        term.addEventListener("keyup", (e)=>{
            const path = `/tool/search?customer=${term.value}`;
            let url_main = `${host}${path}`;
            search(e, url_main)
        })

        function search(e, url){
            
            fetch(url,{
                method: "get",
                headers: { 
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                box.innerHTML = "";
                data.forEach(p =>{
                    box.innerHTML += `<li data-document=${p.document} data-search=${p.id}> ${p.name} </li>`;
                })
                
                box.addEventListener("click", function(li) {
                    // li.target.style.color = "blue";
                    // alert(li.target.dataset.search);
                    term.value = li.target.innerHTML
                    customer_id.value = li.target.dataset.search
                    box.innerHTML = "";
                    box.style.height = '0px'
                    customer_doc.value = li.target.dataset.document
                    // term.disabled=true
                    // term.onselectstart = function() {
                    //     return true;
                    // };
                    if(e.type == 'click'){
                        console.log(e.type)
                        term.onmousedown = function()
                        {
                            return false;
                        }
                    }
                    
                }, false);
            })
        }

        new_pay.addEventListener('change', function(ev){
            // alert("hola");
            // console.log(ev.target.checked, simple_number, ev.target.id)
            let number_id = ev.target.id.slice(-1);
            // console.log(number_id);
            if(ev.target.checked){
                radioPay = ev.target.id;
                if(ev.target.id === 'radioPay_detail'){
                    atAttributeForm(2, simple, simple_number, detailed, detailed_number, number_id);
                    payMethod.style.display = "block";
                }else{
                    cleanPayMethodCheck(detailed, detailed_number);
                    atAttributeForm(1, simple, simple_number, detailed, detailed_number, number_id);
                } 
            }
        }) 

        function cleanPayMethodCheck(detailed, detailed_number){

            detailed.forEach(function(checkElement) {
                checkElement.checked = false;
            });
            detailed_number.forEach(function(checkElement) {
                checkElement.value = false;
            });
                           
            payMethod.style.display = "none";
        }

        function cleanPayMethodRadio(simple, simple_number){
            // console.log(simple)
            simple.forEach(function(radioElement) {
                radioElement.checked = false; 
            })
            
            simple_number.forEach(function(radioElement) {
                radioElement.value = false;
            });

            new_pay.style.display = "none";               
        }

        function atAttributeForm(opt, simple, simple_number, detailed, detailed_number, number_id){
            total =  document.getElementById('total').innerHTML;
            if(opt == 1){
                simple.forEach(function(radioElement) {
                    radioElement.setAttribute('form', 'form_sale'); 
                })
                
                simple_number.forEach(function(radioElement) {
                    if(radioElement.id == `radioMethod${number_id}`){
                        radioElement.value = parseFloat(total);
                    }
                    else{
                        radioElement.value = null;
                    }
                    radioElement.setAttribute('form', 'form_sale'); 
                });

                detailed.forEach(function(checkElement) {
                    checkElement.removeAttribute('form');
                });
                detailed_number.forEach(function(checkElement) {
                    checkElement.removeAttribute('form');
                });
            }else{
                simple.forEach(function(radioElement) {
                    radioElement.removeAttribute('form'); 
                })
                
                simple_number.forEach(function(radioElement) {
                    radioElement.value = null;
                    radioElement.removeAttribute('form'); 
                });

                detailed.forEach(function(checkElement) {
                    checkElement.setAttribute('form', 'form_sale');
                });
                detailed_number.forEach(function(checkElement) {
                    checkElement.setAttribute('form', 'form_sale');
                });
            }
        }

        if(type_payment){
            
             type_payment.addEventListener('change', function(){
                let type_pay = type_payment.value;
                alert(type_pay);
                if(type_payment.value == 2){
                    cleanPayMethodCheck(detailed, detailed_number);
                    cleanPayMethodRadio(simple, simple_number);
                        
                    return 0;
                }
                new_pay.style.display = "block";
            })
        }

        const checkboxs = document.querySelectorAll('input[type="checkbox"]');
        checkboxs.forEach(function(checkbox) {
                checkbox.checked = false;
        });

})