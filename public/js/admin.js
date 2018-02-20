function cancelar(reservacion_id,status_cancelado) {
    $('#status_cancelado').val(status_cancelado);
    $('#reservation_id').val(reservacion_id);
    $('#motivo_cancelado').val('');

    $('#m_Rechazar').modal('show');
}

function guardarCancelado() {
    var status_cancelado= $('#status_cancelado').val();
    var reservation_id = $('#reservation_id').val();
    var motivo_cancelado = $('#motivo_cancelado').val();

    $.ajax({
        type:'GET',
        url:'/statusReservation/'+reservation_id+'/'+status_cancelado+'/'+motivo_cancelado,
        dataType: "json",
        success:function(data){
            location.reload(true);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function mostarMotivo(comentario) {
    if(comentario.length==0){
        alert('sin comentarios');
    }else{
        $('#m_MotivoCancelacion').modal('show');
        $('#motivo_cancelacion').html(comentario);
    }

    
}
/*------------VUE-JS-------------*/
var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        pendientes_id:[],
        pendientes_num_equipo:[],
        pendentes:[]
    },

    mounted: function () {
        Pusher.logToConsole = true;
        var pusher = new Pusher('350f997669c4bed22625', {
            cluster: 'us2',
            encrypted: true
        });
        this.getPendientes();
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            var message = data.message;
            var valores = data.data;
            //this.pendentes.push({id:valores.id,equipo:valores.equipo});
            $("#t_pendientes > tbody").empty();
            for(var i=0; i<valores.length; i++){
                $("#t_pendientes  > tbody")
                    .append($('<tr id="row">')
                        .append($('<td>')
                            .append(valores[i].equipo
                            )

                        )
                        .append($('<td>')
                            .append('<a href="/statusReservation'+valores[i].id+'/1" class="btn btn-primary">Aprobar</a>'+
                                '<a class="btn btn-danger" onclick="cancelar('+valores[i].id+',4)">Rechazar</a>'
                            )

                        )
                    );
            }



            $('#sucess').show();
            $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

        });
    },
    methods: {

        mostarMotivo: function(comentario){
            if(comentario.length==0){
                alert('sin comentarios');
            }else{
                $('#m_MotivoCancelacion').modal('show');
                $('#motivo_cancelacion').html(comentario);
            }
           
        },
        getPendientes:function () {
            this.$http.get('/getPendientes').then(function(response) {
                this.pendientes_id=response.data.id;
                this.pendientes_num_equipo=response.data.equipo;
                this.pendentes = response.data;
            }, function(error) {
                console.log(error);
            });

        },
        cancelar:function (reservacion_id,status_cancelado) {
            $('#status_cancelado').val(status_cancelado);
            $('#reservation_id').val(reservacion_id);
            $('#motivo_cancelado').val('');

            $('#m_Rechazar').modal('show');
        }

    }

})