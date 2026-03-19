document.addEventListener("DOMContentLoaded", async () => {

    // -------------------
    // LOAD POKÉMON BY TIER
    // -------------------
    async function getShowdownDexFromDB() {
        try {
            const response = await fetch('/ascent_draft_league/api/get_pkmn_by_tier.php');
            const pokedex = await response.json();

            let ouPokemon = [], uuPokemon = [], ruPokemon = [], nuPokemon = [];

            pokedex.forEach(pkmn => {
                switch (pkmn.tier) {
                    case "OU": ouPokemon.push(pkmn); break;
                    case "UUBL": ouPokemon.push(pkmn); break;

                    case "UU": uuPokemon.push(pkmn); break;
                    case "RUBL": uuPokemon.push(pkmn); break;

                    case "RU": ruPokemon.push(pkmn); break;
                    case "NUBL": ruPokemon.push(pkmn); break;

                    case "NU": nuPokemon.push(pkmn); break;
                    case "PUBL": nuPokemon.push(pkmn); break;
                }
            });

            displayOu(ouPokemon);
            
            displayUu(uuPokemon);
            displayRu(ruPokemon);
            displayNu(nuPokemon);
        } 
        catch (err) 
        {
            console.error("Failed to load Pokémon from DB:", err);
        }
    }

    function displayList(list, elementId, clickable = false) {
    const container = document.getElementById(elementId);
    if (!container) return;
    container.innerHTML = "";

    list.forEach(item => {
        const li = document.createElement("li");
        li.textContent = item.name;

        // Highlight if drafted
        if (item.drafted) {
            li.classList.add("drafted");
            li.style.pointerEvents = 'none'; // cannot click already drafted
        } else if (clickable) {
            li.addEventListener("click", () => {
                console.log("Clicked Pokémon:", item);
                draftPokemon(item);
                li.classList.add("myPick"); // mark your own pick
            });
        }

        container.appendChild(li);
    });
}

    function displayOu(list) { displayList(list, 'listOfOuPkmn', true); }
    function displayUu(list) { displayList(list, 'listOfUuPkmn', true); }
    function displayRu(list) { displayList(list, 'listOfRuPkmn', true); }
    function displayNu(list) { displayList(list, 'listOfNuPkmn', true); }

    await getShowdownDexFromDB();

    // -------------------
    // DRAFT ORDER DISPLAY
    // -------------------
    const draftOrderList = document.getElementById("draftOrderList");
    async function loadDraftOrder() {
        try {
            const response = await fetch("/ascent_draft_league/api/draft/get_draft_order.php");
            const data = await response.json();
            renderDraftOrder(data);
        } catch (err) {
            console.error("Failed to load draft order:", err);
        }
    }

    function renderDraftOrder(list) {
        if (!draftOrderList) return;
        draftOrderList.innerHTML = "";
        list.forEach(gamerTag => {
            const li = document.createElement("li");
            li.textContent = gamerTag;
            draftOrderList.appendChild(li);
        });
    }

    // -------------------
    // DRAFT STATE
    // -------------------
    let draftInterval = null;

    async function loadDraftState() {
    try {
        const response = await fetch('/ascent_draft_league/api/draft_auto_update.php');
        const data = await response.json();

        const currentPickEl = document.getElementById("currentPickInfo");
        const previousPickEl = document.getElementById("previousPickInfo");

        if (currentPickEl) currentPickEl.textContent = data.current_player ?? "Waiting...";
        if (previousPickEl) previousPickEl.textContent = data.previous_pick ?? "-";

        const myGamerTag = document.body.dataset.gamertag;
        const MAX_POKEMON_PER_USER = data.maxPokemon ?? 6;

        // Check if user can draft: it's their turn AND they haven't reached the max
        const canDraft = (myGamerTag === data.current_player) && (data.myDraftedCount < MAX_POKEMON_PER_USER);

        toggleDraftButtons(canDraft);
    } catch (err) {
        console.error("Failed to load draft state:", err);
    }
}

    function toggleDraftButtons(enable) {
        const lists = ['listOfOuPkmn', 'listOfUuPkmn', 'listOfRuPkmn', 'listOfNuPkmn'];
        lists.forEach(id => {
            const container = document.getElementById(id);
            if (!container) return;
            container.querySelectorAll('li').forEach(li => {
                li.style.pointerEvents = enable ? 'auto' : 'none';
                li.style.opacity = enable ? '1' : '0.5';
            });
        });
    }

    


    // -------------------
    // DRAFT POKÉMON
    // -------------------
    async function draftPokemon(pkmn) {
    if (!confirm(`Draft ${pkmn.name}?`)) return;

    try {
        const response = await fetch("/ascent_draft_league/api/draft/draft_pkmn.php", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ showdown_pkmn: pkmn.id })
        });

        const data = await response.json();

        if (data.status === "success") {
            alert(`${pkmn.name} drafted successfully!`);

            // Add drafted class to the clicked li
            const liElements = document.querySelectorAll("li");
            liElements.forEach(li => {
                if (li.textContent === pkmn.name) {
                    li.classList.add("drafted");
                }
            });

            await getShowdownDexFromDB(); // Refresh UI
        } else {
            alert(data.error || "Draft failed");
        }
    } catch (err) {
        console.error("Draft error:", err);
    }
}

    // -------------------
    // RANDOMIZE DRAFT ORDER
    // -------------------
    const randomizeBtn = document.getElementById("randomizeBtn");
    if (randomizeBtn) {
        randomizeBtn.addEventListener("click", async () => {
            if (!confirm("Randomize draft order?")) return;

            try {
                const response = await fetch("/ascent_draft_league/api/randomize_draft.php");
                const data = await response.json();

                if (data.error) {
                    alert("Randomization failed: " + data.error);
                    return;
                }

                renderDraftOrder(data);
                alert("Draft order randomized!");
            } 
            catch (err) 
            {
                console.error("Randomization error:", err);
            }
        });
    }

    // -------------------
    // START DRAFT BUTTON
    // -------------------
    const startDraftBtn = document.getElementById('startDraftBtn');
    if (startDraftBtn) {
        startDraftBtn.addEventListener("click", () => {
            if (draftInterval) clearInterval(draftInterval);
            loadDraftState(); // initial load
            draftInterval = setInterval(loadDraftState, 2000); // start polling every 2s
            startDraftBtn.disabled = true; // prevent double start
        });
    }

    // Load draft order immediately
    await loadDraftOrder();

    // -------------------
    // CLEAR DRAFT BUTTON
    // -------------------

    const clearDraftBtn = document.getElementById('clearDraftBtn');
