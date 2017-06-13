import $ from 'jquery'
import pathToRegexp from './path-to-regex'

let initDynamic = function() {
  // Button send
  $(".sample-request-send")
    .off("click")
    .on("click", function(e) {
      e.preventDefault()
      let $root = $(this).parents("article")
      let group = $root.data("group")
      let name = $root.data("name")
      let version = $root.data("version")
      sendSampleRequest(group, name, version, $(this).data("sample-request-type"))
    })

  // Button clear
  $(".sample-request-clear")
    .off("click")
    .on("click", function(e) {
      e.preventDefault()
      let $root = $(this).parents("article")
      let group = $root.data("group")
      let name = $root.data("name")
      let version = $root.data("version")
      clearSampleRequest(group, name, version)
    })
} // initDynamic

function sendSampleRequest(group, name, version, type) {
  let $root = $('article[data-group="' + group + '"][data-name="' + name + '"][data-version="' + version + '"]')

  // Optional header
  let header = {}
  $root.find(".sample-request-header:checked").each(function(i, element) {
    let group = $(element).data("sample-request-header-group-id")
    $root.find("[data-sample-request-header-group=\"" + group + "\"]").each(function(i, element) {
      let key = $(element).data("sample-request-header-name")
      let value = element.value
      header[key] = $.type(value) === "string" ? escapeHtml(value) : value
    })
  })

  // create JSON dictionary of parameters
  let param = {}
  $root.find(".sample-request-param:checked").each(function(i, element) {
    let group = $(element).data("sample-request-param-group-id")
    $root.find("[data-sample-request-param-group=\"" + group + "\"]").each(function(i, element) {
      let key = $(element).data("sample-request-param-name")
      let value = element.value
      param[key] = $.type(value) === "string" ? escapeHtml(value) : value
    })
  })

  // grab user-inputted URL
  let url = $root.find(".sample-request-url").val()

  // Insert url parameter
  let pattern = pathToRegexp(url, null)
  let matches = pattern.exec(url)
  for (let i = 1; i < matches.length; i++) {
    let key = matches[i].substr(1)
    if (param[key] !== undefined) {
      url = url.replace(matches[i], encodeURIComponent(param[key]))

      // remove URL parameters from list
      delete param[key]
    }
  } // for

  // send AJAX request, catch success or error callback
  $.ajax({
    url: url,
    dataType: "json",
    contentType: "application/json",
    data: JSON.stringify(param),
    headers: header,
    type: type.toUpperCase(),
    success: displaySuccess,
    error: displayError
  })

  function displaySuccess(data) {
    let jsonResponse
    try {
      jsonResponse = JSON.stringify(data, null, 4)
    } catch (e) {
      jsonResponse = data
    }
    $root.find(".sample-request-response").fadeTo(250, 1)
    $root.find(".sample-request-response-json").html(jsonResponse)
    refreshScrollSpy()
  }

  function displayError(jqXHR, textStatus, error) {
    let message = "Error " + jqXHR.status + ": " + error
    let jsonResponse
    try {
      jsonResponse = JSON.parse(jqXHR.responseText)
      jsonResponse = JSON.stringify(jsonResponse, null, 4)
    } catch (e) {
      jsonResponse = jqXHR.responseText
    }

    if (jsonResponse) {
      message += "<br>" + jsonResponse
    }

    // flicker on previous error to make clear that there is a new response
    if ($root.find(".sample-request-response").is(":visible")) {
      $root.find(".sample-request-response").fadeTo(1, 0.1)
    }

    $root.find(".sample-request-response").fadeTo(250, 1)
    $root.find(".sample-request-response-json").html(message)
    refreshScrollSpy()
  }
}

function clearSampleRequest(group, name, version) {
  let $root = $('article[data-group="' + group + '"][data-name="' + name + '"][data-version="' + version + '"]')

  // hide sample response
  $root.find(".sample-request-response-json").html("")
  $root.find(".sample-request-response").hide()

  // reset value of parameters
  $root.find(".sample-request-param").each(function(i, element) {
    element.value = ""
  })

  // restore default URL
  let $urlElement = $root.find(".sample-request-url")
  $urlElement.val($urlElement.prop("defaultValue"))

  refreshScrollSpy()
}

function refreshScrollSpy() {
  $('[data-spy="scroll"]').each(function() {
    $(this).scrollspy("refresh")
  })
}

function escapeHtml(str) {
  let div = document.createElement("div")
  div.appendChild(document.createTextNode(str))
  return div.innerHTML
}

export default {
  initDynamic
}

