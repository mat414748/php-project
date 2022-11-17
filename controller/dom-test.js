/*
var pageHeader = document.getElementById("page-header");
pageHeader.innerText = "Toxic swamp";
pageHeader.style.color = "red";
pageHeader.style.fontSize = "10px";
pageHeader.style.display = "none";

var secondHeader = document.createElement("h2");
secondHeader.innerText = "KORONa";
document.body.appendChild(secondHeader);
var jasosBiba = document.getElementById("jasos");
secondHeader.className = "header-background";
document.body.insertBefore(secondHeader, jasosBiba);
*/

////////////////////////////////
var people = [
    {
        person: [
            {
                name: "Jorjo",
                age: 20
            }
        ]
    },
    {
        person: [
            {
                name: "Buchilati",
                age: 40
            }
        ]      
    },
    {
        person: [
            {
                name: "Jorno",
                age: 21
            }
        ]      
    }
];

var personTable = document.createElement("table");
var tableLine = document.createElement("tr");

var tableCell = document.createElement("td"); //th
var bigBob = document.createElement("b");
bigBob.innerText = "Name";
tableCell.appendChild(bigBob);
tableLine.appendChild(tableCell);

var tableCell = document.createElement("td");
var bigBob = document.createElement("b");
bigBob.innerText = "Age";
tableCell.appendChild(bigBob);
tableLine.appendChild(tableCell)

personTable.appendChild(tableLine);

for (i = 0; i < people.length; i++) {
    var tableLine = document.createElement("tr");
    for (j = 0; j < people[i].person.length ; j++) {
        var tableCell = document.createElement("td");
        tableCell.innerText = people[i].person[j].name;
        tableLine.appendChild(tableCell);
        var tableCell = document.createElement("td");
        tableCell.innerText = people[i].person[j].age;
        tableLine.appendChild(tableCell);
    }
    personTable.appendChild(tableLine);
}
personTable.setAttribute('border','2');
document.body.appendChild(personTable);

//personTable.removeChild(tableLine);
//button.desabled = true
//console.log(pageHeader.innerText);
//console.log(pageHeader.id);