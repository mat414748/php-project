var someBeb = [
    {
        name: "jojo",
        surname: "jostar",
        age: 30,
        stand: [
            {
                name: "fire",
                power: "jopa"
            },
            {
                name: "water",
                power: "hopa"
            }
        ]
    },
    {
        name: "abdul",
        surname: "gip",
        age: 25
    }
];
someBeb.push({
    name: "Destoyer", 
    surname: "Legion", 
    age: 10000, 
    stand: [
        {
        name: "Anihilation",
        typ: "ALL WILL BURN"
        }
    ]
});

if ("stand" in someBeb[1]) {
    someBeb[1].stand.push({
        name: "Earth",
        power: "Earth wall"
    })
}

someBeb[2].stand.pop();

someBeb[0].stand.splice(0, 1); //remove in first array in stand von erste 0 element nur 1 object
/*
someBeb.name = "Jonaton";
someBeb.stand = "Star platinum";
console.log(someBeb.stand);
*/