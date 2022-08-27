$(document).ready(function (){
    $('.delete').on('click', function (e) {
        if(!confirm('Удалить запись?')){
            e.preventDefault();
        } else
        {
            e.stopPropagation();
        }
    });
})