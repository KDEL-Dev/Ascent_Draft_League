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
                    case "OU": case "UUBL": ouPokemon.push(pkmn); break;
                    case "UU": case "RUBL": uuPokemon.push(pkmn); break;
                    case "RU": case "NUBL": ruPokemon.push(pkmn); break;
                    case "NU": case "PUBL": nuPokemon.push(pkmn); break;
                }
            });

            displayOu(ouPokemon);
            displayUu(uuPokemon);
            displayRu(ruPokemon);
            displayNu(nuPokemon);
        } 
        catch (err) {
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

            if (item.drafted) {
                li.classList.add("drafted");
                li.style.pointerEvents = 'none';
            } else if (clickable) {
                li.addEventListener("click", () => {
                    console.log("Clicked Pokémon:", item);
                    draftPokemon(item);
                    li.classList.add("myPick");
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

    
    async function loadDraftState() 
    {
        try {
            const response = await fetch('/ascent_draft_league/api/draft_auto_update.php');
            const data = await response.json();

            const currentPickEl = document.getElementById("currentPickInfo");
            const previousPickEl = document.getElementById("previousPickInfo");

            // ❗ If this page doesn't have draft UI, just exit safely
            if (!currentPickEl && !previousPickEl) return;

            // 🚨 NEW: check if draft is finished
            if (data.draft_finished) {
                currentPickEl.textContent = "Draft Complete";
                toggleDraftButtons(false);
                return;
            }

            if (!data.draft_started) {
                if (currentPickEl) currentPickEl.textContent = "Stand-By";
                if (previousPickEl) previousPickEl.textContent = "-";

                toggleDraftButtons(false);
                return; 
            }

            if (currentPickEl) currentPickEl.textContent = data.current_player ?? "Waiting...";
            if (previousPickEl) previousPickEl.textContent = data.previous_pick ?? "-";

            const myGamerTag = document.body.dataset.gamertag;
            const MAX_POKEMON_PER_USER = data.maxPokemon ?? 12; //CHANGED TO 12

            const canDraft =
                (myGamerTag === data.current_player) &&
                (data.myDraftedCount < MAX_POKEMON_PER_USER);

            toggleDraftButtons(canDraft);

        } catch (err) {
            console.error("Failed to load draft state:", err);
        }
    }

    function toggleDraftButtons(enable) {
        ['listOfOuPkmn','listOfUuPkmn','listOfRuPkmn','listOfNuPkmn'].forEach(id => {
            const container = document.getElementById(id);
            if (!container) return;
            container.querySelectorAll('li').forEach(li => {
                li.style.pointerEvents = enable ? 'auto' : 'none';
                li.style.opacity = enable ? '1' : '0.5';
            });
        });
    }

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
                document.querySelectorAll("li").forEach(li => {
                    if (li.textContent === pkmn.name) li.classList.add("drafted");
                });
                await getShowdownDexFromDB();
            } else {
                alert(data.error || "Draft failed");
            }
        } catch (err) {
            console.error("Draft error:", err);
        }
    }

    
    await loadDraftState();
    setInterval(loadDraftState, 2000);

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
                if (data.error) return alert("Randomization failed: " + data.error);
                renderDraftOrder(data);
                alert("Draft order randomized!");
            } catch (err) {
                console.error("Randomization error:", err);
            }
        });
    }

    // -------------------
    // START DRAFT BUTTON
    // -------------------
    const startDraftBtn = document.getElementById('startDraftBtn');

    if (startDraftBtn) {
        startDraftBtn.addEventListener("click", async () => {
            if (!confirm("Start draft for ALL users?")) return;

            try {
                const res = await fetch('/ascent_draft_league/api/draft/start_draft.php', {
                    method: 'POST'
                });

                const data = await res.json();

                if (data.status === "success") {
                    alert("Draft started!");
                } else {
                    alert("Error: " + data.error);
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    await loadDraftOrder();

    // -------------------
    // CLEAR DRAFT BUTTON
    // -------------------
    const clearDraftBtn = document.getElementById('clearDraftBtn');
    if (clearDraftBtn) {
        clearDraftBtn.addEventListener("click", async () => {
            if(!confirm("Are you sure you want to reset the draft?")) return;
            try {
                const response = await fetch('/ascent_draft_league/api/draft/clear_draft.php', { method: 'POST' });
                const data = await response.json();
                if(!response.ok) throw new Error(data.error || "Failed to reset draft");
                alert("Draft reset successfully.");
                location.reload();
            } catch (error) {
                console.error("Draft reset error", error);
                alert("Error resetting draft");
            }
        });
    }

    // -------------------
    // SKIP PICK
    // -------------------

    const skipBtn = document.getElementById("skipPickBtn");

    if (skipBtn) {
        skipBtn.addEventListener("click", async () => {
            if (!confirm("Skip the current pick?")) return;

            try {
                const res = await fetch('/ascent_draft_league/api/draft/skip_pick.php', {
                    method: 'POST'
                });

                const data = await res.json();

                if (data.status === "success") {
                    alert("Pick skipped!");

                    // Refresh draft state so next player updates
                    await loadDraftState();
                } else {
                    alert("Error: " + data.error);
                }
            } catch (err) {
                console.error(err);
            }
        });
    }



    // -------------------
    // ENHD DRAFT
    // -------------------

    const endDraftBtn = document.getElementById("endDraftBtn");

    if (endDraftBtn) {
        endDraftBtn.addEventListener("click", async () => {
            if (!confirm("End the draft for all users?")) return;

            try {
                const res = await fetch('/ascent_draft_league/api/draft/end_draft.php', {
                    method: 'POST'
                });

                const data = await res.json();

                if (data.status === "success") {
                    alert("Draft ended!");

                    // Refresh UI state so buttons freeze immediately
                    await loadDraftState();
                } else {
                    alert("Error: " + data.error);
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    // -------------------
    // DRAFT RECAP
    // -------------------
    const recapTableBody = document.getElementById("recapTableBody");

    function displayDraftResults() {
        if (!recapTableBody) return;

        fetch('/ascent_draft_league/api/draft/get_draft_result.php')
        .then(res => res.json())
        .then(data => {
            recapTableBody.innerHTML = "";

            data.forEach(pick => {
                const tr = document.createElement("tr");

                const tdPick = document.createElement("td");
                tdPick.textContent = pick.pick_number;

                const tdName = document.createElement("td");
                tdName.textContent = pick.name;

                const tdTier = document.createElement("td");
                tdTier.textContent = pick.tier;
                if (pick.tier === "OU") tdTier.classList.add("ouBadge");

                const tdTeam = document.createElement("td");
                tdTeam.textContent = pick.gamerTag;

                tr.appendChild(tdPick);
                tr.appendChild(tdName);
                tr.appendChild(tdTier);
                tr.appendChild(tdTeam);

                recapTableBody.appendChild(tr);
            });
        })
        .catch(err => console.error("Failed to load draft results:", err));
    }

    displayDraftResults();

    // -------------------
    // OVERVIEW PAGE
    // -------------------
    function loadOverviewRoster() {
        const homeRoster = document.getElementById('homePkmnList');
        if (!homeRoster) return;

        fetch('/ascent_draft_league/api/overview/get_active_user_roster.php')
        .then(res => res.json())
        .then(data => {
            homeRoster.innerHTML = "";
            data.forEach(pkmn => {
                const li = document.createElement("li");
                li.textContent = pkmn;
                homeRoster.appendChild(li);
            });
        })
        .catch(err => console.error("Failed to load roster:", err));
    }
    loadOverviewRoster();

    // -------------------
    // LEAGUE INFORMATION
    // -------------------
    function loadRulesFormatFromDb() {
        const ruleList = document.getElementById("ruleList");
        if (!ruleList) return;

        fetch('/ascent_draft_league/api/league_information/get_league_information.php')
        .then(res => res.json())
        .then(data => {
            ruleList.innerHTML = "";
            data.rules?.forEach(rule => {
                const li = document.createElement("li");
                li.textContent = rule;
                ruleList.appendChild(li);
            });
        })
        .catch(err => console.error("Rules failed to load:", err));
    }
    loadRulesFormatFromDb();

    const updateLeagueInfoBtn = document.getElementById("updateLeagueInfoBtn");
    const modal = document.getElementById("editLeagueInfoModal");
    if (updateLeagueInfoBtn && modal) {
        updateLeagueInfoBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            loadLeagueInfo();
        });
    }

    const closeModalBtn = document.getElementById("closeModalBtn");
    if (closeModalBtn && modal) {
        closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });
    }

    function loadLeagueInfo() {
        fetch('/ascent_draft_league/api/league_information/get_league_information.php')
        .then(res => res.json())
        .then(data => {
            const draftDate = document.getElementById("draftDate");
            const seasonStart = document.getElementById("seasonStart");
            const container = document.getElementById("rulesContainer");

            if(draftDate) draftDate.value = data.draft_date || "";
            if(seasonStart) seasonStart.value = data.season_start || "";
            if(container) {
                container.innerHTML = "";
                data.rules?.forEach(rule => {
                    const input = document.createElement("input");
                    input.type = "text";
                    input.name = "rules[]";
                    input.value = rule;
                    container.appendChild(input);
                });
            }
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

    const addRuleBtn = document.getElementById("addRuleBtn");
    if(addRuleBtn){
        addRuleBtn.addEventListener("click", () => {
            const container = document.getElementById("rulesContainer");
            if(!container) return;
            const input = document.createElement("input");
            input.type = "text";
            input.name = "rules[]";
            input.placeholder = "Enter new rule";
            container.appendChild(input);
        });
    }

    // -------------------
    // MATCHUP PAGE
    // -------------------
    async function loadActiveTeams() {
        const teamOne = document.getElementById('teamOneSelect');
        const teamTwo = document.getElementById('teamTwoSelect');
        if (!teamOne || !teamTwo) return;

        try {
            const res = await fetch('/ascent_draft_league/api/matchup/get_active_teams.php');
            const teams = await res.json();
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
        } catch(err) {
            console.error("Failed to load active teams:", err);
        }
    }
    await loadActiveTeams();

    const loadBtn = document.getElementById('loadSelectedTeamsBtn');
    if (loadBtn) {
        loadBtn.addEventListener('click', async () => {
            const team1Id = document.getElementById('teamOneSelect')?.value;
            const team2Id = document.getElementById('teamTwoSelect')?.value;
            if (!team1Id || !team2Id) return alert("Select both teams");

            try {
                const res1 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team1Id}`);
                const team1Pkmn = await res1.json();
                const res2 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team2Id}`);
                const team2Pkmn = await res2.json();

                renderPokemonSelection('team1Container', team1Pkmn, 1);
                renderPokemonSelection('team2Container', team2Pkmn, 2);
            } catch(err) {
                console.error("Failed to load selected teams:", err);
            }
        });
    }

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
                const table = document.getElementById(tableId);
                if (!table) return;
                const currentCount = table.querySelectorAll('tr').length;

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

        const tdName = document.createElement('td'); tdName.textContent = pokemon.name;
        const tdKills = document.createElement('td');
        const killsInput = document.createElement('input'); killsInput.type = "number"; killsInput.value = 0; killsInput.classList.add("killsInput");
        tdKills.appendChild(killsInput);

        const tdDeaths = document.createElement('td');
        const deathsInput = document.createElement('input'); deathsInput.type = "number"; deathsInput.value = 0; deathsInput.classList.add("deathsInput");
        tdDeaths.appendChild(deathsInput);

        tr.appendChild(tdName); tr.appendChild(tdKills); tr.appendChild(tdDeaths);
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
        const countEl = document.getElementById(countId);
        if (!countEl) return;
        const currentCount = document.querySelectorAll(`#${tableId} tr`).length;
        countEl.textContent = `Team ${team} (${currentCount} / 6 selected)`;
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
                    const kills = row.querySelector(".killsInput")?.value ?? 0;
                    const deaths = row.querySelector(".deathsInput")?.value ?? 0;

                    stats.push({ roster_pkmn_id: rosterId, kills: parseInt(kills), deaths: parseInt(deaths), used: 1 });
                });
            });

            console.log("Replay link being sent:", replayLink);

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







        // DELETE MATCHUP
        document.addEventListener("click", async (e) => {
        if (!e.target.classList.contains("deleteMatchBtn")) return;

        const matchId = e.target.dataset.matchId;
        if (!matchId) return;

        if (!confirm("Are you sure you want to delete this matchup?")) return;

        try {
            const res = await fetch('/ascent_draft_league/api/matchup/delete_matchup.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ matchup_id: matchId })
            });

            const data = await res.json();

            if (data.status === "success") {
                alert("Matchup deleted successfully!");
                // Remove the matchup from DOM
                const container = e.target.closest(".editDeleteMatchCont");
                if (container) container.remove();
            } else {
                alert("Error deleting matchup: " + data.message);
            }
        } catch (err) {
            console.error("Network error:", err);
            alert("Failed to delete matchup. Check console.");
        }
    });




        // EDIT MATCHUP
    document.addEventListener("click", (e) => {
        if (!e.target.classList.contains("editMatchBtn")) return;

        const matchId = e.target.dataset.matchId;
        if (!matchId) return;

        // 🔥 redirect to edit page
        window.location.href = `/ascent_draft_league/edit_matchup.php?matchup_id=${matchId}`;
    });


    async function loadEditMatchup() {
        if (typeof matchupId === "undefined") return;

        try {
            const res = await fetch(`/ascent_draft_league/api/matchup/get_matchup.php?matchup_id=${matchupId}`);
            const data = await res.json();

            if (data.status !== "success") {
                alert(data.message);
                return;
            }

            document.getElementById("replayLink").value = data.matchup.replay_link;

            renderEditTable("team1Body", data.team1);
            renderEditTable("team2Body", data.team2);

        } catch (err) {
            console.error("Failed to load matchup:", err);
        }
    }

    function renderEditTable(containerId, team) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = "";

        team.forEach(p => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${p.pokemon_name}</td>
                <td><input type="number" name="kills[${p.roster_pkmn_id}]" value="${p.kills}"></td>
                <td><input type="number" name="deaths[${p.roster_pkmn_id}]" value="${p.deaths}"></td>
            `;

            container.appendChild(tr);
        });
    }

    loadEditMatchup();


    // test

    const editForm = document.getElementById('edit_matchup_form');

    if (editForm) {
        editForm.addEventListener('submit', async e => {
            e.preventDefault();

            const formData = new FormData(editForm);

            const data = {
                matchup_id: formData.get('matchup_id'),
                replay_link: formData.get('replay_link'),
                stats: []
            };

            for (const [key, value] of formData.entries()) {
                if (key.startsWith('kills[')) {
                    const rosterId = key.match(/\d+/)[0];

                    data.stats.push({
                        roster_pkmn_id: rosterId,
                        kills: parseInt(value),
                        deaths: parseInt(formData.get(`deaths[${rosterId}]`))
                    });
                }
            }

            try {
                const res = await fetch('/ascent_draft_league/api/matchup/update_matchup.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (result.status === "success") {
                    alert("Matchup updated!");
                    window.location.href = '/ascent_draft_league/matchup.php';
                } else {
                    alert("Error: " + result.message);
                }

            } catch (err) {
                console.error(err);
                alert("Network error: " + err.message);
            }
        });
    }

});