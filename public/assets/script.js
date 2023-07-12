
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

   
  // $(document).ready(function(){
  //   $('.carousel.carousel-slider').carousel();
  // });
  $('.carousel.carousel-slider').carousel({
    fullWidth: true,
    indicators: true,
    // height: 200,
  });
  autoplay()   
function autoplay() {
    $('.carousel').carousel('next');
    setTimeout(autoplay, 4500);
}
        
  

  