<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/locales/fr.js'></script>

    <title><?php echo($data["vehicle"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["vehicle"]["title"]) ?></h1>

        <?php createDateField(); ?>

        <div class="tab flex flexAround nowrap">
            <button id="titletab1.1" class="tablinks width100"
                    onclick="openTab('tab1.1')"><?php echo htmlspecialchars($data["vehicle"]["tab1.1"]["title"]) ?></button>
            <?php if($role <= 3): ?>
            <button id="titletab1" class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo htmlspecialchars($data["vehicle"]["tab1"]["title"]) ?></button>
            <?php endif; ?>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo htmlspecialchars($data["vehicle"]["tab2"]["title"]) ?></button>
            <?php if($role <= 2): ?>
                <button id="titletab2" class="tablinks width100"
                        onclick="openTab('tab2.1')"><?php echo htmlspecialchars($data["vehicle"]["tab2.1"]["title"]) ?></button>
            <?php endif; ?>
            <?php if($role <= 3): ?>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo htmlspecialchars($data["vehicle"]["tab3"]["title"]) ?></button>
            <?php endif; ?>
            <?php if($role == 4): ?>
            <button class="tablinks width100"
                    onclick="openTab('tab4')"><?php echo htmlspecialchars($data["vehicle"]["tab4"]["title"]) ?></button>
            <?php endif; ?>
        </div>

        <!--Beneficiaire ne voient que ça pour réserver un véhicule-->
        <div id="tab1.1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab1.1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["name"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["capacity"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["place"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["entrepot"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["owner"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["immatriculation"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyListAvailable">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <?php if($role <= 3): ?>
            <div id="tab1" class="tabcontent">
                <h2 class="textCenter"><?php echo($data["vehicle"]["tab1"]["title"]) ?></h2>
                <table>
                    <thead>
                    <tr>
                        <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["name"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["capacity"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["place"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["entrepot"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["owner"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["immatriculation"] ?></td>
                        <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                    </tr>
                    </thead>
                    <tbody id="bodyList">
                    <!-- Les lignes de données seront insérées ici par JavaScript -->
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div id="tab2" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab2"]["title"]) ?></h2>
            <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
            <p id="bodyDetail"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?></p>
            <div id="calendar"></div>
        </div>

        <?php if($role <= 2): ?>
        <div id="tab2.1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab2.1"]["title"]) ?></h2>
            <div id="allCalendar"></div>
        </div>
        <?php endif; ?>

        <?php if($role <= 3): ?>
        <div id="tab3" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab3"]["title"]) ?></h2>
            <hr>
            <div class="textCenter">
                <input id="nameV" class="marginTop10 search-box" type="text"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["name"]) ?>"><br>
                <input id="capacityV" class="marginTop10 search-box" type="number"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["capacity"]) ?>"><br>
                <input id="placeV" class="marginTop10 search-box" type="number"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["place"]) ?>"><br>
                <input id="imma" class="marginTop10 search-box" type="text"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["immatriculation"]) ?>"><br>
                <input  type="hidden" id="appartenance" name="appartenance" value="<?php echo($role <= 2 ? "1" : "2"); ?>">
                <div id="leSelectAremplir" class="marginTop10 marginAuto search-box"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?>, Impossible to select storehouse</div><br>
                <input class="marginTop30" type="button" onclick="addVehicle()"
                       value="<?php echo($data["vehicle"]["tab3"]["title"]) ?>">
            </div>
        </div>
        <?php endif; ?>

        <?php if($role == 4): ?>
            <div id="tab4" class="tabcontent">
                <h2 class="textCenter"><?php echo($data["vehicle"]["tab4"]["title"]) ?></h2>
                <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
                <p id="bodyDetail"><?php echo($data["vehicle"]["tab4"]["errorMsg"]) ?></p>
            </div>
        <?php endif; ?>
    </div>

</main>
<?php include("../includes/footer.php"); ?>

</body>
</html>

