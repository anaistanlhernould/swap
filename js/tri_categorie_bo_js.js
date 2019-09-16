$(document).ready(function(){
    $('#list_categories').change(function(event){
        ajax(); 
    }); 

    function ajax()
    {
        var titre = $('#list_categories').val(); 
        console.log(titre); 

        var parameters = "titre="+titre; 
        console.log(parameters); 

        $.post("../assets/ajax/tri_categorie_bo_ajax.php", parameters, function(data){
            $('#resultat').html(data.resultat); 
        },'json'); 
    }
}); 

