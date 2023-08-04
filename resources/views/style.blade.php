
@php

if(session()->has('ADMIN_LOGIN')){
    $u = $admin;
}
elseif(session()->has('USER_LOGIN')){
    $u = $user;
}
@endphp
@if($u->mode == 'dark')
    <style>
        :root{
    --bg: rgb(31, 31, 31);
    --textcol: rgb(214, 214, 214);
    --bgunder: #0e0e0e;
    --bg-content: rgb(31, 31, 31);
    --td-hover: rgb(40, 40, 40);
}
*{
    color: white;
}
body{
    background: #0e0e0e;
}
.topnv{
    background: var(--bg) !important;
}
.bgunder{
    background: var(--bgunder) !important;
}

    </style>
@else
<style>
    :root{
    --bg: rgb(255, 195, 66);
    --textcol: rgb(0, 0, 0);
    --bg-content: white;
    --td-hover: rgb(236, 236, 236);
}

</style>
@endif
<style>
    @import url('https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

body {
    font-family: 'Exo', 'sans-serif';
    /* overflow-x: hidden; */
}
#loading {
  width: 100%;
  height: 100vh;
  position: fixed;
  background: white;
  z-index: 1000;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
}
td{
            font-size: 11px;
            padding: 2px;
        }
th{
    font-size: 10px;
    padding: 4px;
}

tr:hover {
    background: var(--td-hover);
    cursor: pointer;
}
tr{
    user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -khtml-user-select: none;
}

.login-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    max-width: 500px;
    box-shadow: 4px 4px 15px rgba(90, 89, 89, 0.747);
}
.bg-content{
    background: var(--bg-content) !important;
}
.mp-card{
    padding: 5px;
    background: var(--bg-content);
    border-radius: 10px;
}

span.field-icon {
    float: right;
    position: absolute;
    right: 20px;
    top: 10px;
    cursor: pointer;
    z-index: 2;
}

.inp {
    padding: 12px;
    width: 100%;
    border: 1px solid rgb(173, 173, 173);
    border-radius: 5px;
    color: black;
}

input:focus {
    border: 1px solid var(--bg);
    outline: none;
}

.bg{
    background-color: var(--bg) !important;
}


::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

/* Track */
::-webkit-scrollbar-track {
    /* box-shadow: inset 0 0 5px grey;  */
    background-color: var(--bgunder);
    
    /* border-radius: 10px; */
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #ffa545;
    border-radius: 10px;
}

/* Handle on hover */
/* ::-webkit-scrollbar-thumb:hover {
    background: #ffa545;
} */
.textcol {
    color: var(--textcol) !important;
}
.mp-container{
    margin-right: 2vw;
    margin-left: 2vw;
}
.rmenu{
    /* background: var(--bg-content); */
    /* border-radius: 5px; */
    width: 100px;
    position: absolute;
    padding: 0;
    margin: 0;
    /* box-shadow: 1px 1px 5px rgba(128, 128, 128, 0.168); */
    display: none;
}
.rmenu ul{
    border-radius: 10px;
    box-shadow: 1px 1px 7px rgba(128, 128, 128, 0.456);
    background: var(--bg-content);
    /* border: 0.5px solid var(--textcol) */
}
.rmenu li {
    /* background: var(--bg-content); */
    text-align: center;
    padding: 5px;
    font-size: 15px;
    cursor: pointer;
    color: var(--textcol);
    /* border-bottom: 1px solid var(--textcol); */
    /* border-top: 1px solid var(--textcol); */
}
/* .rmenu li:hover{
    background: var(--td-hover);
} */
.border-top{
    border-top: 1px solid var(--textcol);
}
.selectinp{
    padding: 10px;
    border-radius: 10px;
    color: black;
}
.selectinp option{
    color: black;
}
.collapsible-header{
    padding: 10px;
    margin: 0;
    font-size: 13px;
    background: var(--bg-content);
}
.collapsible, .collapsible li{
    border: none;
    box-shadow: none;
}
.mp-chart .google-visualization-tooltip text{
    fill: #000000 !important;
}
.mp-chart text {
    fill: var(--textcol) !important;
}
.bar rect{
    fill: var(--bg-content);
}
.bar text{
    fill: var(--textcol);
}
#linechart_material rect:nth-child(16){
                fill: rgba(255, 0, 0, 0);
            }
/* #linechart_material text{
                fill: var(--textcol)
            } */

.search{
    outline: none;
    border: none;
    background: rgb(255, 195, 66);
}
.search:focus{
    border:none; 
    background: white;
}
.multiple-select-dropdown span{
    color: black !important;
}
.prod-img{
    height: 10vh;
}
.prod-title{
    font-size: 15px;
    font-weight: 600;
}
.prod-det{
    font-size: 12px;
    padding: 10px;
    font-weight: 500;
}
.price-line{
   position: relative;
   top: 7px;
}
.prod-price{
    padding: 3px 10px;
    background: rgb(255, 195, 66);
    border-radius: 5px;
    color: black;
}
.prod-inp{
    color: black;
    outline: none;
    padding: 5px;
    border-radius: 5px;
    outline: none;
    border: 1px solid rgb(170, 170, 170);
    width: 10vh; 
}
.prod-container::-webkit-scrollbar{
    display: none;
  }
.prod-container{
    margin-left: 20vw;
    margin-right: 20vw;
    height: 65vh; 
    overflow-y: scroll; 
    margin-top: 10px;
}
@media screen and (max-width: 720px){
    .prod-container{
        margin: 0;
    }
}
.home-btn{
    /* width: 200px !important; */
    background: rgb(255, 195, 66);
    color: black;
    border-radius: 10px; 
    padding: 15px 20px; 
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.home-btn i{
    margin-left: 3vw; 
    color: black !important;
}
.home-btn:hover{
    background: rgb(255, 178, 12)
}
.spc{
    transform: scale(1.07);
}
.mp-caro-item{
    height: 30vh;
    width: 100%;
}
.scroll-text{
  display:flex;
  flex-wrap: nowrap;
  white-space: nowrap;
  min-width: 100%;
  overflow: hidden; 
}
.news-message{
  display : flex;
  flex-shrink: 0;
  height: 30px;
  align-items: center;
  animation: slide-left 15s linear infinite;
}
.news-message p{
    font-size: 1.5em;
    font-weight: 600;
    padding-left: 1em;
    color: var(--textcol);
  }
  @keyframes slide-left {
  from {
    -webkit-transform: translateX(0);
            transform: translateX(0);
  }
  to {
    -webkit-transform: translateX(-100%);
            transform: translateX(-100%);
  }
}

.bottom-sheet{
    max-height: 80% !important;
    border: none !important;
    outline: none !important;
    border-radius: 10px !important;
}
.edpr_img{
    height: 150px;
    width: 150px;
    border-radius: 50%; 
}
.nav-dp{
    height: 45px;
    /* margin: 7px 7px 0 7px; */
}
.bal-popup{
    position: fixed;
    z-index: 1005;
    top: 30%;
    right: 10%;
    left: 10%;
}
.overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    /* background: #000000; */
    opacity: .6;
    filter: grayscale(100%);
    z-index: 0;
    overflow: hidden;
}
</style>