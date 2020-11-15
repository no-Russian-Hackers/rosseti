//КОМПЬЮТЕРЫ
$( ".cl1" ).change(function() {
    //Имя компьютера
    let inscription = $("#exampleFormControlSelect1 option:selected").text();
    $('.dragDes').append('<div class="desktop" style = "text-allign:center;color: white; display:block;width:32px; height:42px; margin-bottom:40px;" ><img style=" bottom:0;width:32px; height:32px; display:block; margin:auto;" src="/rosseti/img/prakt/desktop.png";/>Desktop '+inscription+'</div>');
});

//КОММУТАТОРЫ
$( ".cl2" ).change(function() {
    //Имя компьютера
    let inscription = $("#exampleFormControlSelect2 option:selected").text();
    //alert(inscription);
    $('.dragSwitch')
.append('<div class="switch" style = "text-allign:center;color: white; display:block;width:150px; height:150px;" ><a id="new" style="bottom:0;width:105px; height:105px; display:block;" class = "ochko" href="#"><img style=" bottom:0;width:150px; height:150px; display:block; margin:auto;" src="/rosseti/img/prakt/network-hub.png";/></a>'+inscription+'</div>');
});

//СВЯЗИ
$( ".cl3" ).change(function() {
    //Имя компьютера
    let inscription = $("#exampleFormControlSelect3 option:selected").text();
    //alert(inscription);
    $('.drag')
.append('<div class="connectn" style = "text-allign:center;color: white; display:block;width:32px; height:42px; margin-bottom:40px;" ><img style=" bottom:0;width:32px; height:32px; display:block; margin:auto;" src="/rosseti/img/prakt/conct.png";/></div>');
});

//ПОДКЛЮЧЕНИЯ
$( ".cl4" ).change(function() {
    //Имя компьютера
    let inscription = $("#exampleFormControlSelect4 option:selected").text();
    //alert(inscription);
    $('.drag')
.append('<div class="connectn" style = "text-allign:center;color: white; display:block;width:32px; height:42px; margin-bottom:40px;" ><img style=" bottom:0;width:32px; height:32px; display:block; margin:auto;" src="/rosseti/img/prakt/desktop.png";/>'+inscription+'</div>');
});