if (clearDraftBtn) {
    clearDraftBtn.addEventListener("click", async () => {
        if(!confirm("Are you sure you want to reset the draft?")) return;

        try {
           const response = await fetch('api/draft/clear_draft.php', { method: 'POST' });
           const data = await response.json();

           if(!response.ok) {
                throw new Error(data.error || "failed to reset draft");
           }

           alert("Draft reset successfully.");
           location.reload();
        } catch (error) {
            console.error("Draft reset error", error);
            alert("Error resetting draft")
        }   
    });
}


/****************************************************************
                        DRAFT RECAP
*****************************************************************/


const table = document.getElementById("recapTable")

function displayDraftResults()
{

    fetch('/ascent_draft_league/api/draft/get_draft_result.php')
    .then(response => response.json())
    .then(data => {
        data.forEach(pick => {
            const tr = document.createElement("tr");

            const tdPick = document.createElement("td");
            tdPick.textContent = pick.pick_number;

            const tdName = document.createElement("td");
            tdName.textContent = pick.name;

            const tdTier = document.createElement("td");
            tdTier.textContent = pick.tier;

            if(pick.tier === "OU")
            {
                tdTier.classList.add("ouBadge")
            }

            const tdTeam = document.createElement("td");
            tdTeam.textContent = pick.gamerTag;

            tr.appendChild(tdPick);
            tr.appendChild(tdName);
            tr.appendChild(tdTier);
            tr.appendChild(tdTeam);

            table.appendChild(tr)
        })
    })
    .catch(err => console.error("Failed to load draft results:", err));
}

displayDraftResults();


/*****************************************************************
                        OVERVIEW PAGE
 *****************************************************************/

