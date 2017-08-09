// Save new location using Ajax
$("#btnSaveLocation").click(function(){

  var name = $('#name').val();
  var address = $('#address').val();
  var token = $('#locationToken').val();
  var url = $(this).attr("name");

  if(name.length < 3){
  
    alert("The name must be at least 3 characters.");
  
  }else{
  
    $.ajax({
      url: url,
      type: 'POST',
      data: {'name' : name, 'address': address,'_token': token},
      success: function(data)
      {
        console.log(data);
        addNewLocationToSelect(data);
      }
    });

  }
  return false;

});



function addNewLocationToSelect(data) {

    // Add new location to select location
    var select = document.getElementById("location");
    var option = document.createElement("option");
    option.value = data['id'];
    option.text = data['label'];
    option.selected = true;
    select.add(option);

    // Close modal and clear data
    $('#modalLocation').modal('toggle');
    $('#name').val('');
    $('#address').val('');
}