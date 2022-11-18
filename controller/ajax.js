var exampleRequest;
var result = document.createElement("p");
function onLoadButtonPressed(event) {
    var data = [
        {
            number: one.value
        },
        {
            number: two.value
        },
        {
            number: three.value
        },
        {
            number: four.value
        }
    ];
    exampleRequest = new XMLHttpRequest();
    //exampleRequest.open("GET" , "controller/example.php");
    exampleRequest.open("POST", "controller/example.php"/*?id=5 | /5*/); //http://localhost/index.html
    exampleRequest.onreadystatechange = onExampleRequestUpdate; 
    exampleRequest.send(JSON.stringify(data)/*inputField.value*/);
    //exampleRequest.send();
}
function onExampleRequestUpdate(event) {
    if (exampleRequest.readyState < 4) {
        return;
    }
    console.log(JSON.parse(exampleRequest.responseText).result); //Response body
    console.log(exampleRequest.status); // Status Code
    console.log(exampleRequest.statusText); // Status defenition 
    result.innerText = JSON.parse(exampleRequest.responseText).result;
    document.body.appendChild(result);
}

var loadButton = document.getElementById("but");
loadButton.addEventListener("click", onLoadButtonPressed);
var one = document.getElementById("number-one");
var two = document.getElementById("number-two");
var three = document.getElementById("number-three");
var four = document.getElementById("number-four");

//window.location.search.substring(1).split("&")[0].split("=")[1];
var searchParametrs = window.location.search;
if (searchParametrs) {
    searchParametrs = searchParametrs.substring(1);
    var allKeysValue = searchParametrs.split("&");
    for (var i = 0; i < allKeysValue.length; i++) {
        var keyValuePair = firstValue[i];
        var keyValue = keyValuePair.split("=");
        if (keyValue.length == 2) {
            var key = keyValue[0];
            var value = keyValue[1];
            if (key = "first_number") {
                one.value = value;
            }
        }
    }
}