function loadOverviewRoster()
{
    fetch('/ascent_draft_league/api/overview/get_active_user_roster.php')
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        const homeRoster = document.getElementById('homePkmnList');
        homeRoster.innerHTML = '';

        data.forEach(pkmn => {
            const li = document.createElement("li");
            li.textContent = pkmn;
            homeRoster.appendChild(li)
        })
    })
    .catch(err => console.error("Rules failed to load:", err));
}

loadOverviewRoster();










/*****************************************************************
                        League Information
 *****************************************************************/

    function loadRulesFormatFromDb()
    {
        fetch('/ascent_draft_league/api/league_information/get_league_information.php')
        .then(response => response.json())
        .then(data => {
        //    console.log(data)
            const ruleList = document.getElementById("ruleList");

            if(!ruleList) return;

            ruleList.innerHTML = "";

            data.rules.forEach(rule => {
                const li = document.createElement("li");
                li.textContent = rule;
                ruleList.appendChild(li);
            })
        })
        .catch(err => console.error("Rules failed to load:", err));
    }

    loadRulesFormatFromDb();


    // Open Modal
    const updateLeagueInfoBtn = document.getElementById("updateLeagueInfoBtn");
    const modal = document.getElementById("editLeagueInfoModal");

    if(updateLeagueInfoBtn){
        updateLeagueInfoBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            loadLeagueInfo();
        });
    }
    
    // Close Modal
    const closeModalBtn = document.getElementById("closeModalBtn");
    if (closeModalBtn) {
            closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            });
        }


    //Load info into Modal
    function loadLeagueInfo() {
    fetch('/ascent_draft_league/api/league_information/get_league_information.php')
        .then(res => res.json())
        .then(data => {

            // Safely set dates
            document.getElementById("draftDate").value = data.draft_date || "";
            document.getElementById("seasonStart").value = data.season_start || "";

            // Load rules into modal
            const container = document.getElementById("rulesContainer");
            container.innerHTML = "";
            data.rules.forEach(rule => {
                const input = document.createElement("input");
                input.type = "text";
                input.name = "rules[]";
                input.value = rule;
                container.appendChild(input);
            });

        })
        .catch(err => console.error("Rules failed to load:", err));
}


    const leagueInfoForm = document.getElementById("leagueInfoForm");
if (leagueInfoForm) {
    leagueInfoForm.addEventListener("submit", function(e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/ascent_draft_league/api/league_information/update_league_information.php', {
            method:"POST",
            body:formData
        })
        .then(res => res.text())
        .then(text => console.log(text));
    });
}


    //Add new rules


    const addRuleBtn = document.getElementById("addRuleBtn")

        if(addRuleBtn){
            addRuleBtn.addEventListener("click", () => {
                const container = document.getElementById("rulesContainer");

                const input = document.createElement("input");
                input.type = "text";
                input.name = "rules[]";
                input.placeholder = "Enter new rule";

                container.appendChild(input);
        })
    };



