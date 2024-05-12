<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["planning"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main>

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["planning"]["sectionTitle"]) ?></h1>
			</section>

            <table class="width80 marginAuto marginTop40 border">
                <thead>
                <tr>
                    <td>Description activité</td>
                    <td>Date</td>
                    <td>Type / Desc</td>
                    <td>Localisation</td>
                </tr>
                </thead>
                <tbody id="tbodyUser">
                </tbody>
            </table>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>

<script type="text/javascript" defer>
    const user = new User()
    let planningData = []

    async function fillArray(){
        const tbodyUser = document.getElementById("tbodyUser")
        tbodyUser.innerHTML = ""
        planningData = await user.myPlanning()
        console.log(planningData)

        await deleteOldPlanning(planningData)

        createBodyTableau(tbodyUser, planningData, ["id_activite", "id_index_planning", "id_planning", "nom_index_planning", "user"])
        replaceCharacters()

    }

    async function deleteOldPlanning(data){
        let newData = []

        const day = today()

        data.forEach((onePlann)=>{

            if(onePlann.date_activite >= day){
                newData.push(onePlann)
            }

            console.log(onePlann)
        })
        console.log(newData)
        planningData = newData
    }

    async function onload(){
        await user.connect()
        startLoading()
        await fillArray()
        stopLoading()
    }

    onload()
</script>