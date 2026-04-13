document.addEventListener("DOMContentLoaded", async () => {

    // NAVBAR
    const hamburger = document.getElementById("hamburgerBtn");
    const navbar = document.querySelector(".navBar");
    const page = document.querySelector(".pageContent");

    if (hamburger && navbar && page) {

        hamburger.addEventListener("click", () => {
            navbar.classList.toggle("active");
        });

        page.addEventListener("click", () => {
            navbar.classList.remove("active");
        });
    }


    // ------------
    // REGISTRATION
    // ------------

    const teamInput = document.getElementById('teamNameInput');
    if(teamInput) 
    {
        teamInput.addEventListener('input', () => {
            teamInput.value = teamInput.value.toUpperCase();
        });
    }

    // ----------------------------
    // LOAD POKÉMON BY TIER FROM DB
    // ----------------------------


    const isPokeboxPage = window.location.pathname.includes("pokebox.php");
    const isDraftPage = window.location.pathname.includes("draft.php");
    const isSwapPage = window.location.pathname.includes("swap.php");

    // Get pokemon from db and store them in an array
    async function getShowdownDexFromDB() {
        try { // Use try catch when something may realistically fail or when you want something to not crash your site
            const response = await fetch('/ascent_draft_league/api/get_pkmn_by_tier.php');
            const pokedex = await response.json();

            // Create empty arrays to hold pokemon
            let ouPokemon = [], uuPokemon = [], ruPokemon = [], nuPokemon = [];

            pokedex.forEach(pkmn => {
                // Switch is just an if else statement.
                switch (pkmn.tier) {
                    // If pokemon is ou, uu, etc, push that pokemon into matching array. 
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


    // Display pokemon as buttons in html

    //  elementId is id from draft.php
    function displayList(list, elementId, clickable = false) 
    {   // If container doesnt exist on page then return
        const container = document.getElementById(elementId);
        if (!container) return;

        // Clear content in container
        container.innerHTML = "";

        // Create li for each pokemon
        list.forEach(item => {
            const li = document.createElement("li");
            li.textContent = item.name;

            // reminder: JSON is what contains info on whether or not it is drafted. 
            if (item.drafted) {
                li.classList.add("drafted");
                li.style.pointerEvents = 'none';
            } 
            else if (clickable) 
            {
                li.addEventListener("click", () => {
                    // console.log("Clicked Pokémon:", item);
                    draftPokemon(item);
                    li.classList.add("myPick");
                });
            }

            container.appendChild(li);
        });
    }

    // List functions

    
    function displayOu(list) { displayList(list, 'listOfOuPkmn', true); }
    function displayUu(list) { displayList(list, 'listOfUuPkmn', true); }
    function displayRu(list) { displayList(list, 'listOfRuPkmn', true); }
    function displayNu(list) { displayList(list, 'listOfNuPkmn', true); }

    if (isDraftPage) 
    {
        await getShowdownDexFromDB();
    }


    // ------------------------------------------------
    // ADMIN BUTTONS FOR SHOWDOWN TO DB AND INSERT TIER
    // ------------------------------------------------

    const insertPokemonBtn = document.getElementById("insertPokemonBtn");
    const insertPkmnTierBtn = document.getElementById("insertPkmnTierBtn");

    if(insertPokemonBtn)
    {
        // Insert Pokémon into DB
        insertPokemonBtn.addEventListener("click", async () => {
            try {
                const response = await fetch('/ascent_draft_league/api/showdown_to_db/add_pokemon_to_db.php');
                const data = await response.json();

                // console.log("Insert Pokémon response:", data);
                alert(`Inserted Pokémon: ${data.inserted}`);
            } 
            catch (err) 
            {
                // console.error("Error inserting Pokémon:", err);
                alert("Failed to insert Pokémon.");
            }
        })
    }

    if(insertPkmnTierBtn)
    {
    // Insert Pokémon tiers
        insertPkmnTierBtn.addEventListener("click", async () => {
            try {
                const response = await fetch('/ascent_draft_league/api/showdown_to_db/insert_current_pokemon_tier.php');
                const data = await response.json();

                console.log("Insert tiers response:", data);
                alert(`Updated tiers: ${data.inserted}`);
            } catch (err) {
                console.error("Error inserting tiers:", err);
                alert("Failed to insert Pokémon tiers.");
            }
        })
    }

    // -------------------
    // DRAFT ORDER DISPLAY
    // -------------------
    const draftOrderList = document.getElementById("draftOrderList");
    async function loadDraftOrder() 
    {
        try 
        {
            const response = await fetch("/ascent_draft_league/api/draft/get_draft_order.php");
            const data = await response.json();
            renderDraftOrder(data);
        } 
        catch (err) 
        {
            console.error("Failed to load draft order:", err);
        }
    }

    function renderDraftOrder(list) 
    {
        if (!draftOrderList) return;
        draftOrderList.innerHTML = "";

        list.forEach((teamName, index) => {
            const li = document.createElement("li");

            // Add number + team name
            li.innerHTML = `<span class="draftNumber">${index + 1}.</span> ${teamName}`;

            draftOrderList.appendChild(li);
        });
    }

    // -------------------
    //      DRAFT STATE
    // -------------------

    let draftInterval = null;
    let pokedexData = null;
    let showdownId = null;

    async function loadPokedex() 
    {
        if (!pokedexData) 
        {
            const res = await fetch('/ascent_draft_league/showdownData/pokedex.json');
            pokedexData = await res.json();
        }
        return pokedexData;
    }

    let lastPreviousPick = null;

    async function loadDraftState() 
    {
        try 
        {
            const response = await fetch('/ascent_draft_league/api/draft_auto_update.php');
            const data = await response.json();

            const currentPickEl = document.getElementById("currentPickInfo");
            const previousPickEl = document.getElementById("previousPickInfo");
            const ppPkmnImgCont = document.getElementById("ppPkmnImgCont");
            const ppPkmnNameCont = document.getElementById("ppPkmnNameCont");
            const ppTeamName = document.getElementById("ppTeamName");
            const ppStatCont = document.getElementById("ppStatCont");

            if (!currentPickEl && !previousPickEl) return;
            
            
            
            let pokeName = null;

            if (data.previous_pick) {
                pokeName = data.previous_pick.toLowerCase();

                showdownId = data.previous_pick
                .toLowerCase()
                .replace(/[^a-z0-9]/g, '');
            }

            if (data.previous_pick !== lastPreviousPick) {

    lastPreviousPick = data.previous_pick;

    if (data.previous_pick) {

        const pokeName = data.previous_pick.toLowerCase();

        showdownId = pokeName.replace(/[^a-z0-9]/g, '');

        const base = "https://img.pokemondb.net/sprites/scarlet-violet/normal/";

        const url1 = `${base}${pokeName}.png`;

        const url2 = `${base}${pokeName
            .replace('-galar', '-galarian')
            .replace('-hisui', '-hisuian')
            .replace('-paldea', '-paldean')
            .replace('-alola', '-alolan')
            .replace('-f', '-female')
        }.png`;

        ppPkmnImgCont.innerHTML = `
            <img src="${url1}" width="200"
                onerror="this.onerror=null; this.src='${url2}'; this.onerror=function(){this.style.display='none'};">
        `;

        ppPkmnNameCont.innerHTML = `<div>${data.previous_pick}</div>`;
        ppTeamName.innerHTML = `<div>${data.previous_team}</div>`;

        // Load pokedex + stats
        const dex = await loadPokedex();
        const pkmnData = dex[showdownId];

        if (pkmnData && pkmnData.baseStats) {
            const { hp, atk, def, spa, spd, spe } = pkmnData.baseStats;

            ppStatCont.innerHTML = `
                <table class="ppStatTable">
                    <thead>
                        <th class="ppTH">HP</th>
                        <th class="ppTH">ATK</th>
                        <th class="ppTH">DEF</th>
                    </thead>
                    <tbody>
                        <td class="ppTD">${hp}</td>
                        <td class="ppTD">${atk}</td>
                        <td class="ppTD">${def}</td>
                    </tbody>
                </table>
                <table class="ppStatTable">
                    <thead>
                        <th class="ppTH">SP.ATK</th>
                        <th class="ppTH">SP.DEF</th>
                        <th class="ppTH">SPE</th>
                    </thead>
                    <tbody>
                        <td class="ppTD">${spa}</td>
                        <td class="ppTD">${spd}</td>
                        <td class="ppTD">${spe}</td>
                    </tbody>
                </table>
            `;
        }

    } else {
        // CLEAR UI when null
        ppPkmnImgCont.innerHTML = "";
        ppPkmnNameCont.innerHTML = "<div>-</div>";
        ppTeamName.innerHTML = "<div>-</div>";
        ppStatCont.innerHTML = "";
    }
}


            if (data.draft_finished) {
                currentPickEl.textContent = "Stand-By";
                toggleDraftButtons(false);
                return;
            }

            if (!data.draft_started) {
                if (currentPickEl) currentPickEl.textContent = "Stand-By";
                if (previousPickEl) previousPickEl.textContent = "No picks have been made yet";

                toggleDraftButtons(false);
                return; 
            }

            if (currentPickEl) currentPickEl.textContent = data.current_player ?? "Waiting...";
            // if (previousPickEl) previousPickEl.textContent = data.previous_pick ?? "-";

            const myTeamName = document.body.dataset.teamName;
            const MAX_POKEMON_PER_USER = data.maxPokemon ?? 12; //CHANGED TO 12


            const canDraft =
                (myTeamName === data.current_player) &&
                (data.myDraftedCount < MAX_POKEMON_PER_USER);

            toggleDraftButtons(canDraft);

        } 
        catch (err) 
        {
            console.error("Failed to load draft state:", err);
        }

        return true;
    }

    function toggleDraftButtons(enable) 
    {
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

                await getShowdownDexFromDB();
                await loadDraftState();

                document.querySelectorAll("li").forEach(li => {
                    if (li.textContent === pkmn.name) li.classList.add("drafted");
                });
                await getShowdownDexFromDB();
            } else {
                alert(data.error || "Draft failed");
            }
        } 
        catch (err) 
        {
            console.error("Draft error:", err);
        }
    }

    
    // await loadDraftState();

    // This was telling my draft auto update to always run in the background.
    // setInterval(loadDraftState, 2000); 

    // Testing this new method

    // draftInterval = setInterval(async () => {
    //     const shouldContinue = await loadDraftState();

    //     if (!shouldContinue && draftInterval) 
    //     {
    //         clearInterval(draftInterval);
    //         draftInterval = null;
    //     }
    // }, 2000);

    

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
    const ppFlexRow = document.getElementById('ppFlexRow');

    if (startDraftBtn) {
        startDraftBtn.addEventListener("click", async () => {
            if (!confirm("Start draft for ALL users?")) return;

            try {
                const res = await fetch('/ascent_draft_league/api/draft/start_draft.php', {
                    method: 'POST'
                });

                const data = await res.json();

                if (data.status === "success") 
                {
                    alert("Draft Started!");
                    ppFlexRow.style.visibility = 'visible';
                    await loadDraftState();
                } 
                else 
                {
                    alert("Error: " + data.error);
                }
            } 
            catch(err) 
            {
                console.error(err);
            }
        });
    }

    // await loadDraftOrder();

    if (isDraftPage) 
    {
        await loadDraftState();
        await loadDraftOrder();

        draftInterval = setInterval(loadDraftState, 2000);
    }

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
            } 
            catch (error) 
            {
                console.error("Draft reset error", error);
                alert("Error resetting draft");
            }
        });
    }

    // -----------------------
    //      SKIP PICK BUTTON
    // -----------------------

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
            } 
            catch (err) 
            {
                console.error(err);
            }
        });
    }



    // -------------------
    // END DRAFT
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

    // Adding color to tier badges
    const recapTableBody = document.getElementById("recapTableBody");
    const recapFlexCont = document.getElementById("recapFlexCont");
    const recapTable = document.getElementById("recapTable");

    function displayDraftResults() {
        if (!recapTableBody) return;

        fetch('/ascent_draft_league/api/draft/get_draft_result.php')
        .then(res => res.json())
        .then(data => {
            // recapFlexCont.innerHTML = "";

            // If data is empty, display message
            if (!Array.isArray(data) || data.length === 0) 
            {
                recapFlexCont.innerHTML = 
                        `<p id="noMatches">
                            No matches have been played yet
                        </p>`;
                return;
            }

            // Dictionary. Converting tiers to class names
            const tierMap = {
                'OU': 'ou-RosterColor',
                'UUBL': 'ou-RosterColor',
                'UU': 'uu-RosterColor',
                'RUBL': 'uu-RosterColor',
                'RU': 'ru-RosterColor',
                'NUBL': 'ru-RosterColor',
                'NU': 'nu-RosterColor',
                'PUBL': 'nu-RosterColor',
                'PU': 'nu-RosterColor',
                'ZUBL': 'nu-RosterColor',
                'ZU': 'nu-RosterColor'
            };

            data.forEach(pick => {
                const tr = document.createElement("tr");

                const tdPick = document.createElement("td");
                tdPick.textContent = pick.pick_number;

                const tdName = document.createElement("td");
                tdName.textContent = pick.name;

                const tdTier = document.createElement("td");
                tdTier.textContent = pick.tier;

                // Adding color to badges using dictionary
                const tierClass = tierMap[pick.tier.toUpperCase()];
                if (tierClass) tdTier.classList.add(tierClass);

                const tdTeam = document.createElement("td");
                tdTeam.textContent = pick.team_name;

                tr.appendChild(tdPick);
                tr.appendChild(tdName);
                tr.appendChild(tdTier);
                tr.appendChild(tdTeam);

                recapTableBody.appendChild(tr);
            });

            recapTable.style.visibility = "visible";
        })
        .catch(err => console.error("Failed to load draft results:", err));
    }

    displayDraftResults();
    // ------------------
    //      POKESWAP
    //-------------------

    async function getShowdownDexForSwap() {
        try { // Use try catch when something may realistically fail or when you want something to not crash your site
            const response = await fetch('/ascent_draft_league/api/get_pkmn_by_tier.php');
            const pokedex = await response.json();

            // Create empty arrays to hold pokemon
            let ouPokemon = [], uuPokemon = [], ruPokemon = [], nuPokemon = [];

            pokedex.forEach(pkmn => {
                // Switch is just an if else statement.
                switch (pkmn.tier) {
                    // If pokemon is ou, uu, etc, push that pokemon into matching array. 
                    case "OU": case "UUBL": ouPokemon.push(pkmn); break;
                    case "UU": case "RUBL": uuPokemon.push(pkmn); break;
                    case "RU": case "NUBL": ruPokemon.push(pkmn); break;
                    case "NU": case "PUBL": nuPokemon.push(pkmn); break;
                }
            });

            displaySwapOu(ouPokemon);
            displaySwapUu(uuPokemon);
            displaySwapRu(ruPokemon);
            displaySwapNu(nuPokemon);
        } 
        catch (err) {
            console.error("Failed to load Pokémon from DB:", err);
        }
    }

    // Reusing the function from Draft Box but going to tweak it
    function displayAvailablePkmnList(list, elementId) {
        const container = document.getElementById(elementId);
        // If container doesn't exist on the page, then return. 
        // This will prevent site from breaking
        if (!container) return;

        // If any content is in the container, then remove it.
        container.innerHTML = "";

        // For each pokemon, create a button basically.
        list.forEach(item => {
            const li = document.createElement("li");
            li.textContent = item.name;

            if (item.drafted) 
            {
                li.classList.add("drafted");
                li.style.pointerEvents = 'none';
            } 
            else
            {
                li.addEventListener("click", () => {
                    // console.log("Clicked Pokémon:", item);
                    window.location.href = `swap.php?add=${item.id}`; // Adds id to the route    
                });
            }

            container.appendChild(li);
        });
    }

    function displaySwapOu(list) { displayAvailablePkmnList(list, 'listOfOuPkmn'); }
    function displaySwapUu(list) { displayAvailablePkmnList(list, 'listOfUuPkmn'); }
    function displaySwapRu(list) { displayAvailablePkmnList(list, 'listOfRuPkmn'); }
    function displaySwapNu(list) { displayAvailablePkmnList(list, 'listOfNuPkmn'); }

    // if (isSwapPage) 
    // {
    //     await getShowdownDexForSwap();
    // }


    // // SENDING TO FORM

    // if (isSwapPage) {
    //     await initSwapPage();
    // }

    

    async function initSwapPage() {

        // -------------------------
        //      GET URL PARAM
        // -------------------------
        const params = new URLSearchParams(window.location.search);
        const addId = params.get("add");

        if (!addId) 
        {
            alert("No Pokémon selected.");
            return;
        }

        // -------------------------
        //       GET ELEMENTS
        // -------------------------

        const availablePkmnName = document.getElementById("availablePkmnName");
        const availablePkmnIdInput = document.getElementById("availablePkmnId");
        const select = document.getElementById("dropSelect");
        const confirmBtn = document.getElementById("confirmSwapBtn");

        if (!availablePkmnName || !availablePkmnIdInput || !select) 
        {
            console.error("Swap page missing elements");
            return;
        }

        // -------------------------
        //      LOAD FREE POKEMON
        // -------------------------

        let availablePkmn;

        try {
            const res = await fetch(`/ascent_draft_league/api/pokebox/get_pkmn_by_id.php?id=${addId}`);
            availablePkmn = await res.json();

            if (!availablePkmn || availablePkmn.error) {
                alert("Failed to load free agent");
                return;
            }

            availablePkmnName.value = availablePkmn.name;
            availablePkmnIdInput.value = availablePkmn.id;

        } 
        catch (err) 
        {
            console.error("Available Pokemon error:", err);
            return;
        }

        // console.log("FREE PKMN:", availablePkmn);

        // -------------------------
        //      LOAD USER ROSTER
        // -------------------------

        let roster;

        const res = await fetch('/ascent_draft_league/api/user/get_user_roster.php');

        if (!res.ok) {
            console.error("Roster request failed:", res.status);
            return;
        }

        roster = await res.json();

        if (!Array.isArray(roster)) 
        {
            console.error("Roster is not an array:", roster);
            return;
        }

        // -------------------------
        //      FILTER AND POPULATE
        // -------------------------

        select.innerHTML = "";

        function getTierGroup(tier) {
        //  Created Dictionary similar to one to color tier badges 
        const map = {
            "OU": "OU", "UUBL": "OU",
            "UU": "UU", "RUBL": "UU",
            "RU": "RU", "NUBL": "RU",
            "NU": "NU", "PUBL": "NU"
        };
        return map[tier] || null;
}
        // Finds group incoming pokemon belong to
       const availablePkmnTierGroup = getTierGroup(availablePkmn.tier);

       // Matches roster to tier of incoming Pokemon
        const validRoster = roster.filter(p => {
            return getTierGroup(p.tier) === availablePkmnTierGroup;
        });


        if (validRoster.length === 0) {
            console.warn("No matching tier Pokémon found", {
                roster,
                availablePkmn
            });
        }

        // Create dropdown
        validRoster.forEach(p => {
            const opt = document.createElement("option");
            opt.value = p.roster_pkmn_id;
            opt.textContent = p.name;
            select.appendChild(opt);
        });

        // console.log("ROSTER:", roster);

        // -------------------------
        // STEP 6: SUBMIT SWAP
        // -------------------------
        if (isSwapPage && confirmBtn) 
        {
            confirmBtn.addEventListener("click", async () => {

                const dropId = parseInt(select.value);
                const addId = availablePkmn.id;

                if (!dropId) {
                    alert("Select a Pokémon to drop");
                    return;
                }

                try {
                    const res = await fetch('/ascent_draft_league/api/pokebox/swap_pkmn.php', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            add: addId,
                            drop: dropId
                        })
                    });

                    const data = await res.json();

                    if (data.status === "success") {
                        alert("Swap completed!");
                        window.location.href = "pokebox.php";
                    } else {
                        alert(data.error || "Swap failed");
                    }

                } 
                catch (err) 
                {
                    console.error("Swap failed:", err);
                }
            });
        }
    }

    // if (isPokeboxPage) 
    // {
    //     await getShowdownDexForSwap();
    //     await initSwapPage();
    // }

    if (isPokeboxPage) 
    {
        await getShowdownDexForSwap();
    }

    if (isSwapPage) {
    await getShowdownDexForSwap();
    await initSwapPage();
}

    // -------------------
    // OVERVIEW PAGE
    // -------------------

    // Turning off function below. This will be a future feature.
    /*
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
    */

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

    // -----------------------
    //      MATCHUP PAGE
    // ----------------------

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
                option1.textContent = team.team_name;
                teamOne.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = team.active_user_id;
                option2.textContent = team.team_name;
                teamTwo.appendChild(option2);
            });
        } catch(err) {
            console.error("Failed to load active teams:", err);
        }
    }
    await loadActiveTeams();

    const loadBtn = document.getElementById('loadSelectedTeamsBtn');
    if (loadBtn) 
        {
            loadBtn.addEventListener('click', async () => {

            const team1Select = document.getElementById('teamOneSelect');
            const team2Select = document.getElementById('teamTwoSelect');

            const team1Id = team1Select?.value;
            const team2Id = team2Select?.value;

            const team1Name = team1Select?.selectedOptions[0]?.text;
            const team2Name = team2Select?.selectedOptions[0]?.text;

            const winnerLabel1 = document.getElementById("winnerLabel1");
            const winnerLabel2 = document.getElementById("winnerLabel2");

            if (winnerLabel1) {
                winnerLabel1.innerHTML = `
                    <input type="radio" name="winner" value="team1" id="winnerTeam1">
                    ${team1Name} Wins
                `;
            }

            if (winnerLabel2) {
                winnerLabel2.innerHTML = `
                    <input type="radio" name="winner" value="team2" id="winnerTeam2">
                    ${team2Name} Wins
                `;
            }

            if (!team1Id || !team2Id) 
            {
                return alert("Select both teams");
            }

    
            const title1 = document.getElementById("team1Title");
            const title2 = document.getElementById("team2Title");

            if (title1) title1.textContent = `${team1Name} Pokémon`;
            if (title2) title2.textContent = `${team2Name} Pokémon`;

            try 
            {
                
                const res1 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team1Id}`);
                const team1Pkmn = await res1.json();

                const res2 = await fetch(`/ascent_draft_league/api/matchup/get_team_roster.php?active_user_id=${team2Id}`);
                const team2Pkmn = await res2.json();

                renderPokemonSelection('team1Container', team1Pkmn, 1);
                renderPokemonSelection('team2Container', team2Pkmn, 2);

            } 
            catch(err) 
            {
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

    // ----------------------
    //      SUBMISSION
    // ----------------------

    const form = document.getElementById('add_matchup_form');
    if (form) 
    {
        form.addEventListener('submit', async e => {
            e.preventDefault();

            const player1 = document.getElementById('teamOneSelect')?.value;
            const player2 = document.getElementById('teamTwoSelect')?.value;
            const replayLink = document.getElementById('replayLink')?.value?.trim();
            const winner = document.querySelector('input[name="winner"]:checked');


            if (!winner) 
            {
                alert("Please select a winning team.");
                return;
            }

            if (!replayLink) 
            {
                alert("Replay link is required.");
                return;
            }

            const winnerValue = winner.value; // "team1" or "team2"

            const stats = [];

            // TEAM 1
            document.querySelectorAll('#team1MatchTable tr').forEach(row => {
                const rosterId = row.id.replace("pkmn-", "");
                const kills = row.querySelector(".killsInput")?.value ?? 0;
                const deaths = row.querySelector(".deathsInput")?.value ?? 0;

                stats.push({ 
                    roster_pkmn_id: rosterId,
                    active_user_id: player1,
                    kills: parseInt(kills),
                    deaths: parseInt(deaths),
                    used: 1
                });
            });

            // TEAM 2
            document.querySelectorAll('#team2MatchTable tr').forEach(row => {
                const rosterId = row.id.replace("pkmn-", "");
                const kills = row.querySelector(".killsInput")?.value ?? 0;
                const deaths = row.querySelector(".deathsInput")?.value ?? 0;

                stats.push({ 
                    roster_pkmn_id: rosterId,
                    active_user_id: player2,
                    kills: parseInt(kills),
                    deaths: parseInt(deaths),
                    used: 1
                });
            });

            // console.log("Replay link being sent:", replayLink);

            try 
            {
                const res = await fetch('/ascent_draft_league/api/matchup/submit_matchup.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ player1, player2, stats, replayLink, winner: winnerValue })
                });
                const data = await res.json();

                if (data.status === "success") {
                    alert("Matchup saved!");
                    window.location.href = '/ascent_draft_league/matchup.php';
                } else {
                    alert("Error: " + data.message);
                }
            } 
            catch(err) 
            {
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

        try 
        {
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
        } 
        catch (err) 
        {
            console.error("Network error:", err);
            alert("Failed to delete matchup. Check console.");
        }
    });




        // EDIT MATCHUP
    document.addEventListener("click", (e) => {
        if (!e.target.classList.contains("editMatchBtn")) return;

        const matchId = e.target.dataset.matchId;
        if (!matchId) return;

        // redirect to edit page
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

            // Set winner radio
            if (data.matchup.winner_active_user_id == data.matchup.player1_active_user_id) {
                document.querySelector('input[name="winner"][value="team1"]').checked = true;
            } else {
                document.querySelector('input[name="winner"][value="team2"]').checked = true;
            }

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

    // -----------------------------
    //      EDIT MATCHUP FORM
    // -----------------------------

    const editForm = document.getElementById('edit_matchup_form');

    if (editForm) 
    {
        editForm.addEventListener('submit', async e => {
            e.preventDefault();

            const formData = new FormData(editForm);

            const winner = document.querySelector('input[name="winner"]:checked');

            if (!winner) {
                alert("Please select a winner.");
                return;
            }

            const data = {
                matchup_id: formData.get('matchup_id'),
                replay_link: formData.get('replay_link'),
                winner: winner.value,
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

    // ------------------
    //      STANDINGS
    // ------------------

    


    async function loadStandings() {
        const tbody = document.getElementById("standingsBody");
        const standingsCont = document.getElementById("standingsCont");
        const standingsTable = document.getElementById("standingsTable");
        
        if (!tbody) return;

        try {
            const res = await fetch('/ascent_draft_league/api/standings/get_standings.php');
            const data = await res.json();

            tbody.innerHTML = "";

            if (!data || data.length === 0) 
            {
                standingsCont.innerHTML = 
                        `<p id="noMatches">
                            No matches have been played yet
                        </p>`;
                return;
            }

            let rank = 1;

            data.forEach(team => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${rank++}</td>
                    <td>${team.team_name}</td>
                    <td>${team.wins}</td>
                    <td>${team.losses}</td>
                `;
                tbody.appendChild(tr);
            });

            standingsTable.style.visibility = "visible"

        } catch (err) {
            console.error("Failed to load standings:", err);
        }
    }

    await loadStandings();

       
    // -------------
    // CLEAR MATCHUP
    // -------------
    const clearMatchupBtn = document.getElementById('clearMatchupBtn');
        if (clearMatchupBtn) 
        {
            clearMatchupBtn.addEventListener("click", async () => {
                if(!confirm("Are you sure you want to delete all matchups? This cannot be undone!")) return;

                try {
                    const response = await fetch('/ascent_draft_league/api/matchup/delete_all_matchups.php', { method: 'POST' });
                    const data = await response.json();
                    if(!response.ok) throw new Error(data.error || "Failed to clear matchups");
                    
                    alert("All matchups cleared successfully.");
                    location.reload(); // refresh the page to reflect changes
                } catch (error) {
                    console.error("Clear matchups error", error);
                    alert("Error clearing matchups. Check console.");
                }
            });
        }

    
    // -----------
    // ROLE UPDATE
    // -----------

    const tbody = document.querySelector("table tbody");
    const editRolePageEl = document.getElementById("editRolePage");

    if (!editRolePageEl || !tbody) {
        return;
    }

    const seasonId = editRolePageEl.dataset.season;

    try {
        const response = await fetch("/ascent_draft_league/api/admin/update_roles.php");
        const users = await response.json();

        users.forEach(user => {
            const tr = document.createElement("tr");

            // 1. Email
            const emailTd = document.createElement("td");
            emailTd.textContent = user.email;
            tr.appendChild(emailTd);

            // 2. Team Name
            const teamTd = document.createElement("td");
            teamTd.textContent = user.team_name;
            tr.appendChild(teamTd);

            // 3. Mascot
            const mascotTd = document.createElement("td");
            mascotTd.textContent = user.team_mascot_pkmn;
            tr.appendChild(mascotTd);

            // 4. Role column
            const roleTd = document.createElement("td");
            if (user.role === "owner") {
                roleTd.textContent = "owner"; // read-only
            } else {
                const roleSelect = document.createElement("select");
                ["admin", "user"].forEach(r => {
                    const option = document.createElement("option");
                    option.value = r;
                    option.textContent = r;
                    if (r === user.role) option.selected = true;
                    roleSelect.appendChild(option);
                });
                roleTd.appendChild(roleSelect);

                // Update role on change
                const competitorValue = user.competitor ?? "no";
                roleSelect.addEventListener("change", async () => {
                    await fetch("/ascent_draft_league/api/admin/update_roles.php", {
                        method: "POST",
                        headers: {"Content-Type": "application/json"},
                        body: JSON.stringify({
                            user_id: user.id,
                            role: roleSelect.value,
                            competitor: competitorValue,
                            season_id: seasonId
                        })
                    });
                });
            }
            tr.appendChild(roleTd);

            // 5. Competitor column (always editable)
            const competitorTd = document.createElement("td");
            const competitorSelect = document.createElement("select");
            ["yes", "no"].forEach(c => {
                const option = document.createElement("option");
                option.value = c;
                option.textContent = c;
                if (c === user.competitor) option.selected = true;
                competitorSelect.appendChild(option);
            });

            competitorSelect.addEventListener("change", async () => {
                await fetch("/ascent_draft_league/api/admin/update_roles.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        user_id: user.id,
                        role: user.role, // keep role as is
                        competitor: competitorSelect.value,
                        season_id: seasonId
                    })
                });
            });
            competitorTd.appendChild(competitorSelect);
            tr.appendChild(competitorTd);

            // 6. Season
            const seasonTd = document.createElement("td");
            seasonTd.textContent = user.season_id ?? "-";
            tr.appendChild(seasonTd);

            // 7. Created At
            const createdTd = document.createElement("td");
            createdTd.textContent = user.created_at;
            tr.appendChild(createdTd);

            tbody.appendChild(tr);
        });
    } catch (err) {
        console.error("Failed to load users:", err);
    }

    // console.log("Above clear matchup")

    

});