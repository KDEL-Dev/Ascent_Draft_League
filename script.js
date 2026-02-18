//TEAMS

let teams = [];

function createTeams(num)
{
    teams = [];

    for(let i = 1; i <= num; i++)
    {
        teams.push({
            id:i,
            name: "Team "+ i,
            roster: []
        })
    }
}

createTeams(2);

//FETCH POKEMON FROM SHOWDOWN

async function getShowdownDex(){
    const response = await fetch('./showdownData/pokedex.json');
    const pokedex = await response.json();
    // console.log(pokedex)

    //empty arrays to seperate tiers
    let ouPokemon = [];
    let uuPokemon = [];
    let ruPokemon = [];
    let nuPokemon = [];

    
    for(let key in pokedex) // I wanted to use a foreach() loop but apparently this only works with arrays
        {
            let pkmn = pokedex[key];

            if(pkmn.tier === "OU")
            {
                ouPokemon.push(pkmn);
            }
            else if(pkmn.tier === "UU")
            {
                uuPokemon.push(pkmn);
            }
            else if(pkmn.tier === "RU")
            {
                ruPokemon.push(pkmn);
            }
            else if(pkmn.tier === "NU")
            {
                nuPokemon.push(pkmn);
            }

        }
    // console.log(ouPokemon); 
    // console.log(uuPokemon);

    displayOu(ouPokemon);
    displayUu(uuPokemon);
    displayRu(ruPokemon);
    displayNu(nuPokemon);
}



function displayOu(ouList)
{
    const ouPkmn = document.getElementById('listOfOuPkmn') //Grab the area you want to place list

    ouPkmn.innerHTML = "";

    for(let i=0; i < ouList.length; i++)
    {
        let li = document.createElement("li");
        li.textContent = ouList[i].name;

        li.addEventListener("click", function(){ //This is to make each pkmn clickable and it needs to be looped
            chooseTeam(ouList[i])
        })


        ouPkmn.appendChild(li);
    }
}

function displayUu(uuList)
{
    const uuPkmn = document.getElementById('listOfUuPkmn') //Grab the area you want to place list

    uuPkmn.innerHTML = "";

    for(let i=0; i < uuList.length; i++)
    {
        let li = document.createElement("li");
        li.textContent = uuList[i].name;
        uuPkmn.appendChild(li);
    }
}
    
function displayRu(ruList)
{
    const ruPkmn = document.getElementById('listOfRuPkmn') //Grab the area you want to place list

    ruPkmn.innerHTML = "";

    for(let i=0; i < ruList.length; i++)
    {
        let li = document.createElement("li");
        li.textContent = ruList[i].name;
        ruPkmn.appendChild(li);
    }
}

function displayNu(nuList)
{
    const nuPkmn = document.getElementById('listOfNuPkmn') //Grab the area you want to place list

    nuPkmn.innerHTML = "";

    for(let i=0; i < nuList.length; i++)
    {
        let li = document.createElement("li");
        li.textContent = nuList[i].name;
        nuPkmn.appendChild(li);
    }
}

getShowdownDex();

function chooseTeam(pkmn)
{
    let teamNumber = prompt("Draft wo which team? Enter team number:")

    teamNumber = parseInt(teamNumber);

    if(!isNaN(teamNumber) && teams[ teamNumber - 1])
    {
        teams[teamNumber - 1].roster.push(pkmn)

        console.log(teams)
    }
    else
    {
        alert('invalid team number');
    }
}

//redo below when not sick

async function chooseTeam(pkmn) {
    let teamNumber = prompt("Draft to which team? Enter team number:");
    teamNumber = parseInt(teamNumber);

    if (!isNaN(teamNumber) && teams[teamNumber - 1]) {
        const response = await fetch('draftPokemon.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                teamId: teams[teamNumber - 1].id,
                pokemonId: pkmn.id
            })
        });

        const result = await response.json();
        if (result.status === "success") alert(pkmn.name + " drafted!");
        else if (result.status === "already drafted") alert("Already drafted!");
        else alert("Error drafting!");
    }
}
