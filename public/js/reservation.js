
$(function () {

});

function mostarMotivo(comentario) {
    $('#m_MotivoCancelacion').modal('show');
    $('#motivo_cancelacion').html(comentario);

    
}


/**
 * obttiene toda la lista de horas disponible por el dia (lunes,martes,miercoles..etc)
 * @param fecha
 */
function getHours(fecha) {
    var elements = '';

    $.ajax({
        type:'GET',
        url:'/getHours/'+fecha,
        dataType: "json",
        success:function(data){

            var total = data.total;
            if(total>0){
                for (var i=0; i<data.horario.length; i++){
                    $('#hora_inicial').append($('<option>', {
                        value: data.horario[i],
                        text : data.horario[i]
                    }));
                    /*$('#hora_final').append($('<option>', {
                        value: data.horario[i],
                        text : data.horario[i]
                    }));*/
                }

            }

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function getEquipos() {
    var hora_inicial=$('#hora_inicial').val();
    var hora_final=$('#hora_final').val();
    var fecha_reservacion=$('#fecha_reservacion').val();

    console.log('test');

    //this.getStyles();
    this.getStyles().then(function() {

        if (hora_final!=null){
            $.ajax({
                type:'GET',
                url:'/getEquipos/'+fecha_reservacion+'/'+hora_inicial+'/'+hora_final,
                dataType: "json",
                success:function(data){
                    var disponibles = data.disponibles;
                    var ocupados = data.ocupados;
                    var css_equipment = data.css_equipment;
                    var class_selected = data.class_selected;
                    disponibles.forEach(function(key) {

                        $('#'+ css_equipment[key]+'').removeClass('st4');

                        $('#'+key+'').addClass('cursor');
                        $( "#"+key+'' ).click(function() {
                            var fecha = $('#fecha_reservacion').val();
                            $('#key_css').val(key);

                            var totalcss = css_equipment[key].length;
                            var cssocupado = css_equipment[key][totalcss-1];

                            if(fecha==""){
                                alert('La fecha es obligatoria');
                            }else{
                                getStyleByKey(key,cssocupado);
                                getDisponible(key)
                            }

                        });

                    }, this);

                    for(var cont_ocupados=0; cont_ocupados<ocupados.length; cont_ocupados++){
                        $('#'+ocupados[cont_ocupados]+'').addClass('st4');
                        var totalcss = css_equipment[ocupados[cont_ocupados]].length;
                        var cssocupado = css_equipment[ocupados[cont_ocupados]];
                        $('#'+cssocupado+'').addClass('st4');
                    }



                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }
            });
        }
    });




}
/* 
    Estilos
    st4 = deshabilitado
    st3 = disponible principal
    st2 = disponible hijo
    st5 = seleccionado principal
    st6 = seleccionado hijo

*/

function getStyles() {
    return new Promise(function(resolver, cancelar) {
        var elements = '';
        //console.log(fecha);
        $.ajax({
            type:'GET',
            url:'/getStyles/',
            dataType: "json",
            success:function(data){
                data.keys.forEach(function(key) {
                    //console.log('removerclase'+key);
                    $('#'+key+'').removeClass('st4');
                }, this);
                data.button_circle.forEach(function(key_circle) {

                    $('#'+key_circle+'').removeClass('st4');
                }, this);
                resolver();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });

    });


}

function getStyleByKey(key_css,cssocupado) {
    var elements = '';

    $.ajax({
        type:'GET',
        url:'/getStyleByKey/'+key_css,
        dataType: "json",
        success:function(data){
            var key_selected = data.key_selected;
            var class_selected = data.class_selected;
            var key_css = data.key_css;
            var clase = data.clase;
            $('#'+cssocupado+'').addClass('st12');

            for (var i = 0; i < clase.length; i++) {
                var child_class = clase[i];
                $('#'+child_class[0]+'').removeClass('st5');
                for (var j = 1; j < child_class.length; j++) {
                    // console.log(child_class[j]);
                    $('#'+child_class[j]+'').removeClass('st6');
                }
            }
            try{
                $('#'+class_selected[0]+'').addClass('st5');
            }catch(e){

            }


            for (var cont_class_selected = 1; cont_class_selected < class_selected.length; cont_class_selected++) {
                $('#'+class_selected[cont_class_selected]+'').addClass('st6');

            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function getDisponible(key_css) {
    var fecha = $('#fecha_reservacion').val();
    $.ajax({
        type:'GET',
        url:'/getDisponible/'+fecha+'/'+key_css,
        dataType: "json",
        success:function(data){
            var total = data.total;
            //var lista_horarios = '';
            for (var key in data.horario) {

                $('#hora_inicial').append($('<option>', {
                    value: data.horario[key],
                    text : data.horario[key]
                }));
                /*lista_horarios+='&nbsp;<span class="label label-default"> '+data.horario[key]+'</span>&nbsp;';*/


            }
            //$('#lista_horarios').html(lista_horarios);

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function getHourFinal(hora_final) {
    var fecha = $('#fecha_reservacion').val();
    var key_css = $('#key_css').val();
    $('#hora_final')
        .empty()
    ;
    $.ajax({
        type:'GET',
        url:'/getHourFinal/'+fecha+'/'+hora_final,
        dataType: "json",
        success:function(data){
            $('#hora_final').append($('<option>', {
                value: 0,
                text : '-- Horas -- '
            }));

            for (var key in data.horario) {


                $('#hora_final').append($('<option>', {
                    value: data.horario[key],
                    text : data.horario[key]
                }));
            }


        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function reservar() {
    var hora_final = $('#hora_final').val();
    if(hora_final==null){
        alert('favor de seleccionar una fecha , equipo y un rango de horas');
    }else{
        $( "#frm_reservar" ).submit();
    }
}