<!-- ////////////////////////////////COMMON//////////////////////////////////// -->
<script type="text/javascript">

    let messageAlert = 0

    function dataVehicleOwnerReplace(dataVehicle){
        dataVehicle.forEach(vehicle => {
            if (vehicle.appartenance === "1") {
                vehicle.appartenance = "Association";
            } else {
                vehicle.appartenance = "Particulier";
            }
        });
        return dataVehicle
    }

    async function fillListAvailable() {
        console.log("Searching Available Vehicle")
        const bodyList = document.getElementById('bodyListAvailable')
        bodyList.innerHTML = ""

        const today = new Date(new Date(document.getElementById("start-date").value))
        const demain = new Date(new Date(document.getElementById("end-date").value))

        dataVehicle = await vehicle.getAvailableVehicle(today.toISOString(), demain.toISOString())
        if(dataVehicle === false){
            return
        }
        dataVehicle = dataVehicleOwnerReplace(dataVehicle)
        createBodyTableau(bodyList, dataVehicle, ["id_owner", "services", "contact"], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")
    }

    async function fillEnterpotList() {
        const entrepot = new EntrepotAdmin()
        await entrepot.connect()
        let dataEtr = await entrepot.getEntrepot()

        let select = document.getElementById("leSelectAremplir")
        select.innerHTML = ""

        let option = []
        const debut = "Choose"

        for (const key in dataEtr) {

            option[key] = {
                "value": dataEtr[key].id_entrepot,
                "text": dataEtr[key].nom
            }

        }
        const newSelect = createSelect(option, debut)
        newSelect.classList.add("marginTop10")
        newSelect.classList.add("search-box")
        newSelect.id ="entrepotV"


        select.parentNode.replaceChild(newSelect, select)
    }

    async function seeDetail(id_vehicle = null) {

        if (id_vehicle == null) {
            const bodyDetail = document.getElementById("bodyDetail")
            bodyDetail.innerHTML = ""
            return
        }

        startLoading()
        let data = await vehicle.getVehicleById(id_vehicle)
        if (data.length === 0) {
            popup("This vehicle don't exist ??")
            startLoading()
            fillList()
            openTab('tab1')
            stopLoading()
            return
        }

        data = dataVehicleOwnerReplace(data)


        const html = formatVehicleToHTML(data[0])

        const bodyDetail = document.getElementById("bodyDetail")
        bodyDetail.innerHTML = ""
        bodyDetail.appendChild(html)

        displayCalendar(data)
        openTab('tab2')
        stopLoading()
        if(messageAlert === 0){
            alert("Plz, click on the below 'previous' button to refresh the calendar below, otherwise nothing will appear :/")
            messageAlert++
        }
    }

    function formatVehicleToHTML(vehicleObject) {

        const vehicleCard = document.createElement('div');
        vehicleCard.classList.add("marginAuto")
        vehicleCard.classList.add("width50")
        vehicleCard.classList.add("border")

        const vehicleInfo = document.createElement('div');
        vehicleInfo.classList.add('vehicle-info');

        const idVehicle = document.createElement('p');
        idVehicle.innerHTML = '<strong>ID Véhicule:</strong> <span class="id-vehicule"></span>';
        idVehicle.querySelector('.id-vehicule').textContent = vehicleObject.id_vehicule;

        const nameVehicle = document.createElement('p');
        nameVehicle.innerHTML = '<strong>Nom du véhicule:</strong> <span class="nom-vehicule"></span>';
        nameVehicle.querySelector('.nom-vehicule').textContent = vehicleObject.nom_du_vehicules;

        const capacity = document.createElement('p');
        capacity.innerHTML = '<strong>Capacité:</strong> <span class="capacite"></span>';
        capacity.querySelector('.capacite').textContent = vehicleObject.capacite;

        const idWarehouse = document.createElement('p');
        idWarehouse.innerHTML = '<strong>ID Entrepôt:</strong> <span class="id-entrepot"></span>';
        idWarehouse.querySelector('.id-entrepot').textContent = vehicleObject.id_entrepot;

        const seats = document.createElement('p');
        seats.innerHTML = '<strong>Nombre de places:</strong> <span class="nombre-places"></span>';
        seats.querySelector('.nombre-places').textContent = vehicleObject.nombre_de_place;

        const owner = document.createElement('p');
        owner.innerHTML = '<strong>Owner:</strong> <span class="owner"></span>';
        owner.querySelector('.owner').textContent = vehicleObject.appartenance;

        const immatriculation = document.createElement('p');
        immatriculation.innerHTML = '<strong>Immatriculation:</strong> <span class="immatriculation"></span>';
        immatriculation.querySelector('.immatriculation').textContent = vehicleObject.immatriculation;

        const contact = document.createElement('p');
        contact.innerHTML = '<strong>Contact:</strong> <span class="contact"></span>';
        contact.querySelector('.contact').textContent = vehicleObject.contact[0].email + " - " + vehicleObject.contact[0].telephone;

        let button
        <?php if($role <= 2): ?>
            button = createButton(vehicle.msg["Delete"])
            button.setAttribute("onclick", "deleteVehicle(" + vehicleObject.id_vehicule + ")")
            vehicleInfo.append(idVehicle, nameVehicle, capacity, idWarehouse, seats, owner, immatriculation, contact, button);
        <?php endif; ?>

        <?php if($role >= 3): ?>
        vehicleInfo.append(idVehicle, nameVehicle, capacity, idWarehouse, seats, owner, immatriculation, contact);

        if(vehicle.email === vehicleObject.contact[0].email){
            button = createButton(vehicle.msg["Delete"])
            button.setAttribute("onclick", "deleteVehicle(" + vehicleObject.id_vehicule + ")")
            vehicleInfo.appendChild(button)
        }
            const button2 = createButton(vehicle.msg["Book"])
            button2.setAttribute("onclick", "bookingVehicle(" + vehicleObject.id_vehicule + ")")
            vehicleInfo.appendChild(button2)

        <?php endif; ?>

        vehicleCard.append(vehicleInfo);

        return vehicleCard;
    }

    function displayCalendar(data, idHtml = "calendar"){

        let event = []
        const dataInter = data[0].services
        for (let i = 0; i < dataInter.length; i++) {
            event.push({
                <?php if($role <= 3): ?>
                "title": dataInter[i].description + " - " + dataInter[i].user.email + " - " + dataInter[i].user.telephone,
                <?php endif; ?>
                <?php if($role == 4): ?>
                "title": dataInter[i].description + " - Vehicule Booked",
                <?php endif; ?>
                "start": (dataInter[i].date_debut).split(" ").join("T"),
                "end": (dataInter[i].date_fin).split(" ").join("T"),
                "description" : data[0].nom_du_vehicules + " (" + data[0].immatriculation + ")"
            })
        }
        
        
        var calendarEl = document.getElementById(idHtml);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour'
            },
            slotMinTime: '05:00:00',
            slotMaxTime: '23:00:00',
            events: event,
            eventClick: function(info) {
                alert('Service : ' + info.event.title + '\nVéhicule : ' + info.event.extendedProps.description);
            },
            firstDay: 1, // (0 pour dimanche, 1 pour lundi)
            allDaySlot: false
        });

        setTimeout(() => {
            calendar.render();
            calendar.updateSize();
        }, 100);

    }


    function startDateChange(){
        checkDateLogic()
        updateDate("start")
    }
    function endDateChange(){
        checkDateLogic()
        updateDate("end")
    }

    function checkDateLogic(){
        const startDate = document.getElementById("start-date");
        const endDate = document.getElementById("end-date");


        if(endDate.value < startDate.value){
            let tday = new Date()
            tday.setDate(new Date(startDate.value).getDate() + 1)
            endDate.value = tday.toISOString().split("T")[0]
        }
    }

