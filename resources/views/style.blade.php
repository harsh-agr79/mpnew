

@if($admin->mode == 'dark')
    <style>
        :root{
    --bg: rgb(31, 31, 31);
    --textcol: rgb(255, 255, 255);
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
}
td {
            font-size: 10px;
            padding: 2px;
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
    padding: 20px;
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
}

input:focus {
    border: 1px solid var(--bg);
    outline: none;
}

.bg{
    background-color: var(--bg) !important;
}


::-webkit-scrollbar {
    width: 15px;
}

/* Track */
::-webkit-scrollbar-track {
    /* box-shadow: inset 0 0 5px grey;  */
    background-color: var(--bgunder);
    
    /* border-radius: 10px; */
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: rgb(255, 145, 0);
    border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #ff7b00;
}
.textcol {
    color: var(--textcol) !important;
}
.mp-container{
    margin-right: 3vw;
    margin-left: 3vw;
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
}

</style>