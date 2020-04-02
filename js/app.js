var rootURL = "http://localhost/AdamsTestEcommerce/adminPanel/products";

$(document).ready(function()
{
   findAll();
   document.getElementById('deleteButton').addEventListener("click", deleteGame);
   document.getElementById('updateButton').addEventListener("click", updateGame);
   document.getElementById('submitButton').addEventListener("click", addGame);

});


var findAll = function()
{
    console.log("findAll");
    $.ajax({
        type: 'GET',
        url: rootURL,
        dataType: "json",
        success: renderList
    });
};

var renderList = function(data)
{
    list = data.game;
    console.log("response");
    $.each(list, function(index, game)
    {
        $('#table_body').append('<tr><td>' + game.id + '</td><td>' + game.name + '</td><td>' + game.developer + '</td><td>' + game.platform + '</td><td>' + game.rating + '</td><td>' + game.yearReleased + '</td><td>' + game.noOfPlayers + '</td><td>'  + game.genre + '</td><td><button type="button" class="editButton" id = ' +game.id+' data-toggle="modal" data-target="#myModal" onclick = "findById(this.id)">Edit</button></td></tr>');
    });
    $('#table_id').DataTable();
    output='<div class = "row">';
    $.each(list, function(index, game)
    {
        var img = "images/" + game.image; 
        if(img === "images/null")
        {
            img = "images/notFound.png";
        }
        output += ('<div class = "col-sm-6 col-md-4 col-lg-3"><div class = "card"><img src = '+'"'+img+'"'+ 'height = "200" width = 90% class = "img"<p>ID: '+game.id+'</p><p>Name: '+game.name+'</p><p>Developer: '+game.developer+'</p><p>Platform: '+game.platform+'</p></div></div>');
    });
    output+='</div>';
    $('#productList').append(output);   
};

var findById = function(id)
{
    $.ajax({
        type: 'GET',
        url: rootURL + '/' + id,
        dataType: "json",
        success: function(data)
        {
            currentGame = data;
            renderGame(currentGame);   
        }
    });
};

var renderGame = function(game)
{
    $("#id").val(game.id);
    $("#name").val(game.name);
    $("#dev").val(game.developer);
    $("#platform").val(game.platform);
    $("#rating").val(game.rating);
    $("#released").val(game.yearReleased);
    $("#players").val(game.noOfPlayers);
    $("#genre").val(game.genre);
    $("#productImg").attr('src', 'images/' + game.image);
};

var formToJSON = function()
{
    console.log(rootURL + '/' + $('#id').val());
    var gameId = $('#id').val();
    return JSON.stringify(
            {
                "id": gameId == "" ? null : gameId,
                "name": $('#name').val(),
                "developer": $('#dev').val(),
                "platform": $('#platform').val(),
                "rating": $('#rating').val(),
                "yearReleased": $('#released').val(),
                "noOfPlayers": $('#players').val(),
                "genre": $('#genre').val(),
            });
};

var formToJSONAdd = function()
{
    console.log(rootURL + '/' + $('#id').val());
       
    //console.log(fileName);
    try
    {
        var gameId = $('#id').val();
        var fileName = null;
        var fileInput = document.getElementById('file-input');
        var fileName = fileInput.files[0].name; 
        return JSON.stringify(
        {   
            "id": gameId,
            "name": $('#newName').val(),
            "developer": $('#newDev').val(),
            "platform": $('#newPlatform').val(),
            "rating": $('#newRating').val(),
            "yearReleased": $('#newReleased').val(),
            "noOfPlayers": $('#newPlayers').val(),
            "genre": $('#newGenre').val(),
            "image": fileName 
        });
    }
    catch(Exception)
    {
        alert("All fields must be completed.");
    }
};

var addGame = function()
{
    console.log('addGame');
    $.ajax({
		type: 'POST',
		contentType: 'application/json',
		url: rootURL,
		dataType: "json",
		data: formToJSONAdd(),
		success: function(data, textStatus, jqXHR){
			alert('Game created successfully');
                        findAll();
                        location.reload(true);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('addGame error: ' + textStatus);
		}
	});
};

var updateGame= function () {
	console.log('updateGame');
	$.ajax({
		type: 'PUT',
		contentType: 'application/json',
		url: rootURL + '/' + $('#id').val(),
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Game updated successfully');
                        findAll();
                        location.reload(true);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('updateGame error: ' + textStatus);
		}
	});
        };

var deleteGame=function() {
	console.log('deleteGame');
	$.ajax({
		type: 'DELETE',
		url: rootURL + '/' + $('#id').val(),
		success: function(data, textStatus, jqXHR){
			alert('Game deleted successfully');
                        findAll();
                        console.log("Deleted");
                        location.reload(true);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('deleteGame error ' + textStatuss);
		}
	});
};