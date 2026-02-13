// fetch("https://pokeapi.co/api/v2/pokemon/yungoos")
//     .then(response => response.json())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));



//This grabs the whole list of pokemon from the JSON
// async function getCurrentMeta(){
//     let response = await fetch('https://play.pokemonshowdown.com/data/pokedex.json');
//     let result = await response.json();

//     console.log(result)
// }

// getCurrentMeta()



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

