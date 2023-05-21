// Connection Request 
function sendRequest(id) 
{
  var csrf_js_var = $('meta[name="csrf-token"]').attr('content')
  $('<form>', {
    "id": "add-connection",
    "html": '<input type="text" id="id" name="id" value="' + id + '" /><input name="_token" value="' +
      csrf_js_var + '" type="hidden">',
    "action": '/requests',
    'method': 'post'
  }).appendTo(document.body).submit();
}

// Accept Request
function acceptRequest(id) 
{
  $.ajax({
    url: '/requests/' + id,
    type: 'patch',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (res) {
      window.location.replace("/home?type=received");
    },
    error: function (textStatus, errorThrown) { 
      console.log("Request Status :", textStatus)
    }
  });
}

// Remove Connection Request
function removeConnection(id, type = null) 
{
  $.ajax({
    url: '/requests/' + id,
    type: 'delete',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (res) {
      if (type) {
        window.location.replace("/home?type=connections");
      } else {
        window.location.replace("/home?type=received");
      }
    },
    error: function (textStatus, errorThrown) { 
      console.log("Request Status :", textStatus)
    }
  });
}