</script>

<!-- ////////////////////////////////ADMIN//////////////////////////////////// -->
<?php if($role <= 2): ?>
    <script type="text/javascript">
        async function fillList() {
            console.log("Searching vehicle")
            const bodyList = document.getElementById('bodyList')
            bodyList.innerHTML = ""
            dataVehicle = await vehicle.getAllVehicle()
            dataVehicle = dataVehicleOwnerReplace(dataVehicle)
            console.log(dataVehicle)
            createBodyTableau(bodyList, dataVehicle, ["id_owner", "services", "contact"], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")
            fillAllCalendar()
        }

        async function fillAllCalendar(){
            console.log("Filling all Calendar")
            let data = await vehicle.getAllBookedVehicle()
            data = dataVehicleOwnerReplace(data)
            displayCalendar(data, 'allCalendar')
            console.log("Finished")

        }
    </script>
<?php endif; ?>

<!-- ////////////////////////////////BÉNÉVOLES//////////////////////////////////// -->
<?php if($role == 3): ?>
    <script type="text/javascript">
        async function fillList() {
            console.log("Searching vehicle")
            const bodyList = document.getElementById('bodyList')
            bodyList.innerHTML = ""
            dataVehicle = await vehicle.getAllMyVehicle()
            dataVehicle = dataVehicleOwnerReplace(dataVehicle)
            console.log(dataVehicle)
            createBodyTableau(bodyList, dataVehicle, ["id_owner", "services", "contact"], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")
        }

        tabs = [document.getElementById("titletab1.1"), document.getElementById("titletab1")]

        tabs[0].innerHTML = "My Free Vehicle"
        tabs[1].innerHTML = "My Vehicles"
    </script>
<?php endif; ?>

<!-- ////////////////////////////////ADMIN-BÉNÉVOLES-COMMON//////////////////////////////////// -->
<?php if($role <= 3): ?>
<script type="text/javascript" defer>

    const vehicle = new VehicleAdmin()
    let dataVehicle = []

    async function deleteVehicle(id_vehicle) {
        startLoading()
        await vehicle.deleteVehicle(id_vehicle)
        fillList()
        fillListAvailable()
        seeDetail()
        openTab("tab1")
        stopLoading()
    }

    async function addVehicle() {
        startLoading()
        let namev = document.getElementById("nameV")
        let placev = document.getElementById("placeV")
        let capacityv = document.getElementById("capacityV")
        let entrepotv = document.getElementById("entrepotV")
        let imma = document.getElementById("imma")
        let appartenance = document.getElementById("appartenance")

        if (namev == null || placev == null || capacityv == null || imma == null || appartenance == null) {
            stopLoading()
            popup("Error")
            return
        }

        namev = namev.value
        placev = placev.value
        capacityv = capacityv.value
        entrepotv = entrepotv.value
        imma = imma.value
        appartenance = appartenance.value

        if(entrepotv === "Choose"){
            popup("Vous devez choisir un entrepot")
            stopLoading()
            return
        }

        const data = {
            "capacite": capacityv,
            "nom_du_vehicules": namev,
            "nombre_de_place": placev,
            "id_entrepot": entrepotv,
            "immatriculation" : imma,
            "appartenance" : appartenance
        }
        console.log(data)
        await vehicle.createVehicle(data)
        fillListAvailable()
        await fillList()
        openTab("tab1")
        stopLoading()

    }

    // Function only paste when it's user role <= 3
    async function updateDate(){
        startLoading()
        await fillListAvailable()
        stopLoading()
    }

    async function onload() {
        startLoading()
        openTab('tab1.1')

        await fillEnterpotList()
        await vehicle.connect()
        fillListAvailable()
        await fillList()

        stopLoading()
    }

    onload()

</script>
<?php endif; ?>

<!-- ////////////////////////////////BÉNÉFICIAIRES//////////////////////////////////// -->
<?php if($role == 4): ?>
    <script type="text/javascript" defer>

        const vehicle = new VehicleAdmin()
        let dataVehicle = []

        // Function only paste when it's user role == 4
        async function updateDate(){
            startLoading()
            await fillListAvailable()
            stopLoading()
        }

        async function onload() {
            startLoading()
            openTab('tab1.1')
            await vehicle.connect()
            await fillListAvailable()
            stopLoading()
        }

        onload()
    </script>
<?php endif; ?>

