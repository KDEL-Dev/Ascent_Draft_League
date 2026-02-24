document.addEventListener("DOMContentLoaded", function(){
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
        let teamNumber = prompt("Draft to which team? Enter team number:")

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
    //BELOW WON'T WORK I THINK

    async function chooseTeam(pkmn) {
        // Pick a team (you can replace prompt with clickable buttons later)
        let teamNumber = prompt("Draft to which team? Enter team number:");
        teamNumber = parseInt(teamNumber);

        if (!isNaN(teamNumber) && teams[teamNumber - 1]) {
            const response = await fetch('api/draftPkmn.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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

    //Randomize Draft Order


    document.addEventListener("DOMContentLoaded", async function () {
    const draftOrderList = document.getElementById("draftOrderList");
    const randomizeBtn = document.getElementById("randomizeBtn");

    // Function to render a list of gamerTags
    function renderDraftOrder(list) {
        draftOrderList.innerHTML = "";
        list.forEach(gamerTag => {
            const li = document.createElement("li");
            li.textContent = gamerTag;
            draftOrderList.appendChild(li);
        });
    }

    // Fetch current draft order on page load
    async function loadDraftOrder() {
        try {
            const response = await fetch("api/get_current_draft.php");
            const data = await response.json();
            if (data.error) {
                console.error("Draft error:", data.error);
            } else {
                renderDraftOrder(data);
            }
        } catch (err) {
            console.error("Failed to load draft order:", err);
        }
    }

    // Randomize draft order button
    randomizeBtn.addEventListener("click", async function () 
    {
        try 
        {
            const response = await fetch("api/randomize_draft.php");
            const shuffledList = await response.json();
            if (shuffledList.error) {
                console.error("Draft error:", shuffledList.error);
                return;
            }
            renderDraftOrder(shuffledList);
            console.log("Draft picks saved to DB and displayed:", shuffledList);
        } 
        catch (err) 
        {
            console.error("Failed to randomize draft:", err);
        }
    });

    // Load current draft order when page loads
        await loadDraftOrder();
    });


})
