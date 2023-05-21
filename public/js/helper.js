var pageNo = 1;
var hasMore = true;
var page_numbers = [];
var have_more = [];

function loadMore(type, user_id) 
{
  if (hasMore) 
  {
    $('#skeleton_' + type).removeClass('d-none');
    $.ajax({
      url: '/requests',
      type: 'get',
      data: {"page": pageNo + 1, "type": type },
      success: function (res) 
      {
        $('#skeleton_' + type).addClass('d-none');

        var html = '';
        if (res.data.length) 
        {
          $.each(res.data, function (key, val) 
          {
            if (type == 'sent') 
            {
              html = html + '<div id="' + type + '_' + val.id +
                '" class="d-flex justify-content-between mt-1"><table class="ms-1"><td class="align-middle">' +
                val.receiver[0].name +
                '</td><td class="align-middle"> - </td><td class="align-middle">' +
                val.receiver[0].email +
                '</td></table><div><button id="cancel_request_btn_" class="btn btn-danger me-1" onclick=removeConnection(' +
                val.id + ')>Withdraw Request</button></div></div>';
            }
            if (type == 'received') 
            {
              html = html +
                '<div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">' +
                val.name +
                '</td><td class="align-middle"> - </td><td class="align-middle">' +
                val.email +
                '</td></table><div><button id="accept_request_btn_" class="btn btn-primary me-1" onclick="">Accept</button></div></div>';
            }
            if (type == 'suggestions') 
            {
              html = html +
                '<div class="d-flex justify-content-between mt-1"><table class="ms-1"><td class="align-middle">' +
                val.name +
                '</td><td class="align-middle"> - </td><td class="align-middle">' +
                val.email + '</td></table><div><button onclick=sendRequest(' +
                val.id +
                ') id="create_request_btn_" class="btn btn-primary me-1">Connect</button></div></div>';
            }
            if (type == 'connections') 
            {
              if (user_id != val.sender[0].id) 
              {
                html = html +
                  '<div class="d-flex justify-content-between mt-1"><table class="ms-1"><td class="align-middle">' +
                  val.sender[0].name +
                  '</td><td class="align-middle"> - </td><td class="align-middle">' +
                  val.sender[0].email +
                  '</td></table><div><div><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common (' +
                  val.commonConnections.data.length +
                  ')</button> <button id="create_request_btn_" onclick=removeConnection(' +
                  val.id +
                  ',"connection") class="btn btn-danger me-1">Remove Connection</button></div></div></div>';
              } 
              else 
              {
                html = html +
                  '<div class="d-flex justify-content-between mt-1"><table class="ms-1"><td class="align-middle">' +
                  val.receiver[0].name +
                  '</td><td class="align-middle"> - </td><td class="align-middle">' +
                  val.receiver[0].email +
                  '</td></table><div><div><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common (' +
                  val.commonConnections.data.length +
                  ')</button> <button id="create_request_btn_" onclick=removeConnection(' +
                  val.id +
                  ',"connection") class="btn btn-danger me-1">Remove Connection</button></div></div></div>';
              }
            }
          });
        }
        $('#' + type).append(html);
        pageNo = pageNo + 1;
        if (res.last_page == pageNo) 
        {
          hasMore = false;
          $('#load_more_btn_parent_' + type).addClass('d-none');
        }
      },
      error: function (textStatus, errorThrown) 
      {
        $('#skeleton_' + type).addClass('d-none');
      }
    });
  }
}

function loadMoreCommon(user_id, id) 
{
  if (page_numbers[id] == undefined) 
  {
    page_numbers[id] = 1;
    have_more[id] = true;
  }
  if (have_more[id]) 
  {
    $('#connections_in_common_skeletons_' + id).removeClass('d-none');
    $.ajax({
      url: '/requests',
      type: 'get',
      data: {"page": page_numbers[id] + 1, "user_id": user_id, "type": 'common-connections'},
      success: function (res) 
      {
        $('#connections_in_common_skeletons_' + id).addClass('d-none');
        var e = '';
        if (res.data.length) 
        {
          $.each(res.data, function (key, val) {
            e = e + '<div class="p-2 shadow rounded mt-2  text-white bg-dark">' +
              val.name + ' - ' + val.email + '</div>';
          });
        }
        $('#content_' + id).append(e);
        page_numbers[id] = page_numbers[id] + 1;
        if (res.last_page == page_numbers[id]) 
        {
          have_more[id] = false;
          $('#load_more_' + id).addClass('d-none');
        }
      },
      error: function (textStatus, errorThrown) 
      {
        $('#connections_in_common_skeletons_' + id).addClass('d-none');
      }
    });
  }
}