/*****************************************************************
                        Matchup Page
 *****************************************************************/

    async function loadActiveTeams() {
        
        const res = await fetch('/ascent_draft_league/api/matchup/get_active_teams.php');
        const teams = await res.json();
        // console.log('Teams fetched:', teams); 
        const teamOne = document.getElementById('teamOneSelect');
        const teamTwo = document.getElementById('teamTwoSelect');

        if(!teamOne || !teamTwo) return;


        teams.forEach(team => {
            const option1 = document.createElement('option');
            option1.value = team.active_user_id;
            option1.textContent = team.gamerTag;
            teamOne.appendChild(option1);

            const option2 = document.createElement('option');
            option2.value = team.active_user_id;
            option2.textContent = team.gamerTag;
            teamTwo.appendChild(option2);
        });
    }

    loadActiveTeams();



    document.getElementById('loadSelectedTeamsBtn').addEventListener('click', async () => {

        const team1Id = document.getElementById('teamOneSelect').value;
        const team2Id = document.getElementById('teamTwoSelect').value;

        if(loadBtn)
        {

        

            if(!team1Id || !team2Id) return alert("Select both teams");

            const res1 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team1Id}`);
            const team1Pkmn = await res1.json();

            const res2 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team2Id}`);
            const team2Pkmn = await res2.json();

            renderPokemonSelection('team1Container', team1Pkmn, 1);
            renderPokemonSelection('team2Container', team2Pkmn, 2);
        }

    });

    
    
    
    function renderPokemonSelection(containerId, pokemonList, team) {

        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';

        pokemonList.forEach(p => {

            const li = document.createElement('li');
            li.textContent = p.name;
            li.dataset.rosterPkmnId = p.roster_pkmn_id;

            li.addEventListener('click', () => {

                const tableId = team === 1 ? "team1MatchTable" : "team2MatchTable";
                const currentCount = document.querySelectorAll(`#${tableId} tr`).length;

                // If trying to add a new Pokémon but already at 6
                if (!li.classList.contains('selected') && currentCount >= 6) {
                    alert("You can only select 6 Pokémon per team.");
                    return;
                }

                li.classList.toggle('selected');

                if (li.classList.contains('selected')) {
                    addPokemonToTable(p, team);
                } else {
                    removePokemonFromTable(p.roster_pkmn_id);
                }

            });

            container.appendChild(li);

        });

    }

    





    function addPokemonToTable(pokemon, team) {

        const tableId = team === 1 ? "team1MatchTable" : "team2MatchTable";
        const table = document.getElementById(tableId);
        if (!table) return;

        if (document.getElementById(`pkmn-${pokemon.roster_pkmn_id}`)) return;

        const tr = document.createElement('tr');
        tr.id = `pkmn-${pokemon.roster_pkmn_id}`;

        const tdName = document.createElement('td');
        tdName.textContent = pokemon.name;

        const tdKills = document.createElement('td');
        const killsInput = document.createElement('input');
        killsInput.type = "number";
        killsInput.value = 0;
        killsInput.classList.add("killsInput");

        const tdDeaths = document.createElement('td');
        const deathsInput = document.createElement('input');
        deathsInput.type = "number";
        deathsInput.value = 0;
        deathsInput.classList.add("deathsInput");


        tdKills.appendChild(killsInput);
        tdDeaths.appendChild(deathsInput);

        tr.appendChild(tdName);
        tr.appendChild(tdKills);
        tr.appendChild(tdDeaths);

        table.appendChild(tr);

        updateTeamCount(team);
    }



    function removePokemonFromTable(rosterId){

        const row = document.getElementById(`pkmn-${rosterId}`);
        if(!row) return;

        const table = row.closest("tbody");
        if (!table) return;
        const team = table.id === "team1MatchTable" ? 1 : 2;

        row.remove();

        updateTeamCount(team);

    }


    function updateTeamCount(team){

        const tableId = team === 1 ? "team1MatchTable" : "team2MatchTable";
        const countId = team === 1 ? "team1Count" : "team2Count";

        const currentCount = document.querySelectorAll(`#${tableId} tr`).length;

        const countEl = document.getElementById(countId);
        if (countEl) {
            countEl.textContent = `Team ${team} (${currentCount} / 6 selected)`;
        }

    }



    // SUBMISSION
    const form = document.getElementById('add_matchup_form');

    if (form) {
        form.addEventListener('submit', async e => {
            e.preventDefault();

            const player1 = document.getElementById('teamOneSelect')?.value;
            const player2 = document.getElementById('teamTwoSelect')?.value;
            const replayLink = document.getElementById('replayLink')?.value;

            const stats = [];
            ['team1MatchTable','team2MatchTable'].forEach(tableId => {
                document.querySelectorAll(`#${tableId} tr`).forEach(row => {
                    const rosterId = row.id.replace("pkmn-", "");
                    const kills = row.querySelector(".killsInput").value;
                    const deaths = row.querySelector(".deathsInput").value;

                    stats.push({
                        roster_pkmn_id: rosterId,
                        kills: parseInt(kills),
                        deaths: parseInt(deaths),
                        used: 1
                    });
                });
            });

            console.log("Replay link being sent:", replayLink); // ✅ debug

            try {
                const res = await fetch('/ascent_draft_league/api/matchup/submit_matchup.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ player1, player2, stats, replayLink })
                });

                const data = await res.json();

                if (data.status === "success") {
                    alert("Matchup saved!");
                    window.location.href = '/ascent_draft_league/matchup.php';
                } else {
                    alert("Error: " + data.message);
                }

            } catch(err) {
                alert("Network or server error: " + err.message);
            }

        });
    }
    
    
});
