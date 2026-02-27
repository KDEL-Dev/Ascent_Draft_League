document.addEventListener("DOMContentLoaded", async function () {

    // -------------------
    // TEAMS
    // -------------------
    let teams = [];

    function createTeams(num) {
        teams = [];
        for (let i = 1; i <= num; i++) {
            teams.push({ id: i, name: "Team " + i, roster: [] });
        }
    }

    createTeams(2);

    // -------------------
    // POKEMON
    // -------------------
    async function getShowdownDex() {
        const response = await fetch('./showdownData/pokedex.json');
        const pokedex = await response.json();

        let ouPokemon = [], uuPokemon = [], ruPokemon = [], nuPokemon = [];

        for (let key in pokedex) {
            let pkmn = pokedex[key];
            if (pkmn.tier === "OU") ouPokemon.push(pkmn);
            else if (pkmn.tier === "UU") uuPokemon.push(pkmn);
            else if (pkmn.tier === "RU") ruPokemon.push(pkmn);
            else if (pkmn.tier === "NU") nuPokemon.push(pkmn);
        }

        displayOu(ouPokemon);
        displayUu(uuPokemon);
        displayRu(ruPokemon);
        displayNu(nuPokemon);
    }

    function displayList(list, elementId, clickable=false) {
        const container = document.getElementById(elementId);
        container.innerHTML = "";
        list.forEach(item => {
            const li = document.createElement("li");
            li.textContent = item.name;

            if (clickable) {
                li.addEventListener("click", () => chooseTeam(item));
            }

            container.appendChild(li);
        });
    }

    function displayOu(list) { displayList(list, 'listOfOuPkmn', true); }
    function displayUu(list) { displayList(list, 'listOfUuPkmn'); }
    function displayRu(list) { displayList(list, 'listOfRuPkmn'); }
    function displayNu(list) { displayList(list, 'listOfNuPkmn'); }

    getShowdownDex();

    // -------------------
    // DRAFTING FUNCTION
    // -------------------
    async function chooseTeam(pkmn) 
    {
        let teamNumber = prompt("Draft to which team? Enter team number:");
        teamNumber = parseInt(teamNumber);

        if (!isNaN(teamNumber) && teams[teamNumber - 1]) {
            // Update local array
            teams[teamNumber - 1].roster.push(pkmn);
            console.log(teams);

            // Optional: send to server
            /*
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
            */
        } else {
            alert('Invalid team number');
        }
    }

    // -------------------
    // DRAFT ORDER DISPLAY
    // -------------------
    const draftOrderList = document.getElementById("draftOrderList");

    function renderDraftOrder(list) {
        draftOrderList.innerHTML = "";
        list.forEach(gamerTag => {
            const li = document.createElement("li");
            li.textContent = gamerTag;
            draftOrderList.appendChild(li);
        });
    }

    async function loadDraftOrder() {
        try 
        {
            const response = await fetch("api/get_draft_order.php");
            const data = await response.json();
            if (data.error) console.error("Draft error:", data.error);
            else renderDraftOrder(data);
            // console.log("Draft order loaded:", data);
        } 
        catch (err) 
        {
            console.error("Failed to load draft order:", err);
        }
    }

    await loadDraftOrder();

});

// ----------------
// DRAFT RANDOMIZER
// ----------------

