
$(document).ready(function(){
    $('.sidenav').sidenav();
  });

  $(document).ready(function(){
    $('.collapsible').collapsible();
  });
  
  $(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();
  });
  $(document).ready(function(){
    $('.tooltipped').tooltip();
  });
  $(document).ready(function(){
    $('.materialboxed').materialbox();
  });
  $(document).ready(function(){
    $('.datepicker').datepicker({format: "yyyy-mm-dd ", autoClose: true,});
 });
 $('.dropdown-trigger').dropdown({
  coverTrigger: false,
  constrainWidth: false,
});
 
 $(document).ready(function(){
  $('.modal').modal();
});


//   autoplay()   
// function autoplay() {
//     $('.carousel').carousel('next');
//     setTimeout(autoplay, 4500);
// }

carousel()
  function carousel(){
    var caroItem = $('.mp-caro-item');
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i + 1;
        if(next == 3){
          var next = 0;
        }
      }
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
    setTimeout(carousel, 5000);
  }
  function next(){
    var caroItem = $('.mp-caro-item');
    var next = 0;
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i + 1;
        if(next == 3){
          var next = 0;
        }
      }
   
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
  }
  function prev(i){
    var caroItem = $('.mp-caro-item');
    var next = 0;
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i + 1;
        if(next == -1){
        var next = 2;
        }
      }
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
  }

  