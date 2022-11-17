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
    exampleRequest.open("POST", "controller/example.php"); //http://localhost/index.html
    exampleRequest.onreadystatechange = onExampleRequestUpdate; 
    exampleRequest.send(JSON.stringify(data)/*inputField.value*/);
}
function onExampleRequestUpdate(event) {
    if (exampleRequest.readyState < 4) {
        return;
    }
    console.log(JSON.parse(exampleRequest.responseText).output); //Response body
    console.log(exampleRequest.status); // Status Code
    console.log(exampleRequest.statusText); // Status defenition 
    result.innerText = JSON.parse(exampleRequest.responseText).output;
    document.body.appendChild(result);
}

var loadButton = document.getElementById("but");
loadButton.addEventListener("click", onLoadButtonPressed);
var one = document.getElementById("number-one");
var two = document.getElementById("number-two");
var three = document.getElementById("number-three");
var four = document.getElementById("number-